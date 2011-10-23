<?php
require_once dirname(dirname(__FILE__)) . '/Auth/Kitten.php';
Auth_Kitten::drawImage(basename($_GET['f']));
