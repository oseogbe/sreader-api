<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentResourceCollection;
use App\Http\Resources\TestResultResource;
use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StudentRepository implements StudentRepositoryInterface
{
    function getStudentsData(): array
    {
        $students = Student::custom()->orderBy('firstname');

        $students_no = (clone $students)->count();

        $students_new = (clone $students)->where('created_at', '>=', now()->subWeek());
        $students_new_no = $students_new->count();

        $students_active = (clone $students)->where('status', 'active');
        $students_active_no = $students_active->count();

        $students_inactive = (clone $students)->where('status', 'inactive');
        $students_inactive_no = $students_inactive->count();

        if($period = request('period'))
        {
            $students_no_growth = growthBetweenTimePeriods((clone $students), $period);
            $students_new_no_growth = growthBetweenTimePeriods($students_new, $period);
            $students_active_no_growth = growthBetweenTimePeriods($students_active, $period);
            $students_inactive_no_growth = growthBetweenTimePeriods($students_inactive, $period);

            return array_merge([
                'all' => [
                    'count' => $students_no,
                    'growth' => $students_no_growth,
                ],
                'new' => [
                    'count' => $students_new_no,
                    'growth' => $students_new_no_growth,
                ],
                'active' => [
                    'count' => $students_active_no,
                    'growth' => $students_active_no_growth,
                ],
                'inactive' => [
                    'count' => $students_inactive_no,
                    'growth' => $students_inactive_no_growth,
                ]
            ], (new StudentResourceCollection($students->paginate(10)))->jsonSerialize());
        }

        return array_merge([
            'all' => $students_no,
            'new' => $students_new_no,
            'active' => $students_active_no,
            'inactive' =>  $students_inactive_no,
        ], (new StudentResourceCollection($students->paginate(10)))->jsonSerialize());
    }

    function getStudentData(string $student_id): array
    {
        $student = Student::find($student_id);

        return array_merge((new StudentResource($student))->jsonSerialize(), [
            "tests" => TestResultResource::collection($student->latestTestResults)
        ]);
    }

    function createStudent(array $student_data): array
    {
        return Student::create($student_data)->toArray();
    }

    function getStudentByID(string $student_id)
    {
        return StudentResource::make(Student::findOrFail($student_id));
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
            'created_at' => now()
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
