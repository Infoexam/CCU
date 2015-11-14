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
     * @param Request $request
     * @param int $paperId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $paperId)
    {
        $paper = Paper::with(['questions' => function (BelongsToMany $relation) {
            $relation->getQuery()->with(['difficulty'])->getQuery()
                ->select(['exam_questions.id', 'content', 'difficulty_id', 'multiple']);
        }])->findOrFail($paperId, ['id', 'name']);

        if ($request->has('onlyId')) {
            $paper = $paper->getRelation('questions')->pluck('id');
        }

        return response()->json($paper);
    }

    /**
     * 更新試卷題目
     *
     * @param Request $request
     * @param int $paperId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, $paperId)
    {
        if (! is_array($questions = $request->input('questions', []))) {
            $questions = [$questions];
        }

        Paper::findOrFail($paperId, ['id'])->questions()->sync($questions);

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
