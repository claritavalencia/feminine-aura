<?php
header('Content-Type: application/json');

$info = [
    'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'N/A',
    'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? 'N/A',
    'PHP_SELF' => $_SERVER['PHP_SELF'] ?? 'N/A',
    'QUERY_STRING' => $_SERVER['QUERY_STRING'] ?? 'N/A',
    'PATH_INFO' => $_SERVER['PATH_INFO'] ?? 'N/A',
    'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
    'GET_params' => $_GET,
    'htaccess_works' => file_exists(__DIR__ . '/.htaccess') ? 'YES' : 'NO'
];

echo json_encode($info, JSON_PRETTY_PRINT);
