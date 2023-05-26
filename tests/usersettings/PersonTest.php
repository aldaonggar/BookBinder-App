<?php

namespace App\Tests\usersettings;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PersonTest extends WebTestCase
{
    public function testRouteMyProfile(): void {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Submit the form with valid credentials
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'sophie@haelters.be',
            '_password' => 'Blabla1!',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/myprofile');
        $this->assertResponseIsSuccessful();
    }

    public function testRoutePerson(): void {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Submit the form with valid credentials
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'sophie@haelters.be',
            '_password' => 'Blabla1!',
        ]);
        $client->submit($form);

        $crawler = $client->request('GET', '/person');
        $this->assertResponseIsSuccessful();
    }
}
