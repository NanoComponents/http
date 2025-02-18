<?php

namespace Nano\Http\Handlers;


class FileParamHandler extends BaseHandler
{
    /**
     * Return True if the file is included in $_FILES
     * @return bool
     */
    public function isFileExists(string $file): bool
    {
        return \array_key_exists($file, $this->getAll());
    }
}