<?php

namespace App\Tests\Service;

use App\Entity\DTO\Collection\DateTimeCustomCollectionDTO;
use App\Entity\DTO\DateTimeCustomDTO;
use App\Service\Closer;
use App\Service\Enumeration\CloserArguments;
use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CloserTest extends KernelTestCase{

    private function getService():Closer
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        return $container->get(Closer::class);
    }
    private function provideDateTimeImmutableArrayAndExpectedResult(){
        $dateTimeCollection = new DateTimeCustomCollectionDTO([
            (new DateTimeCustomDTO(1,1,2024,1,0,0)),
            (new DateTimeCustomDTO(1,2,2024,1,0,0)),
            (new DateTimeCustomDTO(1,3,2024,1,0,0)),
            (new DateTimeCustomDTO(1,4,2024,1,0,0)),
            (new DateTimeCustomDTO(1,5,2024,1,0,0)),
            (new DateTimeCustomDTO(1,7,2024,1,0,0)),
            (new DateTimeCustomDTO(1,8,2024,1,0,0)),
            (new DateTimeCustomDTO(1,10,2024,1,0,0)),
            (new DateTimeCustomDTO(1,11,2024,1,0,0)),
            (new DateTimeCustomDTO(1,12,2024,1,0,0))
        ]);

        $expected = [
            [
                new DateTimeImmutable('2024-01-01 01:00:00'),
                new DateTimeImmutable('2024-02-01 01:00:00'),
                new DateTimeImmutable('2024-03-01 01:00:00'),
                new DateTimeImmutable('2024-04-01 01:00:00'),
            ],
            [
                new DateTimeImmutable('2024-02-01 01:00:00'),
                new DateTimeImmutable('2024-03-01 01:00:00'),
                new DateTimeImmutable('2024-04-01 01:00:00'),
                new DateTimeImmutable('2024-05-01 01:00:00'),
            ],
            [
                new DateTimeImmutable('2024-03-01 01:00:00'),
                new DateTimeImmutable('2024-04-01 01:00:00'),
                new DateTimeImmutable('2024-05-01 01:00:00'),
            ],
            [
                new DateTimeImmutable('2024-04-01 01:00:00'),
                new DateTimeImmutable('2024-05-01 01:00:00'),
                new DateTimeImmutable('2024-07-01 01:00:00'),
            ],
            [
                new DateTimeImmutable('2024-05-01 01:00:00'),
                new DateTimeImmutable('2024-07-01 01:00:00'),
                new DateTimeImmutable('2024-08-01 01:00:00'),
            ],
            [
                new DateTimeImmutable('2024-07-01 01:00:00'),
                new DateTimeImmutable('2024-08-01 01:00:00'),
                new DateTimeImmutable('2024-10-01 01:00:00'),
            ],
            [
                new DateTimeImmutable('2024-08-01 01:00:00'),
                new DateTimeImmutable('2024-10-01 01:00:00'),
                new DateTimeImmutable('2024-11-01 01:00:00'),
            ],
            [
                new DateTimeImmutable('2024-10-01 01:00:00'),
                new DateTimeImmutable('2024-11-01 01:00:00'),
                new DateTimeImmutable('2024-12-01 01:00:00'),
            ],
            [
                new DateTimeImmutable('2024-11-01 01:00:00'),
                new DateTimeImmutable('2024-12-01 01:00:00'),
            ]
        ];
        return [
            [$dateTimeCollection,$expected]
        ];
    }
    
    /**
     * @dataProvider provideDateTimeImmutableArrayAndExpectedResult
     *
     * @return void
     */
    public function testCloserAlgorithm($dateTimeList,$expected){
        //referer to ./public/schemaCloserProCorrected.png for further info
        $service = $this->getService();
        
        $result = $service::makeClosingSuggestions($dateTimeList,3,CloserArguments::MONTHS);

        $this->assertEquals($expected,$result);
    }


    private function provideUnsortedDateTimeCustomCollection()
    {
        $dateTimeList = [
            (new DateTimeCustomDTO(1,1,2024,1,0,0)),
            (new DateTimeCustomDTO(1,2,2024,1,0,0)),
            (new DateTimeCustomDTO(1,3,2024,1,0,0)),
            (new DateTimeCustomDTO(1,4,2024,1,0,0)),
            (new DateTimeCustomDTO(1,7,2024,1,0,0)),
            (new DateTimeCustomDTO(1,5,2024,1,0,0)),
            (new DateTimeCustomDTO(1,8,2024,1,0,0)),
            (new DateTimeCustomDTO(1,10,2024,1,0,0)),
            (new DateTimeCustomDTO(1,11,2024,1,0,0)),
            (new DateTimeCustomDTO(1,12,2024,1,0,0))
        ];
        return [
            [new DateTimeCustomCollectionDTO($dateTimeList)]
        ];
    }

    
    
}