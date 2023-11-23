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

    public function testResponseDataIsSameAsGiven(): void
    {
        $content = ["data"=>
        [
            ["year"=>1,
            "month"=>1,
            "day"=>1,
            "hour"=>1,
            "minute"=>1,
            "second"=>1,]
        ]];
        $client = static::createClient();
        $crawler = $client->jsonRequest("POST","/make/suggestions",$content);
        $this->assertResponseIsSuccessful();
        $this->markTestIncomplete();//il faut dabord processer la data
        
    }
}
