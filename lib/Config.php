<?php
namespace Incube\Base;
/** @author incubatio
  * @depandancies Incube\DataObject, \Incube\File_Explorer
  * @licence GPLv3.0 http://www.gnu.org/licenses/gpl.html
  */

class Config {

    /** @param mixed $data 
      * @return array */
    protected function _parse_xml($data) {
        $data = (array) $data;
        foreach($data as $key => $datum) {
            $data[$key] = is_object($datum) || is_array($datum) ? $this->_parse_xml($datum) : trim($datum);
        }
        return $data;
    }

    /** Load a configuration file
      *
      * @param 	string 	$path
      * @param 	bool $assoc
      * @param 	bool $forced_type
      * @return DataObject */
	public function load($path, $assoc = true, $forced_type = false){

		if(FileExplorer::exists($path)) {
			//$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
			$path_parts = pathinfo($path);
			$ext = $forced_type ?  $forced_type : (array_key_exists("extension", $path_parts) ? strtolower($path_parts["extension"]) : "");
            switch ($ext) {
                case 'ini':
                    $data = parse_ini_file($path, true);
                    break;

                case 'ser':
                    $data = unserialize(FileExplorer::read($path));
                    break;

                case 'php':
                    $data =	include_once $path;
                    break;

				//XML has not been concieved for configuration, but for storage
                case 'xml':
                    $data = simplexml_load_file($path);
                    $data = $this->_parse_xml($data);	
                    break;

                case 'swp':
                case 'gitignore':
                case 'dist':
                    break;

                case '':
                    trigger_error(__class__ . " doesn't support file with no extention.");
                    break;

                default:
                    trigger_error(__class__ . " doesn't support $ext filetype.");
				
			}
		}
        if(isset($data)) $data = $assoc ? $data : DataObject::from_array($data);
		return isset($data) ? $data : false;
    }

    /** Load a configuration file
      *
      * @param 	string 	$folder
      * @param 	bool 	$is_assoc
      * @return Incube_Array */
    public function load_by_folder($folder, $is_assoc = true){
        
        $files = FileExplorer::list_files($folder);

        $config = $is_assoc ? array() : new stdClass();
        foreach($files as $file) {
			//PHP5.3 >>
            //$filename = basename($file, '.ini');
            //$filename = basename($file, '.xml');
			//$filename = strtolower(pathinfo($file, PATHINFO_FILENAME));
			//$path_parts = pathinfo($file);
			//$filename = strtolower($path_parts["filename"]);
			$path_parts = pathinfo($file);
			$ext = array_key_exists("extension", $path_parts) ? "." . $path_parts["extension"]: "";
			$filename = basename($file, $ext); 

            if($config_file = $this->load($folder . DIRECTORY_SEPARATOR . $file, $is_assoc)) {
				$is_assoc ? $config[$filename] = $config_file : $config->set($filename, $config_file);
            }
            unset($config_file);
        }

        return $is_assoc ? DataObject::from_array($config) : $config;
    }

	/** Convert recursively arrays to Objects
      *
	  * @param array $assoc 
	  * @return StdClass */
	public function array_to_object(array $assoc) {
		$object = new stdClass();
		foreach($assoc as $key => $value) {
			$key = trim($key);
			$object->$key = (is_array($value)) ? $this->array_to_object($value) : $value; 
		}
		return $object;
	}


	/** Write a file in a specific format to a specific location
      *
	  * @param string $path 
	  * @param array $data */
	public function save($path, array $data) {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		switch($ext) {
			case "ser":
				FileExplorer::write($path, serialize($data));
			break;
			default:
				trigger_error(__class__ . " doesn't support $ext filetype.");
		}
	}
}
?>
