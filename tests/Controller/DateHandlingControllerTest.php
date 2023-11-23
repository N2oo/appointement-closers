<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DateHandlingControllerTest extends WebTestCase
{

    public function provideHttpMethodOtherThanPOST()
    {
        return [
            ["GET"],
            ["PATCH"],
            ["PUT"],
            ["DELETE"]
        ];
    }

    /**
     * @dataProvider provideHttpMethodOtherThanPOST
     *
     * @return void
     */
    public function testResponseIsWrongOtherThanPOST(string $methodName): void
    {
        $client = static::createClient();
        $crawler = $client->request($methodName, '/make/suggestions');
        $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function provideValidAssociativeArrayForRequest()
    {
        $associativeArray = [
            "data" => [
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 1,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 2,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 3,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 4,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 5,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 7,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 8,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 10,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 11,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 12,
                    "year" => 2024
                ]
            ]
        ];

        return [
            [$associativeArray]
        ];
    }

    public function provideInvalidAssociativeArrayForRequest()
    {
        $associativeArray = [
            "data" => [
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 1,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 2,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 3,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 4,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 5,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 7,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 8,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 10,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 11,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 1,
                    "month" => 12,
                    "year" => 2024
                ],
                [
                    "second" => 0,
                    "minute" => 0,
                    "hour" => 1,
                    "day" => 29,
                    "month" => 2,
                    "year" => 2017
                ]
            ]
        ];

        return [
            [$associativeArray]
        ];
    }

    /**
     * @dataProvider provideValidAssociativeArrayForRequest
     *
     * @param array $content
     * @return void
     */
    public function testResponseIsValid(array $content): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest("POST","/make/suggestions",$content);
        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider provideInvalidAssociativeArrayForRequest
     *
     * @param array $content
     * @return void
     */
    public function testResponseIsInvalid(array $content): void
    {
        $client = static::createClient();
        $crawler = $client->jsonRequest("POST","/make/suggestions",$content);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
