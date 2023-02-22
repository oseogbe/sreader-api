<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\TestResource;
use App\Http\Resources\TestResultResource;
use App\Models\Book;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Repositories\Interfaces\TestRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TestRepository implements TestRepositoryInterface
{
    function createTestForBook(string $book_id, int $term, int $week, string $type): array
    {
        $book = Book::find($book_id);

        $test = $book->tests()->create([
            'term' => $term,
            'week' => $week,
            'type' => $type
        ]);

        return $test->toArray();
    }

    function submitTestQuestions(int $test_id, array $questions): array
    {
        $test = Test::find($test_id);

        $localfolder = public_path('firebase-temp-uploads') .'/';

        foreach ($questions as $key => $question) {

            $question_image_path = null;

            if(isset($question['image'])) {
                $image = $question['image'];
                $question_image = $image->hashName();

                if ($image->move($localfolder, $question_image)) {
                    $uploadedfile = fopen($localfolder.$question_image, 'r');
                    $question_image_uploaded = app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => 'question_images/' . $question_image]);
                }

                unlink($localfolder . $question_image);

                $question_image_path = 'question_images/'. $question_image;
            }

            $test->questions()->create([
                'question' => $question['question'],
                'image' => $question_image_path,
                'options' => $question['options'],
                'correct_option' => $question['correct_option']
            ]);
        }

        return $test->load('questions')->toArray();
    }

    function getTestsForBook(string $book_id)
    {
        $tests = Test::whereHasMorph(
                    'testable',
                    [Book::class],
                    function(Builder $query) use ($book_id) {
                        $query->where('id', $book_id);
                    }
                )->get();

        return TestResource::collection($tests);
    }

    function processTestResult(int $test_id, array $selected_options)
    {
        $test = Test::find($test_id);

        $questions = $test->questions();

        $no_of_questions = $questions->count();

        $correct = 0;

        foreach ($selected_options as $selected_option) {
            $question = TestQuestion::find(data_get($selected_option, 'question_id'));

            if(data_get($selected_option, 'answer') === $question['correct_option']) {
                $correct++;
            }
        }

        $result = $test->results()->create([
            'student_id' => Auth::id(),
            'questions' => $no_of_questions,
            'correct' => $correct,
            'score' => round($correct / $no_of_questions * 100, 1)
        ]);

        return TestResultResource::make($result);
    }
}
