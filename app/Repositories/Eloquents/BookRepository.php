<?php

namespace App\Repositories\Eloquents;

use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BookRepository implements BookRepositoryInterface
{
    function getBooks(): array
    {
        return Book::all()->toArray();
    }

    function getBookByID(string $book_id): string
    {
        return Book::find($book_id)->file_path;
    }

    function getBookCoverByID(string $book_id): string
    {
        return Book::find($book_id)->cover_path;
    }

    function getBooksByClass(string $class): array
    {
        return Book::whereHas('level', function($q) use ($class) {
            $q->where('name', $class);
        })->get()->toArray();
    }

    function addBook(array $book_data): array
    {
        return Book::create($book_data)->toArray();
    }

    function getBookByTitle(string $title): array
    {
        return Book::where('title', $title)->get()->toArray();
    }

    public function deleteBook(string $book_id): bool
    {
        $book = Book::find($book_id);

        if($book->cover_path)
        {
            Storage::disk('s3')->delete($book->cover_path);
        }

        Storage::disk('s3')->delete($book->file_path);

        return $book->delete();
    }
}
