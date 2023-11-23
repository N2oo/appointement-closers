<?php

namespace App\Tests\Entity\DTO\Collection;

use App\Entity\DTO\Collection\GenericCollection;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SampleObj{
    public function __construct(
        public string $key = "hello"
    ){}
}

class SampleObjBis{

}

class GenericCollectionTest extends KernelTestCase
{
    private function getValidator():ValidatorInterface
    {
        self::bootKernel();
        return static::getContainer()->get(ValidatorInterface::class);
    }

    public function provideValidSampleObjAndInfo()
    {
        $o0= new SampleObj("0");
        $o1= new SampleObj("1");
        $o2= new SampleObj("2");
        $dataProvided = [
            $o0,
            $o1,
            $o2
        ];
        $type = SampleObj::class;
        return [
            [$dataProvided,$type]
        ];
    }
    //test de l'implémentation de l'interface Iterator

    /**
     * @dataProvider provideValidSampleObjAndInfo
     *
     * @param [type] $dataProvided
     * @param [type] $type
     * @return void
     */
    public function testCurrent($dataProvided,$type){
        $collection = new GenericCollection($dataProvided,$type);
        $this->assertEquals($dataProvided[0],$collection->current());

    }

    /**
     * @dataProvider provideValidSampleObjAndInfo
     *
     * @return void
     */
    public function testKey($dataProvided,$type):void{
        $collection = new GenericCollection($dataProvided,$type);
        $this->assertEquals(0,$collection->key());
    }

    /**
     * @dataProvider provideValidSampleObjAndInfo
     *
     * @return void
     */
    public function testNext($dataProvided,$type):void{
        $collection = new GenericCollection($dataProvided,$type);
        $collection->next();
        $this->assertEquals(1,$collection->key());
    }

    /**
     * @dataProvider provideValidSampleObjAndInfo
     * @return void
     */
    public function testRewind($dataProvided,$type):void{
        $collection = new GenericCollection($dataProvided,$type);
        $collection->next();
        $collection->rewind();
        $this->assertEquals(0,$collection->key());
    }

    /**
     * @dataProvider provideValidSampleObjAndInfo
     * @return void
     */
    public function testValid($dataProvided,$type):void{
        $collection = new GenericCollection($dataProvided,$type);
        $this->assertTrue($collection->valid());
        $collection->next();
        $collection->next();
        $collection->next();
        $collection->next();
        $this->assertFalse($collection->valid());

    }
    //fin de test de l'implémentation de l'interface Iterator


    public function testHandleDataAndGiveItBack(){
        $data = [
            new SampleObj(),
            new SampleObj(),
            new SampleObj()
        ];
        $collection = new GenericCollection($data,SampleObj::class);
        $return = $collection->getData();
        $this->assertSame($data,$return);

    }

    public function testDataContainOnlyExpectedType(): void
    {
        $data = [
            new SampleObj(),
            new SampleObj(),
            new SampleObjBis(),
        ];
        $validator = $this->getValidator();
        $collection = new GenericCollection($data,SampleObj::class);
        $error = $validator->validate($collection,groups:["motherClass"]);
        $error_count = count($error);
        $this->assertSame(1,$error_count);

    }

    public function testAddElementToCollection()
    {
        $data = [
            new SampleObj(),
            new SampleObj(),
            new SampleObj(),
        ];
        $type = SampleObj::class;
        $collection = new GenericCollection($data,$type);
        $newElement = new SampleObj();
        $data[] = $newElement;
        $returned = $collection->add($newElement);
        $this->assertSame($collection,$returned);//doit retourner elle même
        $this->assertSame($data,$collection->getData());//doit avoir faire les modifications
    }

    public function testAddWrongElementToCollection()
    {
        $data = [
            new SampleObj(),
            new SampleObj(),
            new SampleObj()
        ];
        $type = SampleObj::class;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf("L'élément passé en paramètre n'est pas du type attendu \"%s\"",$type));

        $collection = new GenericCollection($data,$type);
        $newElement = new SampleObjBis();
        $data[] = $newElement;
        $collection->add($newElement);
    }

    public function testReplaceElement()
    {
        $o0= new SampleObj("0");
        $o1= new SampleObj("1");
        $o2= new SampleObj("2");
        $dataProvided = [
            $o0,
            $o1,
            $o2
        ];
        $newElement = new SampleObj("4");
        $dataExpected = [
            $o0,
            $newElement,
            $o2
        ];
        $type = SampleObj::class;
        $collection = new GenericCollection($dataProvided,$type);

        $result = $collection->replace($newElement,1);

        $this->assertSame($collection,$result);//assure le setter fluent
        $this->assertSame($dataExpected,$result->getData());
    }

    public function testReplaceWrongElement()
    {
        $o0= new SampleObj("0");
        $o1= new SampleObj("1");
        $o2= new SampleObj("2");
        $dataProvided = [
            $o0,
            $o1,
            $o2
        ];
        $newElement = new SampleObjBis();
        $type = SampleObj::class;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf("L'élément passé en paramètre n'est pas du type attendu \"%s\"",$type));

        $collection = new GenericCollection($dataProvided,$type);
        $result = $collection->replace($newElement,1);
    }

    public function testReplaceWrongIndex()
    {
        $key = 100;
        $o0= new SampleObj("0");
        $o1= new SampleObj("1");
        $o2= new SampleObj("2");
        $dataProvided = [
            $o0,
            $o1,
            $o2
        ];
        $newElement = new SampleObj("2");
        $type = SampleObj::class;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf("L'index (%d) fourni n'existe pas",$key));

        $collection = new GenericCollection($dataProvided,$type);
        $result = $collection->replace($newElement,$key);//on donne un index inexistant
    }


    /**
     * @dataProvider provideValidSampleObjAndInfo
     * @return void
     */
    public function testRemoveElementAndRearangeIt($dataProvided,$type):void
    {
        //tester la suppression d'un element
        $collection = new GenericCollection($dataProvided,$type);

        
        $data = $collection->getData();
        $indexToRemove = 1;
        unset($data[$indexToRemove]);
        $data = array_values($data);

        $return = $collection->remove($indexToRemove);

        $this->assertEquals($collection,$return);
        $this->assertEquals($data,$collection->getData());
    }

    /**
     * @dataProvider provideValidSampleObjAndInfo
     * @return void
     */
    public function testRemoveElementWrongIndex($dataProvided,$type):void
    {
        $collection = new GenericCollection($dataProvided,$type);
        
        $data = $collection->getData();
        $indexToRemove = 1000;
        unset($data[$indexToRemove]);
        $data = array_values($data);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf("L'index (%d) fourni n'existe pas",$indexToRemove));

        $return = $collection->remove($indexToRemove);
    }

    public function provideUnsortedSampleObjAndInfo()
    {
        $o0= new SampleObj("0");
        $o1= new SampleObj("1");
        $o2= new SampleObj("2");
        $dataProvided = [
            $o1,//1
            $o0,//0
            $o2//2
        ];
        $type = SampleObj::class;
        $expectedOrder = [
            $o0,//0
            $o1,//1
            $o2//2
        ];
        return [
            [$dataProvided,$type,$expectedOrder]
        ];
    }

    /**
     * @dataProvider provideUnsortedSampleObjAndInfo
     * @return void
     */
    public function testSortElement($dataProvided,$type,$expectedOrder):void
    {
        $collection = new GenericCollection($dataProvided,$type);
        $return = $collection->sort(
            function (SampleObj $arg1,SampleObj $arg2)
            {
                return strcmp($arg1->key,$arg2->key);
            }
        );
        $this->assertIsArray($return);
        $this->assertSame($expectedOrder,$collection->getData());
    }
}
