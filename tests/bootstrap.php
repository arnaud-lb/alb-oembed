<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

require_once __DIR__ . '/../vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespaces(array(
    'Alb\\OEmbed' => __DIR__ . '/../lib',
));
$loader->register();

