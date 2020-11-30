<?php

// automatically load the Lassie library based on namespace
function lassie_autoload_modules($class)
{
    $prefix = 'Lassie';
    if (strpos($class, $prefix) === false) return;

    $pieces = explode('\\', $class);
    array_shift($pieces);

    // autoload Model instead of Model\*Model
    $modelPrefix = 'Model';
    if (count($pieces) > 1 && strpos($pieces[1], $modelPrefix) !== false)
        $pieces = [$modelPrefix];
    
    $path =  join(DIRECTORY_SEPARATOR, $pieces);
    
    include_once 'lib' . DIRECTORY_SEPARATOR . $path . '.php';

}

spl_autoload_register('lassie_autoload_modules');
