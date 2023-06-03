<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Controller\BookController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function testRoute() {
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

        $book = $this->getBookFromDatabase();
        $crawler = $this->client->request('GET', '/book/' . $book->getId());
        $this->assertResponseIsSuccessful();
    }

    public function testSetFavorite() {
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

        $book = $this->getBookFromDatabase();
        $crawler = $this->client->request('GET', '/book/' . $book->getId());
        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/get-favorite-status/' . $book->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertFalse($data['isFavorite']); // Assert that the book is initially favorited
    }

    public function testToggleFavoriteToTrue() {
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

        $book = $this->getBookFromDatabase();
        $crawler = $this->client->request('GET', '/book/' . $book->getId());
        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/get-favorite-status/' . $book->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertFalse($data['isFavorite']); // Assert that the book is initially favorited

        $this->client->request('POST', '/toggle-favorite/' . $book->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($data['isFavorite']); // Assert that the book is unfavorited

        $this->client->request('GET', '/get-favorite-status/' . $book->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($data['isFavorite']); // Assert that the book is unfavorited
    }

    public function testToggleFavoriteToFalse() {
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

        $book = $this->getBookFromDatabase();
        $crawler = $this->client->request('GET', '/book/' . $book->getId());
        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/get-favorite-status/' . $book->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertFalse($data['isFavorite']); // Assert that the book is initially favorited

        $this->client->request('POST', '/toggle-favorite/' . $book->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($data['isFavorite']); // Assert that the book is unfavorited

        $this->client->request('GET', '/get-favorite-status/' . $book->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($data['isFavorite']); // Assert that the book is unfavorited

        $this->client->request('POST', '/toggle-favorite/' . $book->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertFalse($data['isFavorite']); // Assert that the book is unfavorited

        $this->client->request('GET', '/get-favorite-status/' . $book->getId());
        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertFalse($data['isFavorite']); // Assert that the book is unfavorited
    }

    private function getBookFromDatabase()
    {
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $book = new Book();
        $book->setId(999);
        $book->setTitle('Book Title');
        $book->setAuthor('Author Name');

        $entityManager->persist($book);
        $entityManager->flush();

        return $book;
    }
}
