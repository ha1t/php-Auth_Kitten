<?php
/**
 * Auth_Kitten example
 *
 */

require_once dirname(dirname(__FILE__)) . '/Auth/Kitten.php';

$kitten = new Auth_Kitten();

if ($_POST['send']) {
    if ($kitten->verify($_POST['kitten'])) {
        print('OK!');
        exit();
    } else {
        $html  = '<strong>Fail!!!!</strong>';
        $html .= $kitten->buildHtml('viewer.php?f=');
    }
} else {
    $html = $kitten->buildHtml('viewer.php?f=');
}
?>
<html>
<head>
<title>Auth_Kitten example</title>
</head>
<body>
<h1>Auth_Kitten example</h1>
<form method="post">
<?php print($html); ?>
<input type="submit" name="send" />
</form>
</body>
</html>
