<?PHP
    require_once(dirname(__FILE__).'/config.inc.php');
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
    }
?>