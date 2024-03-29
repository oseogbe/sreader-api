<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadBookRequest;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\TestRepositoryInterface;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;

class BookController extends Controller
{
    private BookRepositoryInterface $bookRepository;
    private TestRepositoryInterface $testRepository;

    public function __construct(BookRepositoryInterface $bookRepository, TestRepositoryInterface $testRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->testRepository = $testRepository;
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
        $request->validate([
            'level_id' => 'required|integer'
        ]);

        return response([
            'success' => true,
            'data' => $this->bookRepository->getBooksByClass($request->level_id)]
        );
    }

    public function store(UploadBookRequest $request)
    {
        $validated = $request->validated();

        $localfolder = public_path('firebase-temp-uploads') .'/';

        $cover_file = $request->file('cover');
        $cover_image = $cover_file->hashName();
        $cover_image_size = $request->file('cover')->getSize();

        if ($cover_file->move($localfolder, $cover_image)) {
          $uploadedfile = fopen($localfolder.$cover_image, 'r');
          $cover_image_uploaded = app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => 'cover_images/' . $cover_image]);
        }

        $book_file = $request->file('file');
        $book = $book_file->hashName();
        $book_size = $request->file('file')->getSize();

        if ($book_file->move($localfolder, $book)) {
          $uploadedfile = fopen($localfolder.$book, 'r');
          $book_uploaded = app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => 'books/' . $book]);
        }

        if(!$cover_image_uploaded || !$book_uploaded)
        {
            return response([
                'success' => false,
                'data' => "Error occurred uploading files!"
            ], 500);
        }

        unlink($localfolder . $cover_image);
        unlink($localfolder . $book);

        $request->merge([
            'cover_path' => 'cover_images/'.$cover_image,
            'cover_size' => $cover_image_size ,
            'file_path' => 'books/'.$book,
            'file_size' => $book_size,
        ]);

        $book_data = $request->only('description', 'level_id', 'subject_id', 'cover_path', 'cover_size', 'file_path', 'file_size', 'is_compulsory', 'department');

        return response([
            'success' => true,
            'message' => "$book_data[title] book added",
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

    public function getBookTests($book_id)
    {
        $validator = Validator::make(
            ['book_id' => $book_id],
            ['book_id' => 'required|exists:books,id']
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        return response([
            'success' => true,
            'data' => $this->testRepository->getTestsForBook($book_id)
        ]);
    }
}
