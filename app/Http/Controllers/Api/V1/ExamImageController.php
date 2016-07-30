<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExamImageRequest;
use Illuminate\Http\Request;

class ExamImageController extends Controller
{
    /**
     * Get exam images.
     *
     * @param Request $request
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, $name)
    {
        $exam = Exam::where('name', $name)->firstOrFail(['id']);

        return $this->transformImage($exam, $request->input('collection', 'default'));
    }

    /**
     * Upload images to exam.
     *
     * @param ExamImageRequest $request
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(ExamImageRequest $request, $name)
    {
        $exam = Exam::where('name', $name)->firstOrFail(['id']);

        $exam->uploadImages($request->file('image'), $request->input('collection', 'default'));

        return $this->transformImage($exam, $request->input('collection', 'default'));
    }

    /**
     * Delete the exam image.
     *
     * @param string $name
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($name, $id)
    {
        $exam = Exam::where('name', $name)->firstOrFail(['id']);

        $exam->deleteMedia($id);

        return $this->response->noContent();
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
