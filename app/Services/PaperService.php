<?php

namespace App\Services;

use App\Repositories\PaperRepository;
use Carbon\Carbon;

class PaperService
{
    /**
     * @var PaperRepository
     */
    private $repository;

    /**
     * @var QuestionService
     */
    private $questionService;

    /**
     * PaperService constructor.
     *
     * @param PaperRepository $repository
     * @param QuestionService $questionService
     */
    public function __construct(PaperRepository $repository, QuestionService $questionService)
    {
        $this->repository = $repository;
        $this->questionService = $questionService;
    }

    /**
     * Create paper from specific exams.
     *
     * @param array $exams
     * @param int $limit
     *
     * @return \App\Exams\Paper
     */
    public function createFromExams(array $exams, $limit = 50)
    {
        $this->repository
            ->getPaper()
            ->fill([
                'name' => 'automatically generated from '.Carbon::now(),
                'automatic' => true,
            ])
            ->save();

        $ids = $this->questionService
            ->randomQuestionsFromExams($exams, ['id', 'question_id'], $limit)
            ->pluck('id')
            ->toArray();

        $this->repository
            ->getPaper()
            ->questions()
            ->sync($ids);

        return $this->repository->getPaper();
    }
}
