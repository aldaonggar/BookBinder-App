<?php

namespace App\Tests\Controller;
use App\Controller\BookBinderController;
use PHPUnit\Framework\TestCase;

class BookBinderControllerTest extends TestCase
{
    public function testTestFunction(){
        $controller = new BookBinderController();
        $result = $controller -> testFunctionForTests();
        $this->assertGreaterThan(-5, $result);
    }

}