<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Explanation;
use App\Infoexam\Exam\Option;
use App\Infoexam\Exam\Question;
use App\Infoexam\Exam\Set;
use App\Infoexam\Image\Upload;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamSetQuestionController extends Controller
{
    /**
     * ExamSetQuestionController constructor.
     */
    public function __construct()
    {
        // 設定 middleware
        $this->middleware('auth:admin', ['except' => ['index']]);
    }

    /**
     * 取得題庫題目
     *
     * @param int $setId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($setId)
    {
        $set = Set::with(['questions' => function (HasMany $relation) {
            $relation->getQuery()->with(['difficulty'])->getQuery()
                ->select(['id', 'exam_set_id', 'content', 'difficulty_id', 'multiple']);
        }])->findOrFail($setId, ['id', 'name']);

        return response()->json($set);
    }

    /**
     * 新增題目
     *
     * @param Requests\ExamSetQuestionRequest $request
     * @param int $setId
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ExamSetQuestionRequest $request, $setId)
    {
        // 新增題目
        $question = Question::create([
            'exam_set_id' => $setId,
            'content' => $request->input('question.content'),
            'difficulty_id' => $request->input('difficulty_id'),
            'multiple' => $request->input('multiple'),
        ]);

        // 新增解析（如果有的話）
        if ($request->has('explanation')) {
            $question->explanation()->save(new Explanation(['content' => $request->input('explanation')]));
        }

        // 新增圖片（如果有的話）
        if (count($request->input('question.image', []))) {
            new Upload($request->input('question.image'), [
                'id' => $question->getAttribute('id'),
                'type' => Question::class
            ]);
        }

        // 新增選項
        foreach ($request->input('option') as $option) {
            $temp = $question->options()->save(new Option(['content' => $option['content']]));

            if (isset($option['image'])) {
                new Upload($option['image'], ['id' => $temp->getAttribute('id'), 'type' => Option::class]);
            }
        }
    }

    /**
     * 取得指定題目資料
     *
     * @param int $setId
     * @param int $questionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($setId, $questionId)
    {
        $question = Question::with(['options' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'exam_question_id', 'content']);
        }, 'difficulty', 'explanation' => function (HasOne $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'exam_question_id', 'content']);
        }, 'answers', 'set' => function (BelongsTo $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'name']);
        }])
            ->where('exam_set_id', '=', $setId)
            ->findOrFail($questionId, ['id', 'exam_set_id', 'content', 'difficulty_id', 'multiple']);

        $question->setRelation('answers', $question->getRelation('answers')->pluck('id'));

        return response()->json($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Requests\ExamSetQuestionRequest $request
     * @param int $setId
     * @param int $questionId
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\ExamSetQuestionRequest $request, $setId, $questionId)
    {
        //
    }

    /**
     * 刪除指定題目與其相關資料（through model event）
     * 相關資料包括：圖片、選項、解析
     *
     * @param int $setId
     * @param int $questionId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($setId, $questionId)
    {
        Question::where('exam_set_id', '=', $setId)->findOrFail($questionId, ['id'])->delete();

        return $this->ok();
    }
}