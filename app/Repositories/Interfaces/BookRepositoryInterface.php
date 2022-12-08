<?php

namespace App\Repositories\Interfaces;

interface BookRepositoryInterface
{
    public function getBooks(): array;
    public function getBookByID(string $book_id): string;
    public function getBookCoverByID(string $book_id): string;
    public function getBooksByClass(string $class): array;
    public function addBook(array $book_data): array;
    public function getBookByTitle(string $title): array;
    public function deleteBook(string $book_id): bool;
}
