<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LibraryControllerTest extends WebTestCase
{
    public function testBookListPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/librarylist/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('head title', 'Libraries');

        $cardElements = $crawler->filter('div.card');

        $this->assertGreaterThanOrEqual(2, $cardElements->count());

        $elements = $crawler->filter('h5.card-title');

        $containsCentralLibrary = false;
        foreach ($elements as $element) {
            if (strpos($element->textContent, "Central Library") !== false) {
                $containsCentralLibrary = true;
                break;
            }
        }
        // Assert that at least one element matching the selector contains the text "Harry Potter"
        $this->assertTrue($containsCentralLibrary, 'No element contains Central Library');

    }

    public function testSearchPage():void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/librarylist/1');

        $form = $crawler->filter('form[name="search_form"]')->form();
        $form['search_form[searchTerm]'] = 'central';
        $crawler = $client->submit($form);
        $this->assertResponseRedirects('/librarylist/search/central');

        $crawler = $client->request('GET', '/librarylist/search/central');
        $elements = $crawler->filter('h5.card-title');
        $containsTitle = false;
        foreach ($elements as $element) {
            if (str_contains($element->textContent, "Central Library")) {
                $containsTitle = true;
                break;
            }
        }
        // Assert that at least one element matching the selector contains the text "Harry Potter"
        $this->assertTrue($containsTitle, 'No element contains the text "Central Library"');

        $form = $crawler->filter('form[name="search_form"]')->form();
        $form['search_form[searchTerm]'] = 'hello';
        $crawler = $client->submit($form);
        $this->assertResponseRedirects('/librarylist/search/hello');
        $crawler = $client->request('GET', '/librarylist/search/hello');
        $this->assertSelectorTextContains('h2', 'No libraries corresponding to your search :(');
    }
}