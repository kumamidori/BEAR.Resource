<?php

use BEAR\Resource\AppAdapter;
use BEAR\Resource\SchemeCollection;
use Ray\Di\Injector;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationReader;
use BEAR\Resource\Module\ResourceModule;

$loader = require dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor/autoload.php';

$loader->add('', dirname(__DIR__));
AnnotationRegistry::registerLoader([$loader, 'loadClass']);
AnnotationReader::addGlobalIgnoredName('noinspection'); // for phpStorm

$injector = new Injector(new ResourceModule('sandbox'));
$resource = $injector->getInstance('BEAR\Resource\ResourceInterface');
$scheme = (new SchemeCollection)->scheme('app')->host('self')->toAdapter(new AppAdapter($injector, 'Sandbox', 'Resource'));
$resource->setSchemeCollection($scheme);

/* @var $resource \BEAR\Resource\ResourceInterface */
return $resource;
