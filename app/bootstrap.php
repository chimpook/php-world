<?php
// Load Config
require_once 'config/config.php';

// Load helpers
require_once 'helpers/std.php';

// Autoload core libraries
spl_autoload_register(function($className) {
    require_once 'libraries/' . $className . '.php';
});
