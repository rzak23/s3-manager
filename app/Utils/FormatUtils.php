<?php

namespace App\Utils;

class FormatUtils{
    public static function file_string_object(string $file_path): array|string
    {
        return str_replace('/', '-', $file_path);
    }
}