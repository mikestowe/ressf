<?php
require_once('library/ressf.php');

ob_start();
include('content/text.phtml');
$contents = ob_get_contents();
ob_end_clean();

$ressf = new ressf();
echo $ressf->render($contents);

?>
