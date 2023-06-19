<?php

namespace App\Repositories\book;


use App\Models\Book;
use App\Repositories\book\interfaces\BooksRepositoryInterface;

class BooksRepository implements BooksRepositoryInterface {
   function getAllBooks(){
    $books = Book::all();
        return $books;
   }

   function saveBook($book)
   {
    return Book::create($book);
   }


   function findBookById($id)
   {
    $book = Book::find($id);
    return $book;
   }
   
   function update($newBook, $oldBook)
   {
    $oldBook->pages = $newBook['pages'];
    $oldBook->name = $newBook['name'];
    return $oldBook->save();
   }

   function deleteBook($book)
   {
    return $book->delete();
   }
}
