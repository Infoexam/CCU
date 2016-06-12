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
     * @param int $examId
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId, ['id']);

        return $this->transformImage($exam, $request->input('collection', 'default'));
    }

    /**
     * Upload images to exam.
     *
     * @param ExamImageRequest $request
     * @param int $examId
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(ExamImageRequest $request, $examId)
    {
        $exam = Exam::findOrFail($examId, ['id']);

        $exam->uploadImages($request->file('image'), $request->input('collection', 'default'));

        return $this->response->created();
    }

    /**
     * Delete the exam image.
     *
     * @param int $examId
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($examId, $id)
    {
        $exam = Exam::findOrFail($examId, ['id']);

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
