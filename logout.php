<?php
$page_title="Welcome to KingPins FEC - Home Page";
include_once 'headerfooter/header.php';
include_once 'common/session.php';

session_destroy();
redirectTo("index");
?>