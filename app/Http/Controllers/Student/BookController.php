<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReadingProgressRequest;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\TestRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    private StudentRepositoryInterface $studentRepository;
    private BookRepositoryInterface $bookRepository;
    private TestRepositoryInterface $testRepository;

    public function __construct(StudentRepositoryInterface $studentRepository,
                                BookRepositoryInterface $bookRepository,
                                TestRepositoryInterface $testRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->bookRepository = $bookRepository;
        $this->testRepository = $testRepository;
    }

    public function getBooksByStudentClass()
    {
        $class = $this->studentRepository->getStudentClass(Auth::id());

        return response([
            'success' => true,
            'data' => $this->bookRepository->getBooksByClass($class['id'])
        ]);
    }

    public function getBookTests($book_id)
    {
        return response([
            'success' => true,
            'data' => $this->testRepository->getTestsForBook($book_id)
        ]);
    }

    public function setReadingProgress(ReadingProgressRequest $request, $book_id)
    {
        $read_data = $request->validated();

        $this->bookRepository->setReadingProgress($book_id, $read_data);

        return response([
            'success' => true,
            'message' => "Reading progress updated"
        ]);
    }
}
