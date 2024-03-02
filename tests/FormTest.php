<?php

declare(strict_types=1);

namespace ApplicationTest;

use Application\Form;
use PHPUnit\Framework\TestCase;

final class FormTest extends TestCase
{
    public const string TEST_FILE = __DIR__ . '/data/test_file.csv';
    public const string ERR_FILE = __DIR__ . '/data/test_file_with_error.csv';

    public function testInstanceCanBeCreatedWithOneParam()
    {
        $form = new Form(self::TEST_FILE);
        $this->assertInstanceOf(
            Form::class,
            $form
        );
    }

    public function testProcessReturnsArray()
    {
        $form = new Form(self::TEST_FILE);
        $result = $form->process();
        $this->assertIsArray($result);
    }

    public function testGetErrorsReturnsEmptyArray()
    {
        $form = new Form(self::TEST_FILE);
        $form->process();
        $this->assertIsArray($form->getErrors());
        $this->assertEmpty($form->getErrors());
    }

    public function testGetErrorsReturnsArrayWithError()
    {
        $form = new Form(self::ERR_FILE);
        $form->process();
        $errors = $form->getErrors();
        $this->assertIsArray($errors);
        $this->assertEquals('Error on row 7: Title is required.', $errors[0]);
    }
}
