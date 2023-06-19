<?php

namespace App\Repositories\book;

use App\Repositories\book\interfaces\BooksRepositoryInterface;


class BooksService implements BooksRepositoryInterface {

    function __construct(BooksRepositoryInterface $booksRepo) {
        $this->booksRepo = $booksRepo;
      }

   public function getAllBooks(){
        return $this->booksRepo->getAllBooks();
   }

   function saveBook($book)
   {
       return $this->booksRepo->saveBook($book);
   }

   function findBookById($id)
   {
    return $this->booksRepo->findBookById($id);
   }

   function update($newBook, $oldBook)
   {
       return $this->bookRepo->update($newBook,$oldBook);
   }

   function deleteBook($book)
   {
       return $this->booksRepo->deleteBook();
   }
}
