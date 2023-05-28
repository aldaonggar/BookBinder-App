<?php

namespace App\Tests\Integration\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\Test\Constraint as MailerConstraint;

class EmailVerifierTest extends WebTestCase
{
    use MailerAssertionsTrait;
    public function testEmailVerification()
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

        // Get the email that was sent
        $email_sent = $this->getMailerEvent(0)->getMessage();
        $this->assertInstanceOf(Email::class, $email_sent);
        // Extract the verification URL from the email
        $emailContent = $email_sent->getHtmlBody();

        // Parse the email content to get the verification URL
        $dom = new \DOMDocument();
        @$dom->loadHTML($emailContent);
        $xpath = new \DOMXPath($dom);

        // Get href attribute of the first <a> element
        $verificationUrl = $xpath->evaluate('string(//a/@href)');

        // Follow the verification URL
        $client->request('GET', $verificationUrl);

        // After the user clicks on the verification link, their email address should be verified.
        $this->assertTrue($this->isUserEmailVerified($email, $client));
    }

    private function isUserEmailVerified(string $email, KernelBrowser $client): bool
    {
        // get container
        $container = $client->getContainer();

        // retrieve services
        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        return $user->isVerified();
    }
}
