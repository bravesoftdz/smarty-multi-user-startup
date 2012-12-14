<?PHP
require_once('lib/Membership.class.php');
Membership::SessionStart(array('login'=>'FORCE'));

require_once('lib/site.inc.php');
$s = site::getSmarty();
$s->display('main.tmpl');
?>