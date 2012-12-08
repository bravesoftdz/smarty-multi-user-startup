<?PHP
    require_once(dirname(__FILE__).'/config.inc.php');
    require_once(dirname(__FILE__).'/misc.class.php');
    class site{
       private static $dbh = null;
       public static function getSmarty(){
            include_once(SMARTYDIR.'Smarty.class.php');
            $s = new Smarty();
            $s->template_dir = SMARTYTEMPLATESDIR;
            $s->addPluginsDir(SMARTYPLUGINSDIR);
            $s->compile_dir = SMARTYCOMPILEDTEMPLATESDIR;
            $s->compile_id = defined("SMARTYCOMPILEID") ? SMARTYCOMPILEID : LOGENTRYPREFIX;   
            return $s;        
       }
       public static function getPDO(){
         if (is_null(self::$dbh)){
            self::$dbh = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME,DBUSER,DBPASS,
                   array(PDO::ATTR_PERSISTENT => false)); 
         }
         return self::$dbh;
       }
       
       public static function getDBDateTime(){
         $prep = self::getPDO()->prepare("SELECT NOW()");
         $prep->execute();
         $dbdt = $prep->fetch();      
         return $dbdt[0]; 
       }
       
       public static function getPHPDateTime(){
         $date = new DateTime('NOW'); 
         return $date->format('Y-m-d H:i:s');       
       }
       
       /** 
        * Log an action to the database
        * For example logins, password reset requests, account creations,
        * We can use this for detecting site attacks, detecting different ip addresses per user,
        * frequency of use, etc
        */
       public static function log($type, $message){  
           $session = isset($_SESSION) ? $_SESSION : array();
           
           $s = '[dbdt:'.self::getDBDateTime().', phpdt: '.self::getPHPDateTime().'] - '.$message; 
           $prep = self::getPDO()->prepare("INSERT INTO log (id, ip, loggedin_user_id, loggedin_username, loggedin_email, 
                                                     date_created, type, details)
                                    VALUES (NULL, :ip, :loggedin_user_id, :loggedin_username, :loggedin_email, 
                                    NOW(), :type, :details )");
           $prep->execute(array(':ip'=> misc::val($_SERVER,'REMOTE_ADDR'), ':loggedin_user_id'=> misc::val($session,'user_id'), 
                                ':loggedin_username'=> misc::val($session,'user','username'), 
                                ':loggedin_email'=> misc::val($session,'user','email'), 
                                ':type'=>$type, 
                                ':details'=>$s));
           return self::getPDO()->lastInsertId();
       }
    }
?>