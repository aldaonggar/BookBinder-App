<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testBookListPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/booklist/1');

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

        $crawler = $client->request('GET', '/booklist/6');
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

        $crawler = $client->request('GET', '/booklist/8');
        $this->assertSelectorTextContains('h2', 'Book page is not available');

        $crawler = $client->request('GET', '/booklist/0');
        $this->assertSelectorTextContains('h2', 'Book page is not available');
    }

    public function testBookPage():void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $client->submit($form);

        // Assert the user was redirected to the homepage or wherever your app redirects on successful login
        $this->assertResponseRedirects('http://localhost/home');

        //test if it can properly fetch a book with the right ID
        $client->request('GET', '/book/5');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'The Hobbit');
        $this->assertSelectorTextContains('h4', 'J.R.R. Tolkien');

        //test if it can detect non existing book
        $client->request('GET', '/book/0');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', "There's no book of specified ID :(");

        $client->request('GET', '/book/2000');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', "There's no book of specified ID :(");

    }

    public function testSearchPage():void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/booklist/1');

        $form = $crawler->filter('form[name="search_form"]')->form();
        $form['search_form[searchTerm]'] = 'harry';
        $crawler = $client->submit($form);
        $this->assertResponseRedirects('/booklist/search/harry');

        $crawler = $client->request('GET', '/booklist/search/harry');
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
        $crawler = $client->submit($form);
        $this->assertResponseRedirects('/booklist/search/hello');
        $crawler = $client->request('GET', '/booklist/search/hello');
        $this->assertSelectorTextContains('h2', 'No books corresponding to your search :(');
    }
}