<?php

namespace App\Tests\Entity\DTO\Collection;

use App\Entity\DTO\DateTimeCustomDTO;
use App\Entity\DTO\Collection\DateTimeCustomCollectionDTO;
use PHPUnit\Framework\TestCase;

class DateTimeCustomCollectionDTOTest extends TestCase
{

    public function provideDataSortedOlderToYounger(){
        $one = new DateTimeCustomDTO(1,1,1,1,1,1);
        $two = new DateTimeCustomDTO(1,1,200,1,1,1);
        $three = new DateTimeCustomDTO(1,1,500,1,1,1);
        $dateTimeArray=[$one,$three,$two];
        $expectedOrder=[$one,$two,$three];
        return [
            [$dateTimeArray,$expectedOrder]
        ];
    }
    public function provideDataSortedYoungerToOlder(){
        $one = new DateTimeCustomDTO(1,1,1,1,1,1);
        $two = new DateTimeCustomDTO(1,1,200,1,1,1);
        $three = new DateTimeCustomDTO(1,1,500,1,1,1);
        $dateTimeArray=[$one,$three,$two];
        $expectedOrder=[$three,$two,$one];
        return [
            [$dateTimeArray,$expectedOrder]
        ];
    }

    /**
     * @dataProvider provideDataSortedOlderToYounger
     *
     * @param array $dateTimeArray
     * @param array $expectedOrder
     * @return void
     */
    public function testSortingOlderToYunger(array $dateTimeArray,array $expectedOrder):void{
        $this->assertNotEquals($dateTimeArray,$expectedOrder);
        $collection = new DateTimeCustomCollectionDTO($dateTimeArray);
        $result = $collection->sortOlderToYounger();
        $this->assertEquals($collection,$result);//vérifier que la méthode est fluent
        $this->assertEquals($expectedOrder,$collection->getData());
    }
    
    /**
     * @dataProvider provideDataSortedYoungerToOlder
     * @param array $dateTimeArray
     * @param array $expectedOrder
     * 
     * @return void
     */
    public function testSortingYoungerToOlder(array $dateTimeArray,array $expectedOrder):void{
        $this->assertNotEquals($dateTimeArray,$expectedOrder);
        $collection = new DateTimeCustomCollectionDTO($dateTimeArray);
        $result = $collection->sortYoungerToOlder();
        $this->assertEquals($collection,$result);//vérifier que la méthode est fluent
        $this->assertEquals($expectedOrder,$collection->getData());
    }


}
