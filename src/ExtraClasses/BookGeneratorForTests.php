<?php

namespace App\ExtraClasses;

use App\Entity\Book;

class BookGeneratorForTests
{
    public array $bookArray;

    public function __construct($pageNumber) {
        $bookArray = array();
        for($i = ($pageNumber-1)*20; $i<(20+($pageNumber-1)*20); $i ++){
            $book = new Book();
            $title = 'title'.$i;
            $book->setTitle($title);
            $author = 'author'.$i;
            $book->setAuthor($author);
            $book->setId($i);
            $bookArray[] = $book;
        }
        $this->bookArray = $bookArray;
    }

    public function createStringResponse(): string{
        $retString = "";
        foreach ($this->bookArray as $book){
            $retString .= $book->getAuthor().' '.$book->getTitle();
        }
        return $retString;
    }

    /**
     * @return array
     */
    public function getBookArray(): array
    {
        return $this->bookArray;
    }
}