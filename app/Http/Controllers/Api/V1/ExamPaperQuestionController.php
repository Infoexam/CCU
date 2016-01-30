<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ExamPaperQuestionRequest;
use App\Infoexam\Exam\Paper;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class ExamPaperQuestionController extends ApiController
{
    /**
     * ExamPaperQuestionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * 取得試卷題目
     *
     * @param Request $request
     * @param int $paperId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $paperId)
    {
        $paper = Paper::find($paperId, ['id', 'name']);

        if (is_null($paper)) {
            return $this->responseNotFound();
        }

        $paper->load(['questions' => function (BelongsToMany $relation) {
            $relation->getQuery()
                ->with(['difficulty', 'set'])
                ->getQuery()
                ->select(['exam_questions.id', 'exam_set_id', 'content', 'difficulty_id', 'multiple']);
        }]);

        if ($request->has('onlyId')) {
            $paper = $paper->getRelation('questions')->pluck('id');
        }

        return $this->setData($paper)->responseOk();
    }

    /**
     * 更新試卷題目
     *
     * @param ExamPaperQuestionRequest $request
     * @param int $paperId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExamPaperQuestionRequest $request, $paperId)
    {
        $paper = Paper::find($paperId, ['id']);

        if (is_null($paper)) {
            return $this->responseNotFound();
        }

        $paper->questions()->sync($request->input('questions'));

        return $this->responseOk();
    }

    /**
     * 刪除試卷題目
     *
     * @param int $paperId
     * @param int $questionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($paperId, $questionId)
    {
        $question = DB::table('exam_paper_exam_question')
            ->where('exam_paper_id', $paperId)
            ->where('exam_question_id', $questionId)
            ->first();

        if (is_null($question)) {
            return $this->responseNotFound();
        }

        $question->delete();

        return $this->responseOk();
    }
}
