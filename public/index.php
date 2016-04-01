<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}, E_ALL);

session_start();

require '../vendor/autoload.php';
try {
    $app = new \Slim\App(\ToyToyToy\Config::get());
    \ToyToyToy\Dependency::apply($app->getContainer());
    \ToyToyToy\Route::registration($app);
    $app->run();
} catch (Throwable $e) {
    var_dump($e);
}
