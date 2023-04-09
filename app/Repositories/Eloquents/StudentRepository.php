<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentResourceCollection;
use App\Http\Resources\TestResultResource;
use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentRepository implements StudentRepositoryInterface
{
    function getStudentsData(): array
    {
        $students = Student::orderBy('firstname');

        if($filter = request()->filter ?? ['unit' => 'month', 'value' => 6]) {
            $joined_at = $filter['unit'] == 'week' ? Carbon::now()->subWeeks($filter['value']) : Carbon::now()->subMonths($filter['value']);
            $students = $students->where('created_at', '>=', $joined_at);
        }

        $students_clone = clone $students;
        $students_no = $students_clone->count();
        $students_no_growth = getModelPercentageIncrease($students_clone, ['unit' => $filter['unit'], 'value' => $filter['value']]);

        $students_clone = clone $students;
        $students_new = $students_clone->where('created_at', '>=', Carbon::now()->subWeek());
        $students_new_no = $students_new->count();
        $students_new_no_growth = getModelPercentageIncrease($students_new, ['unit' => $filter['unit'], 'value' => $filter['value']]);

        $students_clone = clone $students;
        $students_active = $students_clone->where('status', 'active');
        $students_active_no = $students_active->count();
        $students_active_no_growth = getModelPercentageIncrease($students_active, ['unit' => $filter['unit'], 'value' => $filter['value']]);

        $students_clone = clone $students;
        $students_inactive = $students_clone->where('status', 'inactive');
        $students_inactive_no = $students_inactive->count();
        $students_inactive_no_growth = getModelPercentageIncrease($students_inactive, ['unit' => $filter['unit'], 'value' => $filter['value']]);

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

    function getStudentData(string $student_id): array
    {
        $student = Student::find($student_id);

        return array_merge([
            TestResultResource::collection($student->latestTestResults)
        ], (new StudentResource($student))->jsonSerialize());
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
