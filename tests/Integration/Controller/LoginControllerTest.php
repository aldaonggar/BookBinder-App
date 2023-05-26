<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testShowLoginPage()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Login');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'wrong_username@mail.com',
            '_password' => 'wrong_password',
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();

        // Assert the login error message is displayed
        $this->assertSelectorTextContains('.error-message', 'Incorrect password or email.');
    }

    public function testLoginWithBadCredentials2()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Submit the form with some bad credentials
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'wrong_username',
            '_password' => 'wrong_password',
        ]);
        $client->submit($form);

        // Assert the user is redirected to the login page
        $this->assertResponseRedirects('http://localhost/login');

        // Follow the redirect and check the title
        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('h2', 'Login');

        // Assert the user is not authenticated
        $this->assertFalse($client->getContainer()->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'));
    }



    public function testLoginWithGoodCredentials()
    {
        $client = static::createClient();
        $email = 'test' . uniqid() . '@example.com';

        // Request the registration page
        $crawler = $client->request('GET', '/register');

        // Fill in the registration form
        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = $email;
        $form['registration_form[firstname]'] = 'John';
        $form['registration_form[lastname]'] = 'Doe';
        $form['registration_form[birthday]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;
        $client->submit($form);

        $crawler = $client->request('GET', '/login');

        // Submit the form with valid credentials
        $form = $crawler->selectButton('Login')->form([
            '_username' => $email,
            '_password' => 'Password123!',
        ]);
        $client->submit($form);

        // Assert the user was redirected to the homepage or wherever your app redirects on successful login
        $this->assertResponseRedirects('http://localhost/home');
    }
}
