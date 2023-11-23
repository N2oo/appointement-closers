<?php
namespace App\Entity\DTO\Collection;
use App\Entity\DTO\DateTimeCustomDTO;

class DateTimeCustomCollectionDTO extends GenericCollection
{
    public function __construct(
        /**
         * @var DateTimeCustomDTO[] $data
         */
        private array $data
    )
    {
        parent::__construct($data,DateTimeCustomDTO::class);
    }

    /**
     * Getter des datas
     *
     * @return DateTimeCustomDTO[] $result
     */
    public function getData():array
    {
        return $this->data;
    }


    public function sortOlderToYounger():self
    {
        $sortFunction = function(DateTimeCustomDTO $arg1,DateTimeCustomDTO $arg2){
            return  $arg1->exportToDateTimeImmutable()->getTimestamp() - $arg2->exportToDateTimeImmutable()->getTimestamp();
        };
        $sorted = $this->sort($sortFunction);
        $this->data = $sorted;//pourquoi la réalocation ne se fait pas au niveau de la classe parente ?
        return $this;
    }
    public function sortYoungerToOlder():self
    {
        $sortFunction = function(DateTimeCustomDTO $arg1,DateTimeCustomDTO $arg2){
            return $arg2->exportToDateTimeImmutable()->getTimestamp() - $arg1->exportToDateTimeImmutable()->getTimestamp();
        };
        $sorted = $this->sort($sortFunction);
        $this->data = $sorted;//pourquoi la réalocation ne se fait pas au niveau de la classe parente ?
        return $this;
    }

    public function exportToDateTimeImmutableArray():array{
        $transitionnedResult = [];
        /**
         * @var DateTimeCustomDTO $value
         */
        foreach($this as $value){
            $transitionnedResult[] = $value->exportToDateTimeImmutable();
        }
        return $transitionnedResult;
    }




}