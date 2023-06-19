<?php

namespace App\Repositories\book\interfaces;

interface BooksRepositoryInterface
{
    public function getAllBooks();
    public function saveBook($book);
    public function findBookById($id);
    public function update($newBook,$oldBook);
    public function deleteBook($book);
}
