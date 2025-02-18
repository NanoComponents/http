<?php 

namespace Nano\Http\Collections;

use ArrayIterator;
use Countable;
use Nano\Http\Interfaces\ParamInterface;
use Traversable;

class ParamCollection implements \IteratorAggregate, Countable
{
    /** @var array<ParamInterface> */
    private array $paramArray;

    public function add(ParamInterface $paramInterface): self
    {
        $this->paramArray[$paramInterface::class] = $paramInterface;
        return $this;
    }

    public function getFromClass(string $class)
    {
        if (!isset($this->paramArray[$class])) {
            throw new \InvalidArgumentException("Class '$class' is not a valid param class.");
        }
       return $this->paramArray[$class];
    }

    public function getHandlerFor(string $class)
    {
        return $this->getFromClass($class)->getHandler();
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->paramArray);
    }

    public function count(): int
    {
        return \count($this->paramArray);
    }
}
