<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExamQuestionImportRequest;
use App\Http\Requests\Api\V1\ExamQuestionRequest;
use Excel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\Exam;
use Infoexam\Eloquent\Models\Option;
use Infoexam\Eloquent\Models\Question;
use Maatwebsite\Excel\Collections\CellCollection;
use Ramsey\Uuid\Uuid;

class ExamQuestionController extends Controller
{
    /**
     * Get the exam questions.
     *
     * @param string $name
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function index($name)
    {
        return Exam::with([
            'category', 'questions' => function (HasMany $query) {
                $query->getBaseQuery()->select(['uuid', 'exam_id']);
            }, ])
            ->where('name', $name)
            ->firstOrFail(['id', 'name']);
    }

    /**
     * Create new exam question.
     *
     * @param ExamQuestionRequest $request
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(ExamQuestionRequest $request, $name)
    {
        $exam = Exam::where('name', $name)->firstOrFail(['id']);

        $question = $exam->questions()->save(new Question($request->input('question')));

        foreach ($request->input('option') as $option) {
            $question->options()->save(new Option($option));
        }

        return $this->response->created();
    }

    /**
     * Get exam question.
     *
     * @param string $name
     * @param string $uuid
     *
     * @return Question
     */
    public function show($name, $uuid)
    {
        if (! Exam::where('name', $name)->exists()) {
            $this->response->errorNotFound();
        }

        $question = Question::with(['difficulty', 'options'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $question->makeVisible(['difficulty_id', 'question_id']);

        return $question;
    }

    /**
     * Update exam question.
     *
     * @param ExamQuestionRequest $request
     * @param string $name
     * @param string $uuid
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(ExamQuestionRequest $request, $name, $uuid)
    {
        if (! Exam::where('name', $name)->exists()) {
            $this->response->errorNotFound();
        }

        $question = Question::with(['options'])->where('uuid', $uuid)->firstOrFail();

        $question->update($request->input('question'));

        $this->updateOption($question, $request->input('option'));

        return $this->response->noContent();
    }

    /**
     * Insert, update or delete the exam question's options.
     *
     * @param Question $question
     * @param $options
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function updateOption(Question $question, $options)
    {
        $existIds = $question->getRelation('options')->pluck('id')->all();

        foreach ($options as $option) {
            if (! isset($option['id'])) {
                $question->options()->save(new Option($option));
            } elseif (in_array($option['id'], $existIds, true)) {
                Option::where('id', $option['id'])->update($option);

                $updated[] = $option['id'];
            }
        }

        Option::whereIn('id', array_diff($existIds, $updated ?? []))->delete();
    }

    /**
     * Delete the question and it's related data.
     *
     * @param string $name
     * @param string $uuid
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($name, $uuid)
    {
        if (! Exam::where('name', $name)->exists()) {
            $this->response->errorNotFound();
        }

        $question = Question::withCount(['papers'])->where('uuid', $uuid)->firstOrFail();

        if ($question->getAttribute('papers_count') > 0) {
            $this->response->error('nonEmptyPaper', 422);
        } elseif (! $question->delete()) {
            $this->response->errorInternal();
        }

        return $this->response->noContent();
    }

    /**
     * Get the exam questions id and uuid.
     *
     * @param string $name
     *
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function groups($name)
    {
        $exam = Exam::where('name', $name)->firstOrFail(['id']);

        return Question::where('exam_id', $exam->getKey())
            ->whereNull('question_id')
            ->latest()
            ->get(['id', 'uuid']);
    }

    /**
     * Import questions using file.
     *
     * @param ExamQuestionImportRequest $request
     * @param string $name
     *
     * @return array
     */
    public function import(ExamQuestionImportRequest $request, $name)
    {
        $exam = Exam::where('name', $name)->firstOrFail(['id']);

        $sheet = Excel::load($request->file('file')->getRealPath())->get();

        $result = [];

        $sheet->each(function (CellCollection $item, $key) use ($exam, &$result) {
            $result[] = $this->saveQuestion($exam, $item);
        });

        return $result;
    }

    /**
     * Save exam question.
     *
     * @param Exam $exam
     * @param CellCollection $item
     *
     * @return Question
     */
    protected function saveQuestion(Exam $exam, CellCollection $item)
    {
        static $difficulties = null;

        if (is_null($difficulties)) {
            $difficulties = Category::getCategories('exam.difficulty')->pluck('id', 'name')->toArray();
        }

        $question = $exam->questions()->save(new Question([
            'uuid' => $item->get('uuid') ?? Uuid::uuid4()->toString(),
            'content' => $item->get('content'),
            'multiple' => boolval($item->get('multiple')),
            'difficulty_id' => $difficulties[$item->get('difficulty')],
            'explanation' => $item->get('explanation'),
        ]));

        $this->saveOption($question, $item);

        return $question;
    }

    /**
     * Save exam question option.
     *
     * @param Question $question
     * @param CellCollection $item
     *
     * @return void
     */
    protected function saveOption(Question $question, CellCollection $item)
    {
        $count = $item->get('options', 0);

        for ($i = 1; $i <= $count; ++$i) {
            $question->options()->save(new Option([
                'content' => $item->get("option_{$i}_content"),
                'answer' => boolval($item->get("option_{$i}_answer")),
            ]));
        }
    }
}
