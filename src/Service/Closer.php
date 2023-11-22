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
     * @param DateTimeImmutable[] $dateTimeList sorted from the older (index:0) to the younger (index:>0)
     * @param integer $offset
     * @param CloserArguments $unity
     * @return array of dates groupped by proximity considering offset restrictions
     */
    public static function groupDateTimeArray(array $dateTimeList,int $offset=3,CloserArguments $unity= CloserArguments::MONTHS):array{

        //Amélioration de la lisibilité des conditions
        //fonction de calcul si la différence entre deux dates dépasse l'offset défini
        function isDifferenceOverflowingOffset(DateTimeImmutable $date1,DateTimeImmutable $date2,int $timestampOffset):bool
        {
            return $date2->getTimestamp() - $date1->getTimestamp() > $timestampOffset;
        }

        //fonction d'évaluation si la clée est la dernière du tableau
        function isKeyLastOfArray(array $arrayToTest,int $keyToTest):bool
        {
            return $keyToTest == count($arrayToTest)-1;
        }

        // function arrayShouldBeSorted(array $dateTimeArray){

        // }

        //calcul de l'offset en timestamp
        $timestampOffset = self::calculateOffsetTimestamp($offset,$unity);

        //Recherche des groupes de proximité
        $groups = [];
        /**
         * Pour chaque date dans la liste fournie
         * @var DateTime $date
         */
        foreach($dateTimeList as $key1=>$date1){

            foreach($dateTimeList as $key2=>$date2){
                //passe les clées avant celle principale
                if($key2 <= $key1){
                    continue;
                }
                //si la distance dépasse l'offset ou que nous sommes sur la dernière ittération et que la condition est toujours respectée
                if(isDifferenceOverflowingOffset($date1,$date2,$timestampOffset) || (isKeyLastOfArray($dateTimeList,$key2) && !isDifferenceOverflowingOffset($date1,$date2,$timestampOffset))){
                    $retainedDates = [];
                    if(isKeyLastOfArray($dateTimeList,$key2) && !isDifferenceOverflowingOffset($date1,$date2,$timestampOffset)){
                        $key2 ++;//ce qui revient à changer le signe "<" du for en dessous par un "<="
                    }
                    //on va parcourt l'ensemble des dates entre la date1 et la date2
                    for ($i = $key1;$i<$key2;$i++){
                        //on ajoute les clées au groupe
                        $retainedDates[] = $dateTimeList[$i];
                    }
                    //si l'échantillon contient au moins 2 dates
                    if (count($retainedDates)>=2){
                        //on l'ajoute au groupe
                        $groups[] = $retainedDates;
                    }
                    break;
                }

            }
        }
        return $groups;

    }
}