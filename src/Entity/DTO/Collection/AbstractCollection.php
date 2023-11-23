<?php

namespace App\Entity\DTO\Collection;

use Iterator;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

class AbstractCollection implements Iterator
{

    // Interface Iterator

    // Propriétés
    private int $position=0;
    // Méthodes
    public function current(): mixed
    {
        return $this->data[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }
    public function next(): void
    {
        $this->position++;
    }
    public function rewind(): void
    {
        $this->position = 0;
    }
    public function valid(): bool
    {
        return isset($this->data[$this->position]);
    }


    public function __construct(private array $data,private string $className)
    {
        
    }

    public function getData():array
    {
        return $this->data;
    }

    private function getClassName():string
    {
        return $this->className;
    }


    #[Assert\IsTrue(groups:['motherClass'])]
    private function isOnlyContainingClassNameElements():bool
    {
        foreach($this->data as $element){
            $className = $this->getClassName();
            if($element instanceof $className){
                return false;
            }
        }
        return true;
    }

    private function validateInstanceConformity(mixed $element)
    {
        $classname =$this->getClassName();
        if(!($element instanceof $classname)){
            throw new InvalidArgumentException(sprintf("L'élément passé en paramètre n'est pas du type attendu \"%s\"",$classname));
        }
    }

    private function validateIndexConformity(int $index){
        if($index > count($this->data)-1){
            throw new InvalidArgumentException(sprintf("L'index (%d) fourni n'existe pas",$index));
        }
    }

    public function add(mixed $element):self
    {
        $this->validateInstanceConformity($element);
        $this->data[] = $element;
        return $this;
    }

    public function replace(mixed $element, int $index):self
    {
        $this->validateInstanceConformity($element);
        $this->validateIndexConformity($index);
        $this->data[$index] = $element;
        return $this;
    }

    public function remove(int $index):self
    {
        $this->validateIndexConformity($index);
        unset($this->data[$index]);
        $this->data = array_values($this->data);
        return $this;
    }

    public function sort(callable $compareFunction):array
    {
        usort($this->data,$compareFunction);
        return $this->data;
    }
}
