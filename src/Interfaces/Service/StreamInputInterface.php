<?php

namespace NanoLibs\Http\Interfaces\Service;

interface StreamInputInterface
{
    public function getRawBody(): string;
    /**
     * Summary of toArray
     * @return array<mixed>
     */
    public function toArray(): array;
    public function toJson(int $options = 0): string;
}