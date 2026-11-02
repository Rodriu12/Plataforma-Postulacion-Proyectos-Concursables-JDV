<?php

$publicDirectory = realpath(dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'public');
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($publicDirectory !== false && is_string($requestPath)) {
    $requestedFile = realpath(
        $publicDirectory.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, ltrim(rawurldecode($requestPath), '/\\')),
    );

    if (
        $requestedFile !== false
        && str_starts_with($requestedFile, $publicDirectory.DIRECTORY_SEPARATOR)
        && is_file($requestedFile)
    ) {
        return false;
    }
}

require dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'index.php';
