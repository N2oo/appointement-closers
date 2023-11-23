<?php

namespace App\Tests\Entity\DTO\Collection;

use App\Entity\DTO\DateTimeCustomDTO;
use PHPUnit\Framework\TestCase;

class DateTimeCustomCollectionDTOTest extends TestCase
{
    public function testDataContainOnlyExpectedType(): void
    {
        $year = 2020;
        $month = 2;
        $day = 29;
        $hour = $minute = $second = 1;

        $dateTimeCustom1 = new DateTimeCustomDTO($day,$month,$year,$hour,$minute,$second);
        $dateTimeCustom2 = new DateTimeCustomDTO($day,$month,$year,$hour,$minute,$second);

        $this->assertTrue(true);
        $this->markTestIncomplete();
    }


}
