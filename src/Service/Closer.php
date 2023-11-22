<?php

namespace App\Service;

use App\Service\Enumeration\CloserArguments;
use DateInterval;
use DateTime;
use DateTimeImmutable;

class Closer{

    /**
     * @param int $offset
     * @param CloserArguments $unity
     * 
     * @return int calculated offset
     */
    private static function calculateOffsetTimestamp(int $offset = 3, CloserArguments $unity = CloserArguments::MONTHS):int
    {
        $now = new DateTimeImmutable();
        $timestampNow = $now->getTimestamp();
        //AJOUT DE LA PERIODE VARIABLE
        $dateTimeInterval = new DateInterval(sprintf("P%s%s",$offset,$unity->value));
        //calcul de la date + offset
        $dateLater = $now->add($dateTimeInterval);
        $offsetLater = $dateLater->getTimestamp();

        //calcul de l'offset en timestamp
        return  $offsetLater - $timestampNow;
    }
    
    /**
     * Group given DateTime considering given Offset
     *
     * @param DateTimeImmutable[] $dateTimeList
     * @param integer $offset
     * @param CloserArguments $unity
     * @return array of dates groupped by proximity considering offset restrictions
     */
    public static function groupDateTimeArray(array $dateTimeList,int $offset=3,CloserArguments $unity= CloserArguments::MONTHS):array{

        //calcul de l'offset en timestamp
        $timestampOffset = self::calculateOffsetTimestamp($offset,$unity);

        //Recherche des groupes de proximité
        $groups = [];
        /**
         * Pour chaque date dans la liste fournie
         * @var DateTime $date
         */
        foreach($dateTimeList as $key=>$date){
            $k = $key;
            while(true){
                //si l'écart entre la première date evaluée (du foreach) et la date contenue à $k est > l'offset
                // la partie sur le $k est en prévention d'un bug sur les fins de liste
                if($k > count($dateTimeList) - 1 || $dateTimeList[$k]->getTimestamp() - $date->getTimestamp() > $timestampOffset){
                    // on créer un groupe entre la première date évaluée et la celle avant le dépassement de l'offset
                    $retainedDates = [];
                    //permet de parcourir tous les elements entre le premier élément ($key) et celui qui a fait dépassé l'offset ($k)
                    for ($i = $key;$i<$k;$i++){
                        $retainedDates[] = $dateTimeList[$i];
                    }
                    if (count($retainedDates)>1){
                        $groups[] = $retainedDates;
                    }
                    break;
                }
                //A chaque tour du while, on incrémente la clée suppérieure
                $k++;
            }
        }
        return $groups;

    }
}