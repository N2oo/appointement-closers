<?php

namespace App\Tests\Service;

use App\Service\Closer;
use App\Service\Enumeration\CloserArguments;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CloserTest extends KernelTestCase{

    private function getService():Closer
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        return $container->get(Closer::class);
    }
    
    public function testCloserAlgorithm(){
        //referer to ./public/schemaCloserProCorrected.png for further info
        $service = $this->getService();

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

        
        $result = $service::groupDateTimeArray($dateTimeList,3,CloserArguments::MONTHS);

        $this->assertEquals($expected,$result);
    }

    
}