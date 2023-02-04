<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    private StudentRepositoryInterface $studentRepository;
    private BookRepositoryInterface $bookRepository;

    public function __construct(StudentRepositoryInterface $studentRepository, BookRepositoryInterface $bookRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->bookRepository = $bookRepository;
    }

    public function getBooksByStudentClass()
    {
        $class = $this->studentRepository->getStudentClass(Auth::id());

        return response([
            'success' => true,
            'data' => $this->bookRepository->getBooksByClass($class['id'])
        ]);
    }
}
