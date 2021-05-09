<?php

namespace Gateway\Util;

use Error;

abstract class EnvParser
{

    public static function load($filePath)
    {
        $file = file_get_contents($filePath);

        if (!$file) {
            throw new Error("Failed to load env file");
        }
        $lines = preg_split("/\r\n|\n|\r/", $file);

        foreach ($lines as $line) {
            $keyValues = explode('=', $line, 2);
            $key = trim($keyValues[0]);
            $value = trim($keyValues[1]);
            define($key, $value);
        }
    }
}
