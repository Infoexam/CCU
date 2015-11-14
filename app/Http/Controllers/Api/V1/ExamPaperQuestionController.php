<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Paper;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class ExamPaperQuestionController extends Controller
{
    /**
     * 取得試卷題目
     *
     * @param int $paperId
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function index($paperId)
    {
        $paper = Paper::with(['questions' => function (BelongsToMany $relation) {
            $relation->getQuery()->getQuery()->select(['exam_questions.id']);
        }])->findOrFail($paperId, ['id']);

        return response()->json($paper->getRelation('questions')->pluck('id'));
    }

    /**
     * 更新試卷題目
     *
     * @param Request $request
     * @param int $paperId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(Request $request, $paperId)
    {
        Paper::findOrFail($paperId, ['id'])->questions()->sync($request->input('questions', []));

        return $this->ok();
    }

    /**
     * 刪除試卷題目
     *
     * @param int $paperId
     * @param int $questionId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($paperId, $questionId)
    {
        \DB::table('exam_paper_exam_question')
            ->where('exam_paper_id', '=', $paperId)
            ->where('exam_question_id', '=', $questionId)
            ->delete();

        return $this->ok();
    }
}
