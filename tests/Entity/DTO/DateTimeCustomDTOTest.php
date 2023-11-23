<?php

namespace App\Tests\Entity\DTO;

use App\Entity\DTO\DateTimeCustomDTO;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Range;

class DateTimeCustomDTOTest extends KernelTestCase
{
    public function getValidator():ValidatorInterface
    {
        $kernel = self::bootKernel();

        // $routerService = static::getContainer()->get('router');
        return static::getContainer()->get(ValidatorInterface::class);
    }

    public function testShouldNotGetErrors(){
        $validator = $this->getValidator();
        $dateTimeCustom = new DateTimeCustomDTO(1,1,1,1,1,1);
        $errors = $validator->validate($dateTimeCustom);
        $errorsCount= count($errors);
        $this->assertEquals(0,$errorsCount);
    }

    private function provideInvalidDay(){
        return [
            [0],
            [-1],
            [32],
            [23456]
        ];
    }

    /**
     * @dataProvider provideInvalidDay
     *
     * @return void
     */
    public function testShouldGetErrorDayBetween1And31(int $invalidDay){
        $validator = $this->getValidator();

        $dateTimeCustom = new DateTimeCustomDTO($invalidDay,1,1,1,1,1);
        $errors = $validator->validate($dateTimeCustom,new Range(["min"=>1,"max"=>31]));
        $errorsCount= count($errors);
        $this->assertEquals(1,$errorsCount);
    }

    private function provideInvalidMonth(){
        return [
            [0],
            [-1],
            [13],
            [23456]
        ];
    }

    /**
     * @dataProvider provideInvalidMonth
     *
     * @return void
     */
    public function testShouldGetErrorMonthBetween1And12(int $invalidMonth){
        $validator = $this->getValidator();

        $dateTimeCustom = new DateTimeCustomDTO(1,$invalidMonth,1,1,1,1);
        $errors = $validator->validate($dateTimeCustom,new Range(["min"=>1,"max"=>12]));
        $errorsCount= count($errors);
        $this->assertEquals(1,$errorsCount);
    }

    private function provideInvalidYear(){
        return [
            [0],
            [-1],
            [-13],
            [-23456],
            [32768]
        ];
    }

    /**
     * @dataProvider provideInvalidYear
     *
     * @return void
     */
    public function testShouldGetErrorYearBetween1And32767(int $invalidYear){
        $validator = $this->getValidator();

        $dateTimeCustom = new DateTimeCustomDTO(1,1,$invalidYear,1,1,1);
        $errors = $validator->validate($dateTimeCustom,new Range(["min"=>1,"max"=>32767]));
        $errorsCount= count($errors);
        $this->assertEquals(1,$errorsCount);
    }

    private function provideInvalidHour(){
        return [
            [-2234],
            [-1],
            [24],
            [-23456]
        ];
    }

    /**
     * @dataProvider provideInvalidHour
     *
     * @return void
     */
    public function testShouldGetErrorHourBetween0And23(int $invalidHour){
        $validator = $this->getValidator();

        $dateTimeCustom = new DateTimeCustomDTO(1,1,1,$invalidHour,1,1);
        $errors = $validator->validate($dateTimeCustom,new Range(["min"=>0,"max"=>23]));
        $errorsCount= count($errors);
        $this->assertEquals(1,$errorsCount);
    }

    private function provideInvalidMinutesOrSeconds(){
        return [
            [-2234],
            [-1],
            [60],
            [-23456]
        ];
    }

    /**
     * @dataProvider provideInvalidMinutesOrSeconds
     *
     * @return void
     */
    public function testShouldGetErrorMinuteBetween0And59(int $invalidMinute){
        $validator = $this->getValidator();

        $dateTimeCustom = new DateTimeCustomDTO(1,1,1,1,$invalidMinute,1);
        $errors = $validator->validate($dateTimeCustom,new Range(["min"=>0,"max"=>59]));
        $errorsCount= count($errors);
        $this->assertEquals(1,$errorsCount);
    }
    /**
     * @dataProvider provideInvalidMinutesOrSeconds
     *
     * @return void
     */
    public function testShouldGetErrorSecondBetween0And59(int $invalidSecond){
        $validator = $this->getValidator();

        $dateTimeCustom = new DateTimeCustomDTO(1,1,1,1,1,$invalidSecond);
        $errors = $validator->validate($dateTimeCustom,new Range(["min"=>0,"max"=>59]));
        $errorsCount= count($errors);
        $this->assertEquals(1,$errorsCount);
    }

    public function testShouldGetErrorLeapYear(){
        $validator = $this->getValidator();

        $dateTimeCustom = new DateTimeCustomDTO(29,2,2017,1,1,1);//année non bissextile
        $errors = $validator->validate($dateTimeCustom);
        $errorsCount= count($errors);
        $this->assertEquals(1,$errorsCount);
    }
    public function testShouldNotGetErrorLeapYear(){
        $validator = $this->getValidator();

        $dateTimeCustom = new DateTimeCustomDTO(29,2,2020,1,1,1);//année bissextile
        // $errors = $validator->validate($dateTimeCustom,new IsTrue(message:"La date soumise n'existe pas, vérifiez si l'année est bissextile"));
        $errors = $validator->validate($dateTimeCustom);
        $errorsCount= count($errors);
        $this->assertEquals(0,$errorsCount);
    }

    public function testShouldReturnValidDateTimeObject(){
        $validator = $this->getValidator();
        $year = 2020;
        $month = 2;
        $day = 29;
        $hour = $minute = $second = 1;

        $dateTimeCustom = new DateTimeCustomDTO($day,$month,$year,$hour,$minute,$second);
        $errors = $validator->validate($dateTimeCustom);
        $errorsCount= count($errors);
        $this->assertEquals(0,$errorsCount);
        $expectedDateTimeObject = (new DateTimeImmutable())->setDate($year,$month,$day)->setTime($hour,$minute,$second);
        $result = $dateTimeCustom->exportToDateTimeImmutable();
        $this->assertInstanceOf(DateTimeImmutable::class,$result);
        $this->assertEquals($expectedDateTimeObject,$result);
    }




}
