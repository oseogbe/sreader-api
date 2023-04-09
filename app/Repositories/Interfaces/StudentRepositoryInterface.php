<?php

namespace App\Repositories\Interfaces;

interface StudentRepositoryInterface
{
    public function getStudentsData(): array;
    public function getStudentData(string $student_id): array;
    public function createStudent(array $student_data): array;
    public function getStudentByID(string $student_id);
    public function getStudentByEmail(string $email): array;
    public function getStudentByPhoneNumber(string $phone_number): array;
    public function updateStudent(string $student_id, array $data): bool;
    public function createStudentAuthToken(string $student_id): array;
    public function createResetPasswordOTP(string $student_id): string;
    public function getResetPasswordOTP(string $email): array|bool;
    public function deleteResetPasswordOTP(string $email): bool;
    public function getStudentClass(string $student_id): array;
}
