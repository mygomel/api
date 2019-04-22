<?php
/**
 * Created by Maus 12.03.2019 3:57 mygomel@gmail.com
 */

use App\Func;

require __DIR__.'/vendor/autoload.php';

const DEF_TAXI = 'Taxi181'; // default taxi
const PASS = 'dfe24gsdf3'; // default password

Func::no_enter(PASS);

$url = $_SERVER['REQUEST_URI'];
$parts = explode('/', $url);

$ctrl = $parts[1] ? ucfirst($parts[1]) : DEF_TAXI;


$class = '\App\\' . $ctrl;
$ctrl = new $class($parts); // controller