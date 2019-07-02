<?php

    require("vendor/autoload.php");

    $handler = new App\Controllers\ProcessDelivery();


    $notes = file_get_contents('deliveryNotes.json');
    $handler->handleRoute($notes);
    echo PHP_EOL;
