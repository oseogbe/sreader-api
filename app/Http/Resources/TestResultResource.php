<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Book;
use App\Models\Copy;
use App\Models\Test;
use Exception;

class TestResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->test->testable instanceof Book) {
            $testable = 'Book';
        } elseif ($this->test->testable instanceof Copy) {
            $testable = 'Copy';
        } elseif ($this->test->testable instanceof Test) {
            $testable = 'Test';
        }

        return [
            'testable_type' =>  $testable,
            'subject' => $this->test->testable->title,
            'test_type' => $this->test->type,
            'test_id' => $this->test_id,
            'no_of_questions' => $this->questions,
            'correct_answers' => $this->correct,
            'score' => $this->score,
        ];
    }
}
