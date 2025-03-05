<?php

namespace Nano\Http\Services\StreamInput;

use JsonException;
use Nano\Http\Interfaces\Service\StreamInputInterface;

class StreamInputService implements StreamInputInterface
{
    private ?string $rawBody = null;
    public function __construct(
        private readonly string $path,
    ) {}

    public function getRawBody(): string
    {
        if ($this->rawBody === null) {
            $content = file_get_contents($this->path);
            $this->rawBody = $content === false ? '' : $content;
        }
        return $this->rawBody;
    }

    /**
     * @param int $options
     * @return string
     * @throws JsonException
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | $options);
    }

    /**
     * @throws JsonException
     */
    public function toArray(): array
    {
        $body = $this->getRawBody();

        if (empty($body)) {
            return [];
        }

        $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($decoded)) {
            throw new JsonException('Invalid JSON format: Expected an array.');
        }

        return $decoded;
    }
}