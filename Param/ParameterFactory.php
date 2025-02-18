<?php

namespace Nano\Http\Param;

use Nano\Http\Collections\ParamCollection;
use Nano\Http\Interfaces\ParameterFactoryInterface;
use Nano\Http\Param\CookieParam;
use Nano\Http\Param\FileParam;
use Nano\Http\Param\FormParam;
use Nano\Http\Param\QueryParam;
use Nano\Http\Param\ServerParam;

class ParameterFactory implements ParameterFactoryInterface{

    public static function getDefaults(): self
    {
        return new self;
    }

    public function createParamCollection(): ParamCollection
    {
        return (new ParamCollection)
            ->add($this->createQueryParam())
            ->add($this->createFormParam())
            ->add($this->createServerParam())
            ->add($this->createCookieParam())
            ->add($this->createFileParam());
    }

    protected function createQueryParam(): QueryParam
    {
        return new QueryParam($_GET);
    }

    protected function createFormParam(): FormParam
    {
        return new FormParam($_POST);
    }

    protected function createServerParam(): ServerParam
    {
        return new ServerParam($_SERVER);
    }

    protected function createCookieParam(): CookieParam
    {
        return new CookieParam($_COOKIE);
    }

    protected function createFileParam(): FileParam
    {
        return new FileParam($_FILES);
    }
}