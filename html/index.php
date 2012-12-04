<?PHP
ini_set('display_errors','1');
require_once('lib/site.inc.php');
$s = site::getSmarty();

$s->assign('name', 'george smith');
$s->assign('address', '45th & Harris');


require_once('lib/Membership.class.php');

$errors = array();
try{
  Membership::CreateOrganization('testorg', 'A Test Organization', 2);
}catch(Exception $e){
  $errors[] = get_class($e).': '.$e->getMessage();  
}


try{
  Membership::CreateUser(1, 'jim', 'bLAhBlAh', 'jim@example.com');
}catch(Exception $e){
  $errors[] = get_class($e).': '.$e->getMessage();  
}

try{
  Membership::CreateUser(1, 'bob', 'blah', 'bob@example.com');
}catch(Exception $e){
  $errors[] = get_class($e).': '.$e->getMessage();   
}

try{
  Membership::CreateUser(1, 'joe', 'bLAhBlAh', 'joeexample.com');
}catch(Exception $e){
  $errors[] = get_class($e).': '.$e->getMessage();  
}

$s->assign('errors', $errors);

// display it
$s->display('index.tmpl');
?>