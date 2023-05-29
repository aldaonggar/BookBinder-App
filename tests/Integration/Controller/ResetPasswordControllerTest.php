<?php

namespace App\Tests\Integration\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordControllerTest extends WebTestCase
{
    protected $client;
    protected $email;
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->email = 'test' . uniqid() . '@example.com';

        // Request the registration page
        $crawler = $this->client->request('GET', '/register');

        // Fill in the registration form
        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = $this->email;
        $form['registration_form[firstname]'] = 'John';
        $form['registration_form[lastname]'] = 'Doe';
        $form['registration_form[birthday]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;

        // Submit the form
        $this->client->submit($form);
    }

    public function testRequestPage()
    {
        // Go to the request page
        $crawler = $this->client->request('GET', '/reset-password');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Reset your password', $crawler->filter('h2')->text());
    }

    public function testSubmitInvalidEmail()
    {
        $crawler = $this->client->request('GET', '/reset-password');

        $form = $crawler->selectButton('Reset password')->form();
        $form['reset_password_request_form[email]'] = 'invalid-email';

        $crawler = $this->client->submit($form);

        $this->assertStringContainsString('Please enter a valid email address', $crawler->filter('.alert-danger')->text());
    }

    public function testSubmitValidEmail()
    {
        $crawler = $this->client->request('GET', '/reset-password');

        $form = $crawler->selectButton('Reset password')->form();
        $form['reset_password_request_form[email]'] = 'test@example.com';

        $this->client->submit($form);

        // The user should be redirected to the check email page
        $this->assertTrue($this->client->getResponse()->isRedirect('/reset-password/check-email'));

        $crawler = $this->client->followRedirect();

        $title = 'Password Reset Email Sent';
        $this->assertStringContainsString($title, $this->client->getResponse()->getContent());
    }

    public function testResetPasswordWithInvalidToken()
    {
        $this->client->request('GET', '/reset-password/reset/invalid-token');

        $this->assertResponseRedirects('/reset-password');
        $crawler = $this->client->followRedirect();

        $this->assertStringContainsString('The reset password link is invalid', $crawler->filter('.alert-danger')->text());
    }


    public function testResetPasswordWithValidToken()
    {
        // get container
        $container = $this->client->getContainer();

        // retrieve services
        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $this->email]);

        if (!$user) {
            $this->markTestSkipped('User not found.');
        }

        $resetPasswordHelper = $container->get(ResetPasswordHelperInterface::class);
        $resetToken = $resetPasswordHelper->generateResetToken($user);

        $crawler = $this->client->request('GET', '/reset-password/reset/'.$resetToken->getToken());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Reset password')->form();

        $form['change_password_form[plainPassword][first]'] = 'ValidPassword123!';
        $form['change_password_form[plainPassword][second]'] = 'ValidPassword123!';

        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
    }


}