<?PHP
   require_once('lib/site.inc.php');
   require_once('lib/Membership.class.php');
   Membership::SessionStart(array('login'=>'ALLOW'));
   
   $s = site::getSmarty();
   $showform = false;
   $frm = array('org_login'=>'','username' =>'');   
  if (isset($_POST['org_login']) && isset($_POST['username']) && isset($_POST['password'])){
     $showform = false;
     if (($lresult = Membership::Login($_POST['org_login'], $_POST['username'], $_POST['password'])) === true){
        header('Location: main.php');
        exit;
     }else{
        $error = $lresult;
        $s->assign('error', $error);
        $frm = array('org_login'=>$_POST['org_login'], 
                     'username' =>$_POST['username']);
        $showform = true;
     }
  }else{ //user tries to go to this page directly
      $showform = true;
  }
  
  if ($showform){
     $s->assign('frm', $frm);
     $s->display('login.tmpl');
  }
?>