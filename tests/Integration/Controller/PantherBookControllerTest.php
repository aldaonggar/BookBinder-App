<?php

namespace App\Tests\Integration\Controller;

use Symfony\Component\Panther\PantherTestCase;

class PantherBookControllerTest extends PantherTestCase
{

    public function testBookImages(){
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form([
            '_username' => 'yeska.omar@gmail.com',
            '_password' => 'Hello123.',
        ]);
        $client->submit($form);
        $crawler = $client->request('GET', '/booklist/1');
        //$this->assertResponseIsSuccessful();

        $client->waitFor('img');

        // Assert that the image has valid dimensions
        $this->assertTrue(
            $crawler->filter('img')->first()->attr('width') !== '0'
            && $crawler->filter('img')->first()->attr('height') !== '0'
        );
    }


}