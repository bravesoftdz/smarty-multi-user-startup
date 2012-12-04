<?PHP
    require_once(dirname(__FILE__).'/config.inc.php');
    require_once(dirname(__FILE__).'/site.inc.php');
    
    class MembershipException extends Exception{ public $field = false; };
            class EmailInvalidException extends MembershipException{};
            class PlanMaxUsersExceededException extends MembershipException{};
            class OrganizationInvalidException extends MembershipException{};
            class UsernameInvalidException extends MembershipException{};
            class UsernameTakenException extends MembershipException{};
            class EmailTakenException extends MembershipException{};
            class InvalidPasswordException extends MembershipException{};
            
            class OrgLoginInvalidException extends MembershipException{};
            class PlanDoesNotExistException extends MembershipException{};
            class CreateOrgDBException extends MembershipException{};

    class Membership{
       private static function HashPassword($password){
            $hash = crypt($password, CRYPT_SALT);
            for ($i = 0; $i < CRYPT_ITERATIONS; ++$i)
            {
                $hash = crypt($hash . $password, CRYPT_SALT);
            }
            return $hash;
       }
       
       public static function CreateOrganization($org_login, $org_name, $plan_shortname_or_id)
       {
            if (!preg_match('/\\A[a-z][_a-z\\d]+[a-z\\d]\\z/i', $org_login)) {
               throw new OrgLoginInvalidException('Organization login must start with a letter, end with a letter or digit, and contain letters, digits, or underscores in between.');
            } 
            
            //find plan
            if (is_numeric($plan_shortname_or_id)){
                $sql = 'SELECT * FROM plans WHERE id = :plan_id LIMIT 1';
                $prep = site::getPDO()->prepare($sql);
                $prep->execute(array(':plan_id' => (int)$plan_shortname_or_id));
            }else{
                $sql = 'SELECT * FROM plans WHERE shortname = :shortname LIMIT 1';
                $prep = site::getPDO()->prepare($sql);
                $prep->execute(array(':shortname' => ($plan_shortname_or_id));              
            }
             
            $plan = $prep->fetch();
            if (empty($plan)){
               throw PlanDoesNotExistException($plan_id.' is not a valid plan');
            }
            $date_expires = null;
            if ($plan['charge_cycle'] == 'yearly'){
               $date_expires = date("Y-m-d H:i:s", strtotime('+1 year'));
            }
            $sql = 'INSERT INTO organizations (login, name, plan_id, date_created, date_expires) VALUES (:login, :name, :plan_id, NOW(), :date_expires)';
            $prep = site::getPDO()->prepare($sql);
            if (!$prep->execute(array(':login'=>$org_login, ':name'=>$org_name,':plan_id' => (int)$plan_id, ':date_expires'=>$date_expires))){
              $err = $prep->errorInfo();
              throw new CreateOrgDBException($err[2]);
            }
       }
       
       public static function CreateUser($org_id, $username, $password, $email){
                $org_id = (int)$org_id;
                $sql = 'SELECT * FROM organizations JOIN plans ON organizations.plan_id = plans.id WHERE organizations.id = :org_id LIMIT 1';
                $prep = site::getPDO()->prepare($sql);
                $prep->execute(array(':org_id' => $org_id));
                $orgs = $prep->fetchAll();
                if (empty($orgs)){
                   throw new OrganizationInvalidException();
                }
                $org = $orgs[0];
                $sql = 'SELECT COUNT(*) FROM users WHERE organization_id = :org_id';
                $prep = site::getPDO()->prepare($sql);
                $prep->execute(array(':org_id' => $org_id));
                $count = $prep->fetch();
                $count = $count['COUNT(*)'];
                
                if ($org['max_users'] > $count + 1){
                   throw new PlanMaxUsersExceededException($org['max_users']);
                }           
                if (!preg_match('/\\A[a-z][_a-z\\d]+[a-z\\d]\\z/i', $username)) {
                   throw new UsernameInvalidException('Username must start with a letter, end with a letter or digit, and contain letters, digits, or underscores in between.');
                }
                if (strpos($username, '__') !== false){
                   throw new UsernameInvalidException('Username cannot contain 2 underscores in a row');
                }
                if (strlen($username) < 3){
                   throw new UsernameInvalidException('Username is less than 3 characters');
                }else if (strlen($username) > 15){
                   throw new UsernameInvalidException('Username is more than 15 characters');
                }
                
                if (!strpos($email, '@') >= 1){
                   throw new EmailInvalidException();
                }
                
                $sql = 'SELECT username FROM users WHERE LOWER(username) = LOWER(:username) AND organization_id = :org_id';
                $prep = site::getPDO()->prepare($sql);
                $prep->execute(array(':username' => $username, ':org_id'=> $org_id));
                $users = $prep->fetchAll();
                
                if (!empty($users)){ throw new UsernameTakenException(); }
                
                $sql = 'SELECT email FROM users WHERE LOWER(email) = LOWER(:email) AND organization_id = :org_id';
                $prep = site::getPDO()->prepare($sql);
                $prep->execute(array(':email' => $email, ':org_id'=> $org_id));
                $users = $prep->fetchAll();
                
                if (!empty($users)){ throw new EmailTakenException(); }
                
                if (preg_match('/\\A[^\\s]{6,18}\\z/', $password)) {
                   throw new InvalidPasswordException('Password must be between 6 and 18 characters and contain no spaces');
                }
                //ok if it got here, email and username are valid
        }
    }
?>