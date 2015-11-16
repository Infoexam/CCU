<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ExamSetQuestionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'question' => 'required|array',
            'question.content' => 'required|max:1000|string',
            'question.image' => 'array',
            'option' => 'required|array',
            'explanation' => 'max:1000|string',
            'difficulty_id' => 'required',
            'multiple' => 'required|boolean',
            'answer' => 'required|array',
        ];

        $question = $this->input('question', []);

        // 題目圖片
        if (is_array($question['image'])) {
            foreach (array_keys($question['image']) as $key) {
                $rules["question.image.{$key}"] = 'image';
            }
        }

        $options = $this->input('option', []);

        // 選項
        foreach (array_keys($options) as $key) {
            $rules["option.{$key}.content"] = 'required|max:1000|string';

            // 選項圖片
            $rules["option.{$key}.image"] = 'array';
            if (is_array($options[$key]['image'])) {
                foreach (array_keys($options[$key]['image']) as $subKey) {
                    $rules["option.{$key}.image.{$subKey}"] = 'image';
                }
            }
        }

        // 答案
        foreach ($this->input('answer', []) as $key) {
            $rules["answer.{$key}"] = 'required|integer';
        }

        return $rules;
    }
}
