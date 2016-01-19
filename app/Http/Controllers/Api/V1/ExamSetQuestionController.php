<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ExamSetQuestionRequest;
use App\Infoexam\Exam\Explanation;
use App\Infoexam\Exam\Option;
use App\Infoexam\Exam\Question;
use App\Infoexam\Exam\Set;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamSetQuestionController extends ApiController
{
    /**
     * ExamSetQuestionController constructor.
     */
    public function __construct()
    {
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
        $set = Set::find($setId, ['id', 'name']);

        if (is_null($set)) {
            return $this->responseNotFound();
        }

        $set->load([
            'questions' => function (HasMany $relation) {
                $relation->getQuery()->with(['difficulty'])->getQuery()
                ->select(['id', 'exam_set_id', 'content', 'difficulty_id', 'multiple']);
            },
            'questions.options' => function (HasMany $relation) {
                $relation->getQuery()->getQuery()->select(['id', 'exam_question_id', 'content']);
            }
        ]);

        return $this->setData($set)->responseOk();
    }

    /**
     * 新增題目
     *
     * @param ExamSetQuestionRequest $request
     * @param int $setId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExamSetQuestionRequest $request, $setId)
    {
        // 確認題庫存在
        if (is_null(Set::find($setId, ['id']))) {
            return $this->responseNotFound();
        }

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
        if ($request->hasFile('question.image')) {
            $question->uploadImages($request->file('question.image'));
        }

        // 新增選項
        foreach ($request->input('option') as $option) {
            $temp = $question->options()->save(new Option(['content' => $option['content']]));

            if (isset($option['image'])) {
                $temp->uploadImages($option['image']);
            }
        }

        return $this->setData($question)->responseCreated();
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
        $question = Question::where('exam_set_id', $setId)
            ->find($questionId, ['id', 'exam_set_id', 'content', 'difficulty_id', 'multiple']);

        if (is_null($question)) {
            return $this->responseNotFound();
        }

        $question->load([
            'options' => function (HasMany $relation) {
                $relation->getQuery()->getQuery()->select(['id', 'exam_question_id', 'content']);
            },
            'difficulty',
            'explanation' => function (HasOne $relation) {
                $relation->getQuery()->getQuery()->select(['id', 'exam_question_id', 'content']);
            },
            'answers',
            'set' => function (BelongsTo $relation) {
                $relation->getQuery()->getQuery()->select(['id', 'name']);
            }
        ]);

        $question->setRelation('answers', $question->getRelation('answers')->pluck('id'));

        return $this->setData($question)->responseOk();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExamSetQuestionRequest $request
     * @param int $setId
     * @param int $questionId
     * @return \Illuminate\Http\Response
     *
     * @todo finish it
     */
    public function update(ExamSetQuestionRequest $request, $setId, $questionId)
    {
        //
    }

    /**
     * 刪除指定題目與其相關資料（through model event）
     * 相關資料包括：圖片、選項、解析
     *
     * @param int $setId
     * @param int $questionId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($setId, $questionId)
    {
        $question = Question::where('exam_set_id', $setId)->find($questionId, ['id']);

        if (is_null($question)) {
            return $this->responseNotFound();
        }

        $question->delete();

        return $this->responseOk();
    }
}
