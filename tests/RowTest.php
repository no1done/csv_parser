<?php

declare(strict_types=1);

namespace ApplicationTest;

use Application\Row;
use PHPUnit\Framework\TestCase;

final class RowTest extends TestCase
{
    public function testInstanceCanBeCreatedWithArray()
    {
        $obj = new Row(['mr john smith', '']);
        $this->assertInstanceOf(Row::class, $obj);
    }

    public function testInstanceCanBeCreatedWithString()
    {
        $obj = new Row('mr lowry');
        $this->assertInstanceOf(Row::class, $obj);
    }

    public function testArrayToStringConvertsArrayToString()
    {
        $array = [
            'Mr John Smith',
            ''
        ];

        $expect = 'Mr John Smith';

        $row = new Row($array);
        $result = $row->arrayToString($array);
        $this->assertEquals($expect, $result);
    }

    public function testArrayToStringConvertsArrayToStringMultiple()
    {
        $array = [
            'Mr John Smith',
            'Ms Jane Smith'
        ];

        $expect = 'Mr John Smith & Ms Jane Smith';

        $row = new Row($array);
        $result = $row->arrayToString($array);
        $this->assertEquals($expect, $result);
    }

    public function testSegmentCounts()
    {
        $string = "Mr John Smith and Mr Dave Jones";

        $row = new Row($string);

        $result = $row->getPeople();

        $this->assertEquals('Mr', $result[0]->getTitle());
        $this->assertEquals('John', $result[0]->getFirstName());
        $this->assertEquals('Smith', $result[0]->getSurname());

        $this->assertEquals('Mr', $result[1]->getTitle());
        $this->assertEquals('Dave', $result[1]->getFirstName());
        $this->assertEquals('Jones', $result[1]->getSurname());
    }
}
