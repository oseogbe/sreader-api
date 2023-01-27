<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentRepository implements StudentRepositoryInterface
{
    function createStudent(array $student_data): array
    {
        return Student::create($student_data)->toArray();
    }

    function getStudentByID(string $student_id): array
    {
        return Student::findOrFail($student_id)->toArray();
    }

    function getStudentByEmail(string $email): array
    {
        return Student::where('email', $email)->firstOrFail()->toArray();
    }

    function getStudentByPhoneNumber(string $phone_number): array
    {
        return Student::where('phone_number', $phone_number)->firstOrFail()->toArray();
    }

    function updateStudent(string $student_id, array $data): bool
    {
        return Student::findOrFail($student_id)->update($data);
    }

    function createStudentAuthToken(string $student_id): array
    {
        $student = Student::findOrFail($student_id);

        $student->tokens()->delete();

        return $student->createToken('sreader-token', ['student'])->toArray();
    }

    function createResetPasswordOTP(string $student_id): string
    {
        $student = Student::findOrFail($student_id);

        $otp_record = DB::table('password_resets')->where([
            ['email', $student->email]
        ]);

        if ($otp_record->exists()) {
            $otp_record->delete();
        }

        $token = random_int(100000, 999999);

        $otp_created = DB::table('password_resets')->insert([
            'email' => $student->email,
            'token' =>  $token,
            'created_at' => Carbon::now()
        ]);

        return $token;
    }

    public function getResetPasswordOTP(string $email): array|bool
    {
        if($reset_pin = DB::table('password_resets')->where('email', $email)->first())
        {
            return (array) $reset_pin;
        }

        return false;
    }

    public function deleteResetPasswordOTP(string $email): bool
    {
        return DB::table('password_resets')->where('email', $email)->delete();
    }

    public function getStudentClass(string $student_id): array
    {
        return Student::find($student_id)->level->toArray();
    }
}
