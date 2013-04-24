<?php
require_once('library/ressf.php');

ob_start();
include('content/text.phtml');
$contents = ob_get_contents();
ob_end_clean();

$ressf = new ressf();
echo $ressf->render($contents);

echo '<hr />';

echo 'Static Call: <br />';
echo 'Desktop: ' . (ressf::isDesktop() ? 'true' : 'false') . '<br />';
echo 'Phone: ' . (ressf::isMobile() ? 'true' : 'false') . '<br />';
?>
