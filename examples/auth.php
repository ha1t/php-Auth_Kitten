<?php
/**
 * Auth_Kitten example
 *
 */

$kitten_dirs = array(
    dirname(dirname(__FILE__)) . '/Auth/Kitten.php',
    dirname(dirname(__FILE__)) . '/src/Auth/Kitten.php',
);

foreach ($kitten_dirs as $path) {
    if (file_exists($path)) {
        require_once $path;
        break;
    }
}

$kitten = new Auth_Kitten();

if (isset($_POST['send'])) {
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
