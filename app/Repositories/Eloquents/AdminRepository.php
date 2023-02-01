<?php

namespace App\Repositories\Eloquents;

use App\Models\Admin;
use App\Models\Book;
use App\Models\SchoolAdmin;
use App\Models\Test;
use App\Repositories\Interfaces\AdminRepositoryInterface;

class AdminRepository implements AdminRepositoryInterface
{
    function getAdminByEmail(string $email): array
    {
        return Admin::where('email', $email)->firstOrFail()->toArray();
    }

    function getSchoolAdminByEmail(string $email): array
    {
        return SchoolAdmin::with('school')->where('email', $email)->firstOrFail()->toArray();
    }

    function createAdminAuthToken(string $admin_id): array
    {
        $admin = Admin::findOrFail($admin_id);

        $admin->tokens()->delete();

        if($admin->role == 'superadmin')
        {
            return $admin->createToken('sreader-token', ['super-admin'])->toArray();
        }

        return $admin->createToken('sreader-token', ['app-admin'])->toArray();
    }

    function createSchoolAdminAuthToken(string $admin_id): array
    {
        $admin = SchoolAdmin::findOrFail($admin_id);

        $admin->tokens()->delete();

        return $admin->createToken('sreader-token', ['school-admin'])->toArray();
    }

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

    // foreach (request()->directors as $applicants) {
    //     $director = new Director();

    //     $path = $applicants['photo']->store('applicants', 'public');

    //     $director->name = $applicants['name'];
    //     $director->address = $applicants['address'];
    //     $director->email = $applicants['email'];
    //     $director->photo = $path;
    //     $director->applicant_id = $applicant->id;
    //     $director->save();
    // }

    // $applicant->approvals()->saveMany(
    //     [
    //         Approval::forceCreate(['name' => 'Executive Office Approved']),
    //         Approval::forceCreate(['name' => 'IPNIS Approved']),
    //         Approval::forceCreate(['name' => 'Legal Approved']),
    //         Approval::forceCreate(['name' => 'Zone Operator Approved']),
    //     ]
    // );
}
