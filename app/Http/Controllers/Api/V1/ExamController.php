<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExamImageRequest;
use App\Http\Requests\Api\V1\ExamRequest;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Get the exam list.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $exams = Exam::with(['category'])->latest()->paginate(5);

        foreach ($exams->items() as $exam) {
            $media = $exam->getFirstMedia('cover');

            $exam->setAttribute('cover', is_null($media) ? null : $media->getUrl('thumb'));
        }

        return $exams;
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

        if (! $exam->exists) {
            $this->response->errorInternal();
        }

        $exam->uploadImages($request->file('cover'), 'cover');

        return $this->response->created(null, $exam);
    }

    /**
     * Get exam images.
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function image(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        return $this->transformImage($exam, $request->input('collection', 'default'));
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

        $exam->uploadImages($request->file('image'), $request->input('collection', 'default'));

        return $this->response->created();
    }

    /**
     * Transform media to url.
     *
     * @param Exam $exam
     * @param string $collection
     *
     * @return array
     */
    protected function transformImage(Exam $exam, $collection)
    {
        $result = [];

        foreach ($exam->getMedia($collection) as $media) {
            $result[] = [
                'url'   => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
            ];
        }

        return $result;
    }
}
