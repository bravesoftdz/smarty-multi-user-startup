<?PHP
/** 
 * A class of miscellaneous static functions that can be called via misc::
 * Sort of a replacement for the old misc.inc with a bunch of functions starting with misc_
 */
class misc
{
       /**
        * Get the array value, use $key2 if nested arrays
        * <code>
        * echo misc::val($_POST, 'foo'); 
        * //is the same as
        * echo isset($_POST['foo']) ? $_POST['foo'] : '';
        * //but shorter =)
        * </code>
        */
       public static function val($ary, $key, $key2 = null){
            $defaultval = '';
            if (!empty($key2)){ return isset($ary[$key][$key2]) ? $ary[$key][$key2] : $defaultval; } 
            else { return isset($ary[$key]) ? $ary[$key] : $defaultval; }
       }
       
       public static function getRandomAlphaNumericString($length = 8) {
          $validCharacters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
          $validCharNumber = strlen($validCharacters);
       
          $result = "";
       
          for ($i = 0; $i < $length; $i++) {
              $index = mt_rand(0, $validCharNumber - 1);
              $result .= $validCharacters[$index];
          }
       
          return $result;
      }
}       
?>