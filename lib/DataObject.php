<?php
namespace Incube\Base;
/** @author incubatio
  * @licence GPLv3.0 http://www.gnu.org/licenses/gpl.html
  */

use Incube\Base\Pattern\IDataObject;

class DataObject implements IDataObject{

	/** @var array */
	protected $_data;

	/** @param array $data */
	public function __construct(array $data = array()) {
		$this->_data = $data;
	}

	/** @param string $key
	  * return mixed */
	public function get($key) {
		return $this->has($key) ? $this->_data[$key] : null;
	}

	/** @param string $key
	  * @param mixed $value */
	public function set($key, $value) {
		if(is_null($value)) unset($this->_data[$key]);
        else $this->_data[$key] = $value;
        return $this;
	}

	/** @param string $key
	  * return boolean */
	public function has($key) {
		return array_key_exists($key, $this->_data);
	}

	/** @return array */
	public function to_array() {
		$data = array();
		foreach($this->_data as $key => $datum) {
			$data[$key] = ($datum instanceof DataObject) ? $datum->to_array() : $datum; 
		}
		return $data;
	}

	/** Merge mutltiple array 
	  * @params array $data
	  * @return array */
	public static function merge_arrays(array $data) {
		$assoc = array(); 
		foreach($data as $datum) { 
			$assoc = array_merge($assoc, $datum); 
		} 
		return $assoc;
	}

	/** @param array $assoc
	  * @return Incube_Array */
	public static function from_array(array $assoc) {
		$object = new DataObject();
		foreach($assoc as $key => $value) {
			$key = trim($key);
            $value = (is_array($value)) ? self::from_array($value) : $value;
			$object->set($key, $value); 
		}
		return $object;
	}

}
?>
