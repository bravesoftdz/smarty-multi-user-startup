<?PHP
require_once('lib/Membership.class.php');
Membership::SessionStart(array('login'=>'ALLOW'));
$logged_in = Membership::IsLoggedIn();

require_once('lib/site.inc.php');
$s = site::getSmarty();
$s->assign('logged_in', $logged_in);
$s->display('index.tmpl');
?>