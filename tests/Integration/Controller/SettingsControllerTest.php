<?php

namespace App\Tests\Integration\Controller;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;


class SettingsControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testEditUserSettings()
    {
        $email = 'test' . uniqid() . '@example.com';

        // Request the registration page
        $crawler = $this->client->request('GET', '/register');

        // Fill in the registration form
        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = $email;
        $form['registration_form[firstname]'] = 'John';
        $form['registration_form[lastname]'] = 'Doe';
        $form['registration_form[birthday]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;
        $this->client->submit($form);

        $crawler = $this->client->request('GET', '/login');

        // Submit the form with valid credentials
        $form = $crawler->selectButton('Login')->form([
            '_username' => $email,
            '_password' => 'Password123!',
        ]);
        $this->client->submit($form);

        $crawler = $this->client->request('GET', '/usersettings');

        $this->client->request('POST', '/editUserSettings', [
            'id' => 1,
            'name' => 'John',
            'surname' => 'Doe',
            'age' => '1990-01-01',
            'sex' => 'Male',
        ]);

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('/usersettings', $this->client->getResponse()->headers->get('Location'));

        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository(User::class)->find(1);

        $this->assertSame('John', $user->getFirstname());
        $this->assertSame('Doe', $user->getLastname());
        $this->assertSame('1990-01-01', $user->getBirthday()->format('Y-m-d'));
        $this->assertSame('Male', $user->getSex());
    }
}
