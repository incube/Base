<?php
namespace Incube\Base\Pattern;
/** @author incubatio
  * @licence GPLv3.0 http://www.gnu.org/licenses/gpl.html
  */
interface IUri {

	/** @param string $key */
    public function get_param($key);

	public function get_main_params();

	public function get_content_type();
}
