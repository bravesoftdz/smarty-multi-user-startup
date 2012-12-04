<?PHP
ini_set('display_errors','1');
require_once('lib/site.inc.php');
$s = site::getSmarty();
if (!empty($_POST)){
   $s->assign('registration_complete', true);
}else{
   $s->assign('registration_complete', false);
   $query = site::getPDO()->prepare("SELECT * FROM plans ORDER BY cost DESC");
   $query->execute();
   $plans = $query->fetchAll(PDO::FETCH_ASSOC);
   $s->assign('plans', $plans);
}

// display it
$s->display('register.tmpl');
?>