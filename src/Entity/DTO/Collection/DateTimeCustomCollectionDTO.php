<?php
namespace App\Entity\DTO\Collection;
use App\Entity\DTO\DateTimeCustomDTO;
class DateTimeCustomCollectionDTO
{
    public function __construct(
        /**
         * @var DateTimeCustomDTO[] $result
         */
        private array $result
    )
    {}

    /**
     * Getter des resultats
     *
     * @return DateTimeCustomDTO[] $result
     */
    public function getResult():array
    {
        return $this->result;
    }




}