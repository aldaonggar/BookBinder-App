<?php

namespace App\Tests\Unit\Controller;

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

    public function testNewUserRegistration(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'testuser@test.com';
        $form['registration_form[firstname]'] = 'Test';
        $form['registration_form[lastname]'] = 'User';
        $form['registration_form[birthday]'] = '1990-01-01';
        $form['registration_form[plainPassword][first]'] = 'Test123!';
        $form['registration_form[plainPassword][second]'] = 'Test123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        // The form submission should trigger a redirection to the email verification page
        $this->assertResponseRedirects('/email-verification');
    }
}
