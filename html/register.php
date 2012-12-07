<?PHP
ini_set('display_errors','1');
require_once('lib/site.inc.php');
require_once('lib/Membership.class.php');
$s = site::getSmarty();
if (!empty($_POST)){
   $result = Membership::NewAccount($_POST['plan'], $_POST['name'], $_POST['org_name'], $_POST['email'], $_POST['password']);
   if (is_array($result)){
      $error = $result;
   }else{
      //complete!
   }
   
   if (!empty($error)){ 
       $s->assign('error', $error); 
       $s->assign('registration_complete', false);
   }else{
      $s->assign('registration_complete', true);
   }
}else{
   $s->assign('registration_complete', false);
}

if (true)
{
    $query = site::getPDO()->prepare("SELECT * FROM plans ORDER BY cost DESC");
    $query->execute();
    $plans = $query->fetchAll(PDO::FETCH_ASSOC);
    $s->assign('plans', $plans);
}

// display it
$s->display('register.tmpl');
?>