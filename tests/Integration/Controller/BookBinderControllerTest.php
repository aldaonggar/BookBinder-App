<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookBinderControllerTest extends WebTestCase
{
    public function testBookListPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/booklist/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('head title', 'Book List');
        $this->assertSelectorTextContains('div.mt-4 ul li.test ', 'page 1/6');

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
        $this->assertSelectorTextContains('div.mt-4 ul li.test ', 'page 6/6');

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

    public function testBookPage():void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/booklist/1');
        $this->assertResponseIsSuccessful();


    }
}