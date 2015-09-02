<?php
//声明路径是全局的，防止在函数里进行include调用的时候出现访问不到的现象
global $basesupport_root;
$basesupport_root=dirname(__file__);
//实现唯一的方式引入文件
global $api_includes;
if(!isset($api_includes))$api_includes=array();
//session开关
if(isset($session_power) && $session_power)
{
	if(!isset($api_includes['session']))
	{
		if(!isset($_SESSION))session_start();
		include($basesupport_root."/../configuration.php");
		include_once($basesupport_root."/../foundation/fsession.php");
		$api_includes['session']=true;
	}
}
//数据库配置及连接文件 
if(isset($iweb_power) && $iweb_power)
{
	include($basesupport_root."/includes.php");
}
//封装的get和post的方法
if(isset($getpost_power) && $getpost_power)
{
	if(!isset($api_includes['getandpost']))
	{
		include($basesupport_root."/../foundation/fgetandpost.php");
		$api_includes['getandpost']=true;
	}
	
}
//API代理函数
if(!isset($api_includes['api_proxy']))
{
	function Api_Proxy()
	{
		global $basesupport_root;
		
		$args = func_get_args();
		if(count($args)>=1)
		{
			$function=$args[0];
			unset($args[0]);
			if(is_string($function))
			{
				$fun=explode("_",$function);
				if(file_exists($basesupport_root."/$fun[0]/{$fun[0]}_{$fun[1]}.php"))
				{
					include_once($basesupport_root."/$fun[0]/{$fun[0]}_{$fun[1]}.php");
					return  call_user_func_array($function,$args);
				}
			}
		}
		return null;
	}
	$api_includes['api_proxy']=true;
}

if(!isset($api_includes['log_file']))
{
	function LogString($str)
	{
		file_put_contents($log, $str.'\n', FILE_APPEND);
	}

	$api_includes['log_file'] = true;
}

if(!isset($api_includes['weixin_oauth']))
{
	function save_weixin_session($code)
	{
		global $log;

		$wechatObj = new wechat();
		$data = $wechatObj->getWebAccessToken($code);
		if(!empty($data['openid']))
		{
			file_put_contents($log, '\nSuccess Get: '.json_encode($data), FILE_APPEND);
			$wechatObj->addUser($data["access_token"], $data['openid']);
			api_proxy('paper_related_save_user_session', $data['openid']);
		}
	}

	$api_includes['weixin_oauth'] = true;
}

if(!isset($api_includes['calc_distance']))
{
	function calc_distance($lat1, $long1, $lat2, $long2)
	{
		$radLat1 = deg2rad($lat1);
		$radLat2 = deg2rad($lat2);
		$a = $radLat1 - $radLat2;
		$b = deg2rad($long1) - deg2rad($long2);

		$s = 2 * asin(sqrt(pow(sin($a / 2.0), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2.0), 2)));
		$s = $s * 6378.137;
		$s = round($s * 10000) / 10000;

		return $s;
	}

	function calc_all_distance($res)
	{
		$user_id = get_sess_userid();
		$user_lat = get_session("position_y");
		$user_long = get_session("position_x");
		$deal = false;

		$isNull = empty($user_lat) || empty($user_long);

		if(is_array($res))
		{
			if(array_key_exists("user_id", $res))
			{
				if($res["user_id"] == $user_id){
					$res["distance_to_me"] = 0;
					$deal = true;
				}
			}

			if(!$deal && array_key_exists("position_x", $res) && array_key_exists("position_y", $res))
			{
				$x = $res["position_x"];
				$y = $res["position_y"];

				if($isNull || empty($x) || empty($y))
				{
					$res["distance_to_me"] = rand(1000,100000);
					$deal = true;
				}else{
					$res["distance_to_me"] = calc_distance($y, $x, $user_lat, $user_long);
					$deal = true;
				}
			}

			if(!$deal)
			{
				foreach ($res as $key => $value) {
					$isSameUser = false;

					if(array_key_exists("user_id", $value))
					{
						if($value["user_id"] == $user_id)
						{
							$res[$key]["distance_to_me"] = 0;
							$isSameUser = true;
						}
					}
					if(!$isSameUser && array_key_exists("position_x", $value) && array_key_exists("position_y", $value))
					{
						$x = $value["position_x"];
						$y = $value["position_y"];

						if($isNull || empty($x) || empty($y))
							$res[$key]["distance_to_me"] = rand(1000, 100000);
						else
							$res[$key]["distance_to_me"] = calc_distance($y, $x, $user_id, $user_long);
				
					}
				}
			}
		}
		return $res;
	}

	$api_includes['calc_distance'] = true;
}

if(!isset($api_includes['status_tools_func']))
{
	function get_status($status_code)
	{
		if($status_code)
		{
			switch ($status_code) {
				case '0': return '未解决';
					# code...
					break;
				case '1': return '已解决';
					break;
				case '2': return '正在解决';
					break;
				default:  return '已过期';
					# code...
					break;
			}
		}
		return '已过期';
	}

	function get_reply_str($status_code)
	{
		if($status_code)
		{
			switch ($status_code) {
				case '0': return '等待接受';
					# code...
					break;
				
				default: return '已接受';
					# code...
					break;
			}
		}
	}
	$api_includes['status_tools_func'] = true;
}

?>