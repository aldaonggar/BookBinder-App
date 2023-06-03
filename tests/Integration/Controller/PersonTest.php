<?php

namespace App\Tests\Integration\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PersonTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRouteMyProfile(): void {
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

        $crawler = $this->client->request('GET', '/myprofile');
        $this->assertResponseIsSuccessful();
    }

    public function testRoutePerson(): void {
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

        $crawler = $this->client->request('GET', '/person');
        $this->assertResponseIsSuccessful();
    }

    public function testRouteUserSettings(): void {
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
        $this->assertResponseIsSuccessful();
    }
}
