<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegisterPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');
    }

    public function testEmailVerificationPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/email-verification');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Email Verification');
    }

    public function testRegistrationWithValidData(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[firstname]'] = 'Test';
        $form['registration_form[lastname]'] = 'User';
        $form['registration_form[birthday]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        $this->assertResponseRedirects('/email-verification');
    }

    public function testRegistrationWithInvalidEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'not_an_email';
        $form['registration_form[firstname]'] = 'Test';
        $form['registration_form[lastname]'] = 'User';
        $form['registration_form[birthday]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        $this->assertSelectorTextContains('.form-error-message', 'Please enter a valid email address.');
    }
}
