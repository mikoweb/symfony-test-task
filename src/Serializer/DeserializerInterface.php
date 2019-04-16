<?php

namespace App\Serializer;

interface DeserializerInterface
{
    public function create(array $data): void;
    public function update(array $data): void;
}
