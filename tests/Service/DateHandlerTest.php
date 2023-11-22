<?php

namespace App\Tests\Service;

use App\Service\DateHandler;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DateHandlerTest extends KernelTestCase
{
    private function getDateHandlerService():DateHandler{
            $kernel = self::bootKernel();

            $container = static::getContainer();

            return $container->get(DateHandler::class);
    }

    private function provideValidJSONDatesAndArrayTranslation(){
        return [['
        [
            {
              "day": 1,
              "month": 1,
              "year": 2023,
              "hour": 12,
              "minute": 30,
              "second": 45
            },
            {
              "day": 15,
              "month": 6,
              "year": 2023,
              "hour": 8,
              "minute": 0,
              "second": 0
            },
            {
              "day": 31,
              "month": 12,
              "year": 2023,
              "hour": 23,
              "minute": 59,
              "second": 59
            }
          ]
        ',[
            [
                "day" => 1,
                "month" => 1,
                "year" => 2023,
                "hour" => 12,
                "minute" => 30,
                "second" => 45
            ],
            [
                "day" => 15,
                "month" => 6,
                "year" => 2023,
                "hour" => 8,
                "minute" => 0,
                "second" => 0
            ],
            [
                "day" => 31,
                "month" => 12,
                "year" => 2023,
                "hour" => 23,
                "minute" => 59,
                "second" => 59
            ]
        ]]];
    }

    private function provideValidJSONDatesAndDateTimeTranslation(){
        return [['
        [
            {
              "day": 1,
              "month": 1,
              "year": 2023,
              "hour": 12,
              "minute": 30,
              "second": 45
            },
            {
              "day": 15,
              "month": 6,
              "year": 2023,
              "hour": 8,
              "minute": 0,
              "second": 0
            },
            {
              "day": 31,
              "month": 12,
              "year": 2023,
              "hour": 23,
              "minute": 59,
              "second": 59
            }
          ]
        ',[
            (new DateTimeImmutable())->setDate(2023,1,1)->setTime(12,30,45),
            (new DateTimeImmutable())->setDate(2023,6,15)->setTime(8,0,0),
            (new DateTimeImmutable())->setDate(2023,12,31)->setTime(23,59,59)
        ]]];
    }

    private function provideValidDateTimeArrayAndTimeStampTranslation(){
        return [
            [
                [
                    (new DateTimeImmutable())->setDate(2023,1,1)->setTime(12,30,45),
                    (new DateTimeImmutable())->setDate(2023,6,15)->setTime(8,0,0),
                    (new DateTimeImmutable())->setDate(2023,12,31)->setTime(23,59,59)
                ],
                [
                    (new DateTimeImmutable())->setDate(2023,1,1)->setTime(12,30,45)->getTimestamp(),
                    (new DateTimeImmutable())->setDate(2023,6,15)->setTime(8,0,0)->getTimestamp(),
                    (new DateTimeImmutable())->setDate(2023,12,31)->setTime(23,59,59)->getTimestamp()
                ]
            ]
        ];
    }

    private function provideUnsortedDateTimeAndSortedArray(){
        return [
            [
                [
                    (new DateTimeImmutable())->setDate(2023,12,31)->setTime(23,59,59)->getTimestamp(),
                    (new DateTimeImmutable())->setDate(2023,1,1)->setTime(12,30,45)->getTimestamp(),
                    (new DateTimeImmutable())->setDate(2023,6,15)->setTime(8,0,0)->getTimestamp(),
                ],
                [//expected
                    (new DateTimeImmutable())->setDate(2023,1,1)->setTime(12,30,45)->getTimestamp(),
                    (new DateTimeImmutable())->setDate(2023,6,15)->setTime(8,0,0)->getTimestamp(),
                    (new DateTimeImmutable())->setDate(2023,12,31)->setTime(23,59,59)->getTimestamp()
                ]
            ]
        ];
    }


    /**
     * @dataProvider provideValidJSONDatesAndArrayTranslation
     *
     * @param string $jsonData
     * @return void
     */
    public function testHandleValidJSONArrayToPHPArray(string $jsonData,array $expectedResult){
        $service = $this->getDateHandlerService();
        $returnValue = $service->handleJsonArrayToPHPArray($jsonData);
        $this->assertIsArray($returnValue);
        $this->assertEquals($expectedResult,$returnValue);
    }

    /**
     * @dataProvider provideValidJSONDatesAndDateTimeTranslation
     *
     * @param string $jsonData
     * @return void
     */
    public function testHandleValidJSONArrayToDateTimeArray(string $jsonData,array $expectedResult){
        $service = $this->getDateHandlerService();
        $returnValue = $service->handleJsonArrayToDateTimeArray($jsonData);
        $this->assertIsArray($returnValue);
        $this->assertEquals($expectedResult,$returnValue);
    }

    /**
     * @dataProvider provideValidDateTimeArrayAndTimeStampTranslation
     *
     * @param array $dateTimeArray
     * @param array $expectedResult
     * @return void
     */
    public function testTranslateDateTimeArrayToTimestampArray(array $dateTimeArray,array $expectedResult):void
    {
        $service = $this->getDateHandlerService();
        $returnValue = $service->translateDateTimeArrayToTimeStampArray($dateTimeArray);
        $this->assertIsArray($returnValue);
        $this->assertEquals($expectedResult,$returnValue);
    }

    /**
     * @dataProvider provideUnsortedDateTimeAndSortedArray
     *
     * @param array $timeStampArray
     * @param array $sortedArray
     * @return void
     */
    public function testTimeStampSorter(array $unsortedArray,array $sortedArray){
        $service = $this->getDateHandlerService();
        $returnedArray = $service->sortTimeStampArray($unsortedArray);
        $this->assertIsArray($returnedArray);
        $this->assertEquals($sortedArray,$returnedArray);
    }

    



}
