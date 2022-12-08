<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadBookRequest;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function allBooks()
    {
        return response([
            'success' => true,
            'data' => $this->bookRepository->getBooks()
        ]);
    }

    public function getBooksByClass(Request $request)
    {
        return response([
            'success' => true,
            'data' => $this->bookRepository->getBooksByClass($request->class)]
        );
    }

    public function store(UploadBookRequest $request)
    {
        $validated = $request->validated();

        // cover image
        $cover_path = Storage::put('cover_images', $request->file('cover'));

        // book file
        $file_path = Storage::put('books', $request->file('file'));

        if(!$cover_path || !$file_path)
        {
            return response([
                'success' => false,
                'data' => "Error occurred uploading files!"
            ], 500);
        }

        $request->merge([
            'cover_path' => $cover_path,
            'cover_size' => $request->file('cover')->getSize(),
            'file_path' => $file_path,
            'file_size' => $request->file('file')->getSize(),
        ]);

        $book_data = $request->only('title', 'level_id', 'cover_path', 'cover_size', 'file_path', 'file_size');

        return response([
            'success' => true,
            'data' => $this->bookRepository->addBook($book_data)
        ], 201);
    }

    public function destroy(Request $request)
    {
        $this->bookRepository->deleteBook($request->book_id);

        return response([
            'success' => true,
            'message' => 'Book deleted!'
        ]);
    }
}
