<?php
require_once('engine/render.php');

ob_start();
include('views/sample.phtml');
$contents = ob_get_contents();
ob_end_clean();

$ressf = new ressf();
echo $ressf->render($contents);

?>