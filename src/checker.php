<?php

use App\Services\Betfier\LoginChecker\LoginChecker;

include 'vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'This route accept only POST method.']);
    http_response_code(422);
    die();
}

if (empty($_POST['list'])) {
    echo json_encode(['error' => 'Empty list!']);
    http_response_code(422);
    die();
}

$response = LoginChecker::handle($_POST['list']);

echo json_encode($response);