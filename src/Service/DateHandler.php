<?php

namespace App\Service;

use DateTime;
use DateTimeImmutable;

class DateHandler{

    public function handleJsonArrayToPHPArray(string $jsonData):array
    {
        return json_decode($jsonData,true);
    }

    public function handleJsonArrayToDateTimeArray(string $jsonData)
    {
        $dataArray = $this->handleJsonArrayToPHPArray($jsonData);
        $serializedDateTimeCustomList = [];
        foreach($dataArray as $dateTimeCustomUnserialized){
            $newDateTime = (new DateTimeImmutable())->setDate(
                $dateTimeCustomUnserialized["year"],
                $dateTimeCustomUnserialized["month"],
                $dateTimeCustomUnserialized["day"]
            )->setTime(
                $dateTimeCustomUnserialized["hour"],
                $dateTimeCustomUnserialized["minute"],
                $dateTimeCustomUnserialized["second"],
            );
            $serializedDateTimeCustomList[] = $newDateTime;
        }
        return $serializedDateTimeCustomList;
    }

    public function translateDateTimeArrayToTimeStampArray(array $dateTimeArray):array{
        $translatedArray = [];
        /**
         * @var DateTimeImmutable $element
         */
        foreach($dateTimeArray as $element){
            $translatedArray[] = $element->getTimestamp();
        }
        return $translatedArray;   
    }
    
    private function compareByTimestamp(int $val1,int $val2):int
    {
        return $val1 - $val2;
    }

    public function sortTimeStampArray(array $unsortedArray):array
    {
        usort($unsortedArray,[$this,'compareByTimestamp']);
        return $unsortedArray;
    }
}