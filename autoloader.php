<?php
// autoloader.php

spl_autoload_register(function ($class) 
{
   
    // 1. Define your base directory
    $base_dir = __DIR__ . '/';

    // 2. Replace namespace backslashes with directory separators
    //    e.g., App\Models\User -> App/Models/User
    $relative_class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // 3. Construct the full path to the file
    $file = $base_dir . $relative_class . '.php';

    // 4. If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
});