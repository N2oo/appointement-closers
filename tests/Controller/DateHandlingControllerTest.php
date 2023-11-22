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

    public function testResponseIsOkWhenPOST(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/make/suggestions');
        $this->assertResponseIsSuccessful();
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





}
