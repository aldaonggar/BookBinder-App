<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PeopleListTest extends WebTestCase
{
    public function testPeopleListPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/peoplelist/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('head title', 'People List');

        $cardElements = $crawler->filter('div.card');

        $this->assertGreaterThanOrEqual(2, $cardElements->count());

    }

    public function testSearchPage():void{
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/peoplelist/1');

        $form = $crawler->filter('form[name="search_form"]')->form();
        $form['search_form[searchTerm]'] = 'devon';
        $crawler = $client->submit($form);
        $this->assertResponseRedirects('/peoplelist/search/devon');

        $crawler = $client->request('GET', '/peoplelist/search/devon');
        $elements = $crawler->filter('h5.card-title');
        $containsTitle = false;
        foreach ($elements as $element) {
            if (str_contains($element->textContent, "Devon")) {
                $containsTitle = true;
                break;
            }
        }
        // Assert that at least one element matching the selector contains the text "Harry Potter"
        $this->assertTrue($containsTitle, 'No element contains the text "Devon"');

        $form = $crawler->filter('form[name="search_form"]')->form();
        $form['search_form[searchTerm]'] = 'hello';
        $crawler = $client->submit($form);
        $this->assertResponseRedirects('/peoplelist/search/hello');
        $crawler = $client->request('GET', '/peoplelist/search/hello');
        $this->assertSelectorTextContains('h2', 'No users corresponding to your search :(');
    }

    public function testPersonPage():void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $client->submit($form);

        $client->request('GET', '/person/2');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Person Profile');

    }
}