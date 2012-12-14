<?PHP
/**
 * This file manages everything validated by verification codes,
 * including initial registration verification, new user email verification, 
 * lost your password verification, change email
 * @see database enum users.verify_action
 */
 
ini_set('display_errors','1');
require_once('lib/site.inc.php');
require_once('lib/Membership.class.php');
$s = site::getSmarty();

//grab verification code
if (!empty($_POST)){



}
else if (!empty($_REQUEST['code'])){
   $sql = "SELECT username, email, verify_code, verify_action, verify_param, verify_date_expires, 
                  organizations.name AS org_name, organizations.login AS org_login
            FROM  `users` JOIN organizations ON users.organization_id = organizations.id
            WHERE verify_code = :code 
            LIMIT 1";
            
    $prep = site::getPDO()->prepare($sql);
    $prep->execute(array(':code' => $_REQUEST['code']));
    $verification = $prep->fetch(PDO::FETCH_ASSOC);
    
    if (!$verification){
       $s->assign('error', array('formfield'=>'verify_code', 'message'=>'Invalid code supplied or account already verified.'));
       $show_pre_verify_form = true;
    }else if (time() > strtotime($verification['verify_date_expires'])){
       $s->assign('error', array('formfield'=>'verify_code', 'message'=>'This verification code has expired, please contact support@clearbugs.com.'));
       $show_pre_verify_form = true;
       site::log('VERIFYATTEMPTEXPIRED', print_r($verification, true));
    }
}else{
   //no code provided
   $s->assign('error', array('formfield'=>'verify_code', 'message'=> 'No code provided. Please enter your verification code'));
   $show_pre_verify_form = true;
}

if (!empty($show_pre_verify_form)){
   $s->display('verify_pre.tmpl');
   exit;
}

  switch($verification['verify_action']){
    case 'initial_email_verification':
       if (!empty($_REQUEST['email'])){
          $sql = "UPDATE users SET email_verified = 1, email = verify_param, 
                        verify_code = '', verify_param = '', 
                        verify_date_expires = NULL 
                  WHERE verify_code = :code AND email = :email AND 24=2";
          $prep = site::getPDO()->prepare($sql);
          if (!$prep->execute(array(':code'=>$verification['verify_code'], 
                               ':email'=>$_REQUEST['email']))){
             $s->assign('error', array('formfield'=>'', 'message'=>'There was an error with our database, please try again later.'));
             site::log('VERIFYUPDATEERROR', print_r(site::getPDO()->errorInfo(), true));
          }else{
             site::log('VERIFYCOMPLETE', print_r($verification, true));
             $s->assign('verification', $verification);
             $s->assign('frm', $verification);
             $s->display('verify_registration.tmpl');
             exit;
          }
       }
    break;
    default:
    $s->assign('error', array('formfield'=>'', 'message'=>'Invalid verification action'));
    site::log('BADVERIFICATIONACTION', print_r($verification, true));
  }
  
  

?>