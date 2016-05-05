<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExamImageRequest;
use App\Http\Requests\Api\V1\ExamRequest;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Get the exam list.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        return Exam::with(['category'])->latest()->paginate(6);
    }

    /**
     * Create a new exam.
     *
     * @param ExamRequest $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(ExamRequest $request)
    {
        $exam = Exam::create($request->only(['category_id', 'name', 'enable']));

        return $this->response->created(null, $exam);
    }

    /**
     * Get exam images.
     *
     * @param int $id
     *
     * @return array
     */
    public function image($id)
    {
        $exam = Exam::findOrFail($id);

        return $this->transformImage($exam);
    }

    /**
     * Upload images.
     *
     * @param ExamImageRequest $request
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function storeImage(ExamImageRequest $request, $id)
    {
        $exam = Exam::findOrFail($id);

        foreach ($request->file('image') as $file) {
            $exam->addMedia($file)
                ->setFileName(random_int(1000000000, 2147483647).'.'.$file->guessExtension())
                ->toMediaLibrary();
        }

        return $this->response->created(null, $this->transformImage($exam));
    }

    /**
     * Transform media to url.
     *
     * @param Exam $exam
     *
     * @return array
     */
    protected function transformImage(Exam $exam)
    {
        $result = [];

        foreach ($exam->getMedia() as $media) {
            $result[] = [
                'url'   => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
            ];
        }

        return $result;
    }
}
