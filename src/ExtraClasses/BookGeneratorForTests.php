<?php

namespace App\ExtraClasses;

use App\Entity\Book;

class BookGeneratorForTests
{
    public array $bookArray;

    public function __construct() {
        $bookArray = array();
        for($i = 0; $i<20; $i ++){
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