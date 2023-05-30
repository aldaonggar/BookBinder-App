<?php

namespace App\Tests\Unit\BookList;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookRepositoryTest extends KernelTestCase
{

    public function testGet21Books(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $repo = $entityManager->getRepository(Book::class);
        $books = $repo->get21Books(1);

        $this->assertCount(21, $books);

        $ids = [];
        foreach ($books as $book) {
            $this->assertNotContains($book->getId(), $ids);
            $ids[] = $book->getId();
        }

        // Assert that a book with title "The Lord of The Rings" is in the array
        $hasLordOfTheRings = false;
        // Assert that a book with title "The Hobbit" is in the array
        $hasTheHobbit = false;
        foreach ($books as $book) {
            if ($book->getTitle() === 'The Lord Of The Rings') {
                $hasLordOfTheRings = true;
            }
            if ($book->getTitle() === 'The Hobbit') {
                $hasTheHobbit = true;
            }
        }

        $this->assertTrue($hasLordOfTheRings);
        $this->assertTrue($hasTheHobbit);

        $booksLastPage = $repo->get21Books(6);
        $this->assertLessThan(21, count($booksLastPage));

        // Assert that each book object in $booksLastPage is different from $books
        foreach ($booksLastPage as $bookLastPage) {
            foreach ($books as $book) {
                $this->assertNotEquals($bookLastPage->getId(), $book->getId());
            }
        }
    }

    public function testSearchBooks():void{
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $repo = $entityManager->getRepository(Book::class);

        $books = $repo->searchBooksByAuthorAndTitle('hello');
        $this->assertCount(0, $books);

        $books = $repo->searchBooksByAuthorAndTitle('Harry');
        $containsHarryPotter = false;
        foreach ($books as $book) {
            if ($book->getTitle() === "Harry Potter and the Philosopher's Stone") {
                $containsHarryPotter = true;
                break;
            }
        }
        $this->assertTrue($containsHarryPotter, 'One of the books should contain the title "Harry Potter"');

        $books = $repo->searchBooksByAuthorAndTitle('Pratchett');
        $containsPratchett = false;
        foreach ($books as $book) {
            if ($book->getAuthor() === "Terry Pratchett") {
                $containsPratchett = true;
                break;
            }
        }
        $this->assertTrue($containsPratchett, 'One of the books should contain author Terry Pratchett');
    }
}