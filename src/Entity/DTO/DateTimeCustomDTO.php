<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class DateTimeCustomDTO
{
    public function __construct(
        #[Assert\Range([
            "min"=>1,
            "max"=>31])]
        private readonly int $day,

        #[Assert\Range([
            "min"=>1,
            "max"=>12])]
        private readonly int $month,

        #[Assert\Range([
            "min"=>1,
            "max"=>32767])]//check https://www.php.net/manual/fr/function.checkdate.php for details about "max"
        private readonly int $year,

        #[Assert\Range([
            "min"=>0,
            "max"=>23])]
        private readonly int $hour,

        #[Assert\Range([
            "min"=>0,
            "max"=>59])]
        private readonly int $minute,

        #[Assert\Range([
            "min"=>0,
            "max"=>59])]
        private readonly int $second,

    )
    {
        
    }

    #[Assert\IsTrue(message:"La date soumise n'existe pas, vérifiez si l'année est bissextile")]
    public function isValidDate():bool
    {
        return checkdate($this->getMonth(),$this->getDay(),$this->getYear());
    }

        /**
         * Get the value of second
         */ 
        public function getSecond():int
        {
                return $this->second;
        }

        /**
         * Get the value of minute
         */ 
        public function getMinute():int
        {
                return $this->minute;
        }

        /**
         * Get the value of hour
         */ 
        public function getHour():int
        {
                return $this->hour;
        }

        /**
         * Get the value of year
         */ 
        public function getYear():int
        {
                return $this->year;
        }

        /**
         * Get the value of month
         */ 
        public function getMonth():int
        {
                return $this->month;
        }

        /**
         * Get the value of day
         */ 
        public function getDay():int
        {
                return $this->day;
        }
}