<?php

namespace App\Services;

use App\Repositories\QuestionRepository;
use Illuminate\Support\Collection;

class QuestionService
{
    /**
     * @var QuestionRepository
     */
    private $repository;

    /**
     * QuestionService constructor.
     *
     * @param QuestionRepository $repository
     */
    public function __construct(QuestionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get random questions from exams.
     *
     * @param array $exams
     * @param array $columns
     * @param int $limit
     *
     * @return Collection
     */
    public function randomQuestionsFromExams(array $exams, $columns = ['*'], $limit = 50)
    {
        return $this->randomQuestions(
            $this->repository->fromExams($exams, $columns),
            $limit
        );
    }

    /**
     * Get random questions from question collection.
     *
     * @param Collection $questions
     * @param int $limit
     * @param bool $includeGroup
     *
     * @return Collection
     */
    public function randomQuestions(Collection $questions, $limit = 50, $includeGroup = true)
    {
        $questions = $questions->shuffle();

        while ($questions->count() > 0 && ($limit-- > 0)) {
            $result[] = $question = $questions->pop();

            $count = $question->getRelation('questions')->count();

            if ($includeGroup && $count > 0) {
                $result = array_merge($result, $question->getRelation('questions')->all());

                $limit -= $count;
            }
        }

        return new Collection($result ?? []);
    }
}
