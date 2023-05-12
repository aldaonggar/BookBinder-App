<?php

namespace App\Tests\Form;

use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistrationFormTypeTest extends TypeTestCase
{
    private $passwordHasher;

    protected function setUp(): void
    {
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        // Assuming the password "Test123!" hashes to "hashedTest123!"
        $this->passwordHasher->method('hashPassword')->willReturn('hashedTest123!');

        parent::setUp();
    }

    protected function getExtensions()
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@test.com',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'birthday' => '1980-01-01',
            'plainPassword' => [
                'first' => 'Test123!',
                'second' => 'Test123!',
            ],
            'agreeTerms' => true,
        ];

        $objectToCompare = new User();
        $form = $this->factory->create(RegistrationFormType::class, $objectToCompare);

        $object = new User();
        $object->setEmail('test@test.com');
        $object->setFirstname('John');
        $object->setLastname('Doe');
        $object->setBirthday(new \DateTime('1980-01-01'));

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // Check that the form's data matches the submitted data
        $this->assertEquals($formData['email'], $form->get('email')->getData());
        $this->assertEquals($formData['firstname'], $form->get('firstname')->getData());
        $this->assertEquals($formData['lastname'], $form->get('lastname')->getData());
        $this->assertEquals($formData['birthday'], $form->get('birthday')->getData()->format('Y-m-d'));
        $this->assertEquals($formData['plainPassword']['first'], $form->get('plainPassword')->get('first')->getData());
        $this->assertEquals($formData['plainPassword']['second'], $form->get('plainPassword')->get('second')->getData());
        $this->assertEquals($formData['agreeTerms'], $form->get('agreeTerms')->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}


