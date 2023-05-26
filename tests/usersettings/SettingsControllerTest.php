<?php

namespace App\Tests\usersettings;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;


class SettingsControllerTest extends WebTestCase
{
    public function testEditUserSettings()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'sophie@haelters.be',
            '_password' => 'Blabla1!',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/usersettings');

        $client->request('POST', '/editUserSettings', [
            'id' => 1,
            'name' => 'John',
            'surname' => 'Doe',
            'age' => '1990-01-01',
            'sex' => 'Male',
            'favoriteLibrary' => 'Library X',
        ]);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('/usersettings', $client->getResponse()->headers->get('Location'));

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->find(1);

        $this->assertSame('John', $user->getFirstname());
        $this->assertSame('Doe', $user->getLastname());
        $this->assertSame('1990-01-01', $user->getBirthday()->format('Y-m-d'));
        $this->assertSame('Male', $user->getSex());
        $this->assertSame('Library X', $user->getFavoriteLibrary());
    }

    public function editUserSettingsTestInvalidName (){
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Submit the form with valid credentials
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'sophie@haelters.be',
            '_password' => 'Blabla1!',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/usersettings');

        // Test case 1: Invalid name with numbers
        $client->request('POST', '/editUserSettings', [
            'id' => 1,
            'name' => 'John123',
            'surname' => 'Doe',
            'age' => '1990-01-01',
            'sex' => 'Male',
            'favoriteLibrary' => 'Library X',
        ]);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('/usersettings', $client->getResponse()->headers->get('Location'));

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->find(1);

        $this->assertNotSame('John123', $user->getFirstname());

        // Test case 1: Invalid name with numbers
        $client->request('POST', '/editUserSettings', [
            'id' => 1,
            'name' => 'John@#§',
            'surname' => 'Doe',
            'age' => '1990-01-01',
            'sex' => 'Male',
            'favoriteLibrary' => 'Library X',
        ]);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('/usersettings', $client->getResponse()->headers->get('Location'));

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->find(1);

        $this->assertNotSame('John@#§', $user->getFirstname());
    }

    public function editUserSettingsTestInvalidSurname (){
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Submit the form with valid credentials
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'sophie@haelters.be',
            '_password' => 'Blabla1!',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/usersettings');

        // Test case 1: Invalid name with numbers
        $client->request('POST', '/editUserSettings', [
            'id' => 1,
            'name' => 'John',
            'surname' => 'Doe678',
            'age' => '1990-01-01',
            'sex' => 'Male',
            'favoriteLibrary' => 'Library X',
        ]);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('/usersettings', $client->getResponse()->headers->get('Location'));

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->find(1);

        $this->assertNotSame('Doe678', $user->getLastname());

        // Test case 1: Invalid name with signs
        $client->request('POST', '/editUserSettings', [
            'id' => 1,
            'name' => 'John',
            'surname' => 'Doe@@@',
            'age' => '1990-01-01',
            'sex' => 'Male',
            'favoriteLibrary' => 'Library X',
        ]);

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('/usersettings', $client->getResponse()->headers->get('Location'));

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->find(1);

        $this->assertNotSame('Doe@@@', $user->getLastname());
    }
}
