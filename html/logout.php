<?PHP
   require_once('lib/site.inc.php');
   require_once('lib/Membership.class.php');
   $tokens_deleted = Membership::Logout();
   header('Location: index.php'); 
?>