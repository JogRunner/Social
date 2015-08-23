<?php
	
	//将键值对放入session中
	function save2session($key, $value)
	{
		if(is_null($key) || is_null($value) || !is_string($key)){
			return false;
		}

		//将键值对放入session中
		set_session($key, $value);
		return true;
	}

?>