<?php

namespace App\Repositories\Eloquents;

use App\Models\Book;
use App\Models\ReadingProgress;
use App\Models\Student;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Support\Facades\DB;
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
        if (auth()->user() instanceof Student) {
            $books = Book::where('level_id', $level_id)->get()->each(function($book) {
                $book->reading_progress = $book->readingProgress(auth()->id())->select('term', 'topic_no', 'sub_topic_no')->get();
            });

            return $books->toArray();
        }

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

    public function setReadingProgress(string $book_id, array $read_data): bool
    {
        $read_data = [
            'term' => $read_data['term'],
            'topic_no' => $read_data['topic_no'],
            'sub_topic_no' => $read_data['sub_topic_no']
        ];

        $reading_progress = ReadingProgress::firstOrCreate(
                                ['student_id' => auth()->id(), 'book_id' => $book_id], $read_data);

        if ($reading_progress->fill($read_data)->isDirty()) {
            return $reading_progress->save();
        }

        return false;
    }
}
