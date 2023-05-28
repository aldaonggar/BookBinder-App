<?php

namespace App\Tests\Unit\Form;

use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class ResetPasswordRequestFormTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidator();
        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@example.com',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testSubmitBlankEmail()
    {
        $formData = [
            'email' => '',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidEmailFormat()
    {
        $formData = [
            'email' => 'notAnEmail',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
    public function testSubmitInvalidEmailFormat2()
    {
        $formData = [
            'email' => 'notAn@Email',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
    public function testSubmitInvalidEmailFormat3()
    {
        $formData = [
            'email' => 'notAnEmai@l',
        ];

        $form = $this->factory->create(ResetPasswordRequestFormType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
    }
}
