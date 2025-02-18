<?php

include 'vendor/autoload.php';

use Nano\Http\Param\QueryParam;
use Nano\Http\Request;

$request = Request::initializeGlobals();
$res = $request->getQuery();

echo '<pre>';
var_dump($res->invalid === null);
echo '<pre/>';