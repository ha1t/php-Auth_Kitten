<?php
require_once dirname(dirname(__FILE__)) . '/Auth/Kitten.php';

$auth = new Auth_Kitten();
$html = $auth->drawImage(basename($_GET['f']));

print($html);
?>
