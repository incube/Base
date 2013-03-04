<?php
namespace Incube\Base\Pattern;
/** @author incubatio 
  * @licence GPLv3.0 http://www.gnu.org/licenses/gpl.html
  */
interface IValidator {

	/** @param string $value 
	  * @return bool */
	public function is_valid($value);
	
}
