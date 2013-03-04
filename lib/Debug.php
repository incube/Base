<?php
namespace Incube\Base;
/** @author incubatio 
  * @licence GPLv3.0 http://www.gnu.org/licenses/gpl.html
  */
class Debug {

	/** @param mixed $param
	  * @param string $label 
	  * @return string */
	public static function dump($params, $label = ""){
		ob_start();
		var_dump($params);
		//trim(preg_replace("#\=\>\n(\s+)#", " => ",$temp)) . ";\n";
		$output = preg_replace("#\]\=\>\n(\s+)#m", "] => ", ob_get_clean());
		return self::_dump($output, $label);
	}

	/** @param string $text
	  * @param string $label 
	  * @return string */
	protected static function _dump($text, $label="") {
		if ($j = strlen($label)) {
			$label = "|= " . $label . " =\\" .  PHP_EOL;
			for($i=-4; $i<$j; $i++) { 
				$label .= "=="; 
			}
			$text = $label . PHP_EOL . $text;
		}
		echo "<pre>" . $text . "<pre>";
		return $text;
	}

}
