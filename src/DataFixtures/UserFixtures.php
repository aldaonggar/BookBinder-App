<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $user1 = new User();
        $user1->setFirstname('Hanne');
        $user1->setLastname('Vervecken');
        $user1->setEmail('hanne.vervecken@student.kuleuven.be');
        $user1->setBirthday('2001-06-21');
        $user1->setPassword('helloWorld');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setFirstname('Felix');
        $user2->setLastname('Janssens');
        $user2->setEmail('felix.janssens@gmail.com');
        //$user2->setBirthday(2000-07-15);
        $user2->setBirthday('2000-09-15');
        $user2->setPassword('bookBinder123');
        $manager->persist($user2);

        $user3 = new User();
        $user3->setFirstname('Sara');
        $user3->setLastname('Peeters');
        $user3->setEmail('sara.peeters@hotmail.be');
        $user3->setBirthday('2003-10-06');
        $user3->setPassword('1234');
        $manager->persist($user3);

        $manager->flush();
    }
}
