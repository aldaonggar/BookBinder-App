<?php

namespace App\DataFixtures;

use App\Entity\Label;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LabelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $label1 = new Label();
        $label1->setLabelName('Young adult');
        $label1->setId(1);
        $manager->persist($label1);

        $label2 = new Label();
        $label2->setLabelName('Romance');
        $label2->setId(2);
        $manager->persist($label2);

        $label3 = new Label();
        $label3->setLabelName('Science Fiction');
        $label3->setId(3);
        $manager->persist($label3);

        $label4 = new Label();
        $label4->setLabelName('Mystery');
        $label4->setId(4);
        $manager->persist($label4);

        $label5 = new Label();
        $label5->setLabelName('Thriller');
        $label5->setId(5);
        $manager->persist($label5);

        $label6 = new Label();
        $label6->setLabelName('Fantasy');
        $label6->setId(6);
        $manager->persist($label6);

        $label7 = new Label();
        $label7->setLabelName('Horror');
        $label7->setId(7);
        $manager->persist($label7);

        $label8 = new Label();
        $label8->setLabelName('Non-fiction');
        $label8->setId(8);
        $manager->persist($label8);

        $label9 = new Label();
        $label9->setLabelName('Comedy');
        $label9->setId(9);
        $manager->persist($label9);

        $label10 = new Label();
        $label10->setLabelName('Biography');
        $label10->setId(10);
        $manager->persist($label10);

        $manager->flush();
    }
}