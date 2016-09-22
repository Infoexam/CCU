<?php

namespace App\Repositories;

use App\Exams\Question;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionRepository
{
    /**
     * @var Question
     */
    private $question;

    /**
     * QuestionRepository constructor.
     *
     * @param Question $question
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Get question according to specific exams.
     *
     * @param array $exams
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fromExams(array $exams, $columns = ['*'])
    {
        return $this->getQuestion()
            ->with(['questions' => function (HasMany $query) use ($columns) {
                $query->select($columns);
            }])
            ->whereIn('exam_id', $exams)
            ->whereNull('question_id')
            ->get($columns);
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
