<?php

namespace Nano\Http\Interfaces\Service;

interface StreamInputInterface
{
    public function getRawBody(): string;
    public function toArray(): array;
    public function toJson(int $options = 0): string;
}