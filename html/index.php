<?PHP
ini_set('display_errors','1');
require_once('lib/site.inc.php');
$s = site::getSmarty();

// display it 
$s->display('index.tmpl');
?>