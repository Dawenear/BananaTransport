<?php

if (isset($_POST['jsonFile'])) {
    require("vendor/autoload.php");

    $handler = new App\Controllers\ProcessDelivery();


    $notes = file_get_contents($_POST['jsonFile']);
    $handler->handleRoute($notes);
} else {
    echo "No file uploaded";
}