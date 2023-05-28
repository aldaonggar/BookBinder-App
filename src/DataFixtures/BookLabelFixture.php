<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Label;
use App\Entity\Rating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookLabelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $book1 = $manager->getRepository(Book::class)->findOneBy(['title' => 'How it feels to float']);
        $label1 = $manager->getRepository(Label::class)->findOneBy(['labelName'=>'Young adult']);
        $book1->addLabel($label1);
        $manager->persist($book1);

        $book2 = $manager->getRepository(Book::class)->findOneBy(['title' => 'We were liars']);
        $label2 = $manager->getRepository(Label::class)->findOneBy(['labelName'=>'Mystery']);
        $book2->addLabel($label2);
        $book2->addLabel($label1);
        $manager->persist($book2);

        $manager->flush();
    }
}
