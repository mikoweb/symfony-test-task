<?php

namespace App\Serializer;

final class CollectionSerializer implements SerializerInterface
{
    /**
     * @var array
     */
    private $collection;

    /**
     * @var string
     */
    private $serializerClass;

    public function __construct(array $collection, string $serializerClass)
    {
        $this->collection = $collection;
        $this->serializerClass = $serializerClass;
    }

    public function serialize(): array
    {
        $collection = [];

        foreach ($this->collection as $item) {
            $serializer = new $this->serializerClass($item);
            $collection[] = $serializer->serialize();
        }

        return $collection;
    }
}
