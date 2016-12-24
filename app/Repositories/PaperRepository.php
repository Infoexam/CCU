<?php

namespace App\Repositories;

use Infoexam\Eloquent\Models\Paper;

class PaperRepository
{
    /**
     * @var Paper
     */
    private $paper;

    /**
     * PaperRepository constructor.
     *
     * @param Paper $paper
     */
    public function __construct(Paper $paper)
    {
        $this->paper = $paper;
    }

    /**
     * @return Paper
     */
    public function getPaper()
    {
        return $this->paper;
    }
}
