<?php declare(strict_types=1);

use Application\Person;
use PHPUnit\Framework\TestCase;

final class PersonTest extends TestCase
{
    public function testFromStringCreatesCorrectFullNameObject()
    {
        $person = new Person();
        $person->fromString('Mr John Doe');

        $this->assertEquals('Mr', $person->getTitle());
        $this->assertEquals('John', $person->getFirstName());
        $this->assertEquals('Doe', $person->getSurname());
        $this->assertNull($person->getInitial());
    }

    public function testFromStringCreatesCorrectWithInitialObject()
    {
        $person = new Person();
        $person->fromString('Miss J Doe');

        $this->assertEquals('Miss', $person->getTitle());
        $this->assertEquals('J', $person->getInitial());
        $this->assertEquals('Doe', $person->getSurname());
        $this->assertNull($person->getFirstName());
    }

    public function testFromStringCreatesCorrectDoubleBarrelName()
    {
        $person = new Person();
        $person->fromString('Mrs Faye Hughes-Eastwood');

        $this->assertEquals('Mrs', $person->getTitle());
        $this->assertEquals('Faye', $person->getFirstName());
        $this->assertEquals('Hughes-Eastwood', $person->getSurname());
        $this->assertNull($person->getInitial());
    }

    public function testFromStringCreatesCorrectPeriodInitial()
    {
        $person = new Person();
        $person->fromString('Mr F. Fredrickson');

        $this->assertEquals('Mr', $person->getTitle());
        $this->assertEquals('F', $person->getInitial());
        $this->assertEquals('Fredrickson', $person->getSurname());
        $this->assertNull($person->getFirstName());
    }

    public function testNameIsCreatedFromLowerCaseString()
    {
        $person = new Person();
        $person->fromString('mr john doe');

        $this->assertEquals('Mr', $person->getTitle());
        $this->assertEquals('john', $person->getFirstName());
        $this->assertEquals('doe', $person->getSurname());
        $this->assertNull($person->getInitial());
    }
}
