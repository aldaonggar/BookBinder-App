<?php

namespace App\Tests\Unit\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testShowRegistrationForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Register');
    }

    public function testRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'testhello5353@gmail.com';
        $form['registration_form[firstname]'] = 'John';
        $form['registration_form[lastname]'] = 'Doe';
        $form['registration_form[birthday]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $expectedUrl = '/email-verification'; // replace this with the expected URL
        $this->assertEquals($expectedUrl, $client->getResponse()->getTargetUrl());
        // You can add more assertions here to check that the data has been inserted correctly
    }

    public function testInvalidEmailRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'not_an_email';
        $form['registration_form[firstname]'] = 'John';
        $form['registration_form[lastname]'] = 'Doe';
        $form['registration_form[birthday]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        //$this->assertFalse($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());


    }

    public function testPasswordNotMatchRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'hello@gmail.com';
        $form['registration_form[firstname]'] = 'John';
        $form['registration_form[lastname]'] = 'Doe';
        $form['registration_form[birthday]'] = '2000-01-01';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'password';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        //$this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());
        // You can add more assertions here to check that the data has been inserted correctly

    }

    public function testInvalidBirthdayRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'invalid2@gmail.com';
        $form['registration_form[firstname]'] = 'John';
        $form['registration_form[lastname]'] = 'Doe';
        $form['registration_form[birthday]'] = '2053-11-11';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        //$this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());
        // You can add more assertions here to check that the data has been inserted correctly

    }

    public function testAgreeTermsRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'agree@gmail.com';
        $form['registration_form[firstname]'] = 'John';
        $form['registration_form[lastname]'] = 'Doe';
        $form['registration_form[birthday]'] = '2001-12-12';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';

        $client->submit($form);

        //$this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());
        // You can add more assertions here to check that the data has been inserted correctly

    }

    public function testExistingEmailRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'testhello5353@gmail.com';
        $form['registration_form[firstname]'] = 'Michael';
        $form['registration_form[lastname]'] = 'Scofield';
        $form['registration_form[birthday]'] = '1998-05-05';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        //$this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

    }

    public function testPasswordLengthRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'passwordemaiil@gmail.com';
        $form['registration_form[firstname]'] = 'Michael';
        $form['registration_form[lastname]'] = 'Scofield';
        $form['registration_form[birthday]'] = '1998-05-05';
        $form['registration_form[plainPassword][first]'] = 'Pa123!';
        $form['registration_form[plainPassword][second]'] = 'Pa123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        //$this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());
    }

        public function testPasswordSpecialCharRegister(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'passwordemaiil@gmail.com';
        $form['registration_form[firstname]'] = 'Michael';
        $form['registration_form[lastname]'] = 'Scofield';
        $form['registration_form[birthday]'] = '1998-05-05';
        $form['registration_form[plainPassword][first]'] = 'Password123';
        $form['registration_form[plainPassword][second]'] = 'Password123';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        //$this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());
    }
        public function testPasswordUpperCaseRegister(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'passwordemaiil@gmail.com';
        $form['registration_form[firstname]'] = 'Michael';
        $form['registration_form[lastname]'] = 'Scofield';
        $form['registration_form[birthday]'] = '1998-05-05';
        $form['registration_form[plainPassword][first]'] = 'password123!';
        $form['registration_form[plainPassword][second]'] = 'password123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        //$this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());
    }
        public function testPasswordLowerCaseRegister(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'passwordemaiil@gmail.com';
        $form['registration_form[firstname]'] = 'Michael';
        $form['registration_form[lastname]'] = 'Scofield';
        $form['registration_form[birthday]'] = '1998-05-05';
        $form['registration_form[plainPassword][first]'] = 'PASSWORD123!';
        $form['registration_form[plainPassword][second]'] = 'PASSWORD123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        //$this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());
    }

    public function testPasswordDigitRegister(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'passwordemaiil@gmail.com';
        $form['registration_form[firstname]'] = 'Michael';
        $form['registration_form[lastname]'] = 'Scofield';
        $form['registration_form[birthday]'] = '1998-05-05';
        $form['registration_form[plainPassword][first]'] = 'Password!';
        $form['registration_form[plainPassword][second]'] = 'Password!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        //$this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());
    }

    public function testCorrectPasswordRegister(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[email]'] = 'correctpassword53@gmail.com';
        $form['registration_form[firstname]'] = 'Michael';
        $form['registration_form[lastname]'] = 'Scofield';
        $form['registration_form[birthday]'] = '1998-05-05';
        $form['registration_form[plainPassword][first]'] = 'Password123!';
        $form['registration_form[plainPassword][second]'] = 'Password123!';
        $form['registration_form[agreeTerms]'] = 1;

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $expectedUrl = '/email-verification'; // replace this with the expected URL
        $this->assertEquals($expectedUrl, $client->getResponse()->getTargetUrl());
    }
}
