<?php
require '../bootstrap.php';

use HotelBooking\Application;

$app = new Application($dm);
$app->start();