<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Label;
use App\Entity\Rating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $book1 = new Book();
        $book1->setTitle('How it feels to float');
        $book1->setAuthor('Helena Fox');
        $book1->setGenre('Fiction');
        $book1->setId(1);
        $book1->setIsbn(9781471403989);

        $rating1 = new Rating();
        $rating1->setBook($book1);
        $rating1->setScore(10);
        $rating1->setId(1);
        $manager->persist($rating1);

        $rating2 = new Rating();
        $rating2->setBook($book1);
        $rating2->setScore(9);
        $rating2->setId(2);
        $manager->persist($rating2);

        $book1->addRating($rating1);
        $book1->addRating($rating2);

        $manager->persist($book1);


        $book2 = new Book();
        $book2->setTitle('We were liars');
        $book2->setAuthor('E. Lockhart');
        $book2->setGenre('Fiction');
        $book2->setId(2);
        $book2->setIsbn(9781471403989);

        $rating3 = new Rating();
        $rating3->setBook($book2);
        $rating3->setScore(8);
        $rating3->setId(3);
        $manager->persist($rating3);

        $rating4 = new Rating();
        $rating4->setBook($book2);
        $rating4->setScore(7);
        $rating4->setId(4);
        $manager->persist($rating4);

        $book2->addRating($rating3);
        $book2->addRating($rating4);

        $manager->persist($book2);

        $manager->flush();
    }
}
