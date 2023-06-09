<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function testBookListPage()
    {


        $crawler = $this->client->request('GET', '/booklist/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('head title', 'Book List');
        $this->assertSelectorTextContains('ul.pagination li ', 'page 1/6');

        $elements = $crawler->filter('h5.card-title');

        $containsHarryPotter = false;
        foreach ($elements as $element) {
            if (strpos($element->textContent, "Harry Potter and the Philosopher's Stone") !== false) {
                $containsHarryPotter = true;
                break;
            }
        }
        // Assert that at least one element matching the selector contains the text "Harry Potter"
        $this->assertTrue($containsHarryPotter, 'No element contains the text "Harry Potter"');

        $crawler = $this->client->request('GET', '/booklist/6');
        $elements = $crawler->filter('h5.card-title');


        $containsTitle = false;
        foreach ($elements as $element) {
            if (str_contains($element->textContent, "Thinking, fast and slow")) {
                $containsTitle = true;
                break;
            }
        }
        // Assert that at least one element matching the selector contains the text "Harry Potter"
        $this->assertTrue($containsTitle, 'No element contains the text "Thinking, fast and slow"');

        $crawler = $this->client->request('GET', '/booklist/8');
        $this->assertSelectorTextContains('h2', 'Book page is not available');

        $crawler = $this->client->request('GET', '/booklist/0');
        $this->assertSelectorTextContains('h2', 'Book page is not available');
    }

    public function testBookPage():void {

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $this->client->submit($form);

        // Assert the user was redirected to the homepage or wherever your app redirects on successful login
        $this->assertResponseRedirects('http://localhost/home');

        //test if it can properly fetch a book with the right ID
        $this->client->request('GET', '/book/5');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'The Hobbit');
        $this->assertSelectorTextContains('h4', 'J.R.R. Tolkien');

        //test if it can detect non existing book
        $this->client->request('GET', '/book/0');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', "There's no book of specified ID :(");

        $this->client->request('GET', '/book/2000');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', "There's no book of specified ID :(");

    }

    public function testSearchPage():void{

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $this->client->submit($form);

        $crawler = $this->client->request('GET', '/booklist/1');

        $form = $crawler->filter('form[name="search_form"]')->form();
        $form['search_form[searchTerm]'] = 'harry';
        $crawler = $this->client->submit($form);
        $this->assertResponseRedirects('/booklist/search/harry');

        $crawler = $this->client->request('GET', '/booklist/search/harry');
        $elements = $crawler->filter('h5.card-title');
        $containsTitle = false;
        foreach ($elements as $element) {
            if (str_contains($element->textContent, "Harry Potter and the Philosopher's Stone")) {
                $containsTitle = true;
                break;
            }
        }
        // Assert that at least one element matching the selector contains the text "Harry Potter"
        $this->assertTrue($containsTitle, 'No element contains the text "Harry Potter and the Philosopher\'s Stone"');

        $form = $crawler->filter('form[name="search_form"]')->form();
        $form['search_form[searchTerm]'] = 'hello';
        $crawler = $this->client->submit($form);
        $this->assertResponseRedirects('/booklist/search/hello');
        $crawler = $this->client->request('GET', '/booklist/search/hello');
        $this->assertSelectorTextContains('h2', 'No books corresponding to your search :(');
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

        $book = $this->getBookFromDatabaseBetter();
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

        $book = $this->getBookFromDatabaseBetter();
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

        $book = $this->getBookFromDatabaseBetter();
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

        $book = $this->getBookFromDatabaseBetter();
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

    private function getBookFromDatabaseBetter(){
        // Generate a random book ID between 1 and 110
        $randomId = rand(1, 110);
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        // Fetch the book with the random ID from the database
        $book = $entityManager->getRepository(Book::class)->find($randomId);

        if (!$book) {
            throw new \Exception('Book not found');
        }
        return $book;
    }
}