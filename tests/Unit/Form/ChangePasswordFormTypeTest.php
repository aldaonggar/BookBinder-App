<?php

namespace App\Tests\Unit\Form;

use App\Form\ChangePasswordFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ChangePasswordFormTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        // Here is where we enable validation.
        $validator = Validation::createValidator();
        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'ValidPassword1!',
                'second' => 'ValidPassword1!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testSubmitValidData2()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Qwerty12*!',
                'second' => 'Qwerty12*!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testSubmitValidData3()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Password1!',
                'second' => 'Password1!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testSubmitInvalidData()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'invalid',
                'second' => 'invalid',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidData2()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'invalid123',
                'second' => 'invalid123',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
    public function testSubmitInvalidData3()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Invalid12',
                'second' => 'Invalid12',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
    public function testSubmitInvalidData4()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'invalid123!',
                'second' => 'invalid123!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
    public function testSubmitMismatchedPasswords()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'ValidPassword1!',
                'second' => 'DifferentPassword2!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitMismatchedPasswords2()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'ValidPassword1!',
                'second' => 'ValidPassword1*',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitMismatchedPasswords3()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'ValidPassword1!',
                'second' => 'validPassword1!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitShortPassword()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'Short1!',
                'second' => 'Short1!',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitPasswordWithoutSpecialChar()
    {
        $formData = [
            'plainPassword' => [
                'first' => 'NoSpecialChar1',
                'second' => 'NoSpecialChar1',
            ],
        ];

        $form = $this->factory->create(ChangePasswordFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
}
