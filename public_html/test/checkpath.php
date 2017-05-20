<?php

echo '$_SERVER["DOCUMENT_ROOT"]';
echo '<br>';
echo $_SERVER['DOCUMENT_ROOT'];
echo '<br>';
echo '<br>';


$upOne = realpath($_SERVER['DOCUMENT_ROOT'] . '/..');
echo 'realpath($_SERVER["DOCUMENT_ROOT"] . "/..")';
echo '<br>';
echo $upOne;
echo '<br>';
echo '<br>';

echo "__DIR__";
echo '<br>';
echo __DIR__;
echo '<br>';
echo '<br>';

$upOne = realpath(__DIR__ . '/../..');
echo "realpath(__DIR__ . '/../..')";
echo '<br>';
echo $upOne;

echo '<br>';
echo '<br>';

//Require Config File
require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
echo $config['pbConfig']['paths']['images'];