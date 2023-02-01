<?php

namespace App\Repositories\Eloquents;

use App\Models\Book;
use App\Models\LevelSubject;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BookRepository implements BookRepositoryInterface
{
    function getBooks(): array
    {
        return Book::all()->toArray();
    }

    function getBookByID(string $book_id): array
    {
        return Book::find($book_id)->toArray();
    }

    function getBookFileByID(string $book_id): string
    {
        return Book::find($book_id)->file_path;
    }

    function getBookCoverByID(string $book_id): string
    {
        return Book::find($book_id)->cover_path;
    }

    function getBooksByClass(int $level_id): array
    {
        return Book::where('level_id', $level_id)->get()->toArray();
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
