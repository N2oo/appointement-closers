<?php

namespace App\Tests\Service;

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
    
    /**
     * @dataProvider provideDateTimeImmutableArrayAndExpectedResult
     *
     * @return void
     */
    public function testCloserAlgorithm($dateTimeList,$expected){
        //referer to ./public/schemaCloserProCorrected.png for further info
        $service = $this->getService();
        
        $result = $service::groupDateTimeArray($dateTimeList,3,CloserArguments::MONTHS);

        $this->assertEquals($expected,$result);
    }


    private function provideUnsortedDateTimeImmutableArray()
    {
        $dateTimeList = [
            (new DateTimeImmutable('2024-01-01 01:00:00')),
            (new DateTimeImmutable('2024-02-01 01:00:00')),
            (new DateTimeImmutable('2024-03-01 01:00:00')),
            (new DateTimeImmutable('2024-04-01 01:00:00')),
            (new DateTimeImmutable('2024-07-01 01:00:00')),
            (new DateTimeImmutable('2024-05-01 01:00:00')),
            (new DateTimeImmutable('2024-08-01 01:00:00')),
            (new DateTimeImmutable('2024-10-01 01:00:00')),
            (new DateTimeImmutable('2024-11-01 01:00:00')),
            (new DateTimeImmutable('2024-12-01 01:00:00')),
        ];
        return [
            [$dateTimeList]
        ];
    }
    private function provideNotOnlyDateTimeImmutableArray()
    {
        $dateTimeList1 = [
            (new DateTimeImmutable('2024-01-01 01:00:00')),
            (new DateTime('2024-02-01 01:00:00')),
        ];
        $dateTimeList2 = [
            (new DateTimeImmutable('2024-01-01 01:00:00')),
            null,
        ];
        $dateTimeList3 = [
            (new DateTimeImmutable('2024-01-01 01:00:00')),
            14,
        ];

        return [
            [$dateTimeList1],
            [$dateTimeList2],
            [$dateTimeList3],
        ];
    }

    private function provideDateTimeImmutableArrayAndExpectedResult(){
        $dateTimeList = [
            (new DateTimeImmutable('2024-01-01 01:00:00')),
            (new DateTimeImmutable('2024-02-01 01:00:00')),
            (new DateTimeImmutable('2024-03-01 01:00:00')),
            (new DateTimeImmutable('2024-04-01 01:00:00')),
            (new DateTimeImmutable('2024-05-01 01:00:00')),
            (new DateTimeImmutable('2024-07-01 01:00:00')),
            (new DateTimeImmutable('2024-08-01 01:00:00')),
            (new DateTimeImmutable('2024-10-01 01:00:00')),
            (new DateTimeImmutable('2024-11-01 01:00:00')),
            (new DateTimeImmutable('2024-12-01 01:00:00')),
        ];

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
            [$dateTimeList,$expected]
        ];
    }


    /**
     * Teste si le tableau est rangé de la plus vielle date à la plus récente
     * @dataProvider provideUnsortedDateTimeImmutableArray
     *
     * @param array $dateTimeList
     * @return void
     */
    public function testDateTimeArrayShouldBeSortedOlderToYounger(array $dateTimeList):void
    {
        $service = $this->getService();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("La liste soumise n'a pas été ordonnée dans l'ordre attendu");
        $service::groupDateTimeArray($dateTimeList,3,CloserArguments::MONTHS);

    }

    /**
     * Teste si le tableau fourni contient uniquement des valeurs du type DateTimeImmutable
     * @dataProvider provideNotOnlyDateTimeImmutableArray
     *
     * @return void
     */
    public function testDateTimeArrayShouldOnlyContainDateTimeImmutableElement(array $dateTimeList):void
    {
        $service = $this->getService();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("La liste soumise ne contient pas uniquement des éléments du type DateTimeImmutable");
        $service::groupDateTimeArray($dateTimeList,3,CloserArguments::MONTHS);
    }
    
}