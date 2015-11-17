<?php

$prefix = implode('/', array_slice(explode('/', dirname(__FILE__)), 0, -1));

require($prefix . '/vendor/autoload.php');

require($prefix . '/tests/lib/AbstractTestCase.php');

require($prefix . '/config/services.php');

require($prefix . '/tests/src/Model/Swagger/TestableSchema.php');