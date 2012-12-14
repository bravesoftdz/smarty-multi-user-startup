<?PHP
define("LOGENTRYPREFIX", "clearbugs");

define("VHOSTDIR", "/<clearbugs-path>/");
define("DOCUMENTROOT", VHOSTDIR . "html/");
define("SKINDIR", DOCUMENTROOT . "lib/skin/");
define("SMARTYDIR", VHOSTDIR . "hidden/lib/smarty/");
define("SMARTYCOMPILEDTEMPLATESDIR", SMARTYDIR . "templates_c/");
define("SMARTYPLUGINSDIR", SMARTYDIR . "plugins/");
define("SMARTYTEMPLATESDIR", SKINDIR . "tmpl/");

define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASS", "<password>");
define("DBNAME", "<db-name>");


define("CRYPT_SALT", "<a-long-salt-string>");

//use a number that your server can handle, the more iterations, the more secure the passwords
define("CRYPT_ITERATIONS", 10);

//how much time before your login token expires (after latest access)
define("LOGIN_EXPIRE_INTERVAL", " INTERVAL 6 HOUR ");
?>