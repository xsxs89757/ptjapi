<?php
	/**
	 * 集合或者json 取字段转为数组
	 */
	if(!function_exists('collect_to_field_array')){
		function collect_to_field_array($collect,String $field){
			if(empty($field))return $collect;
			$array = [];
			foreach($collect as $key=>$value){
				array_push($array, $value[$field]);
			}
			return $array;
		}
	}
	/**
	 * 分析枚举
	 */
	if(!function_exists('parse_config_attr')){
		function parse_config_attr($string) {
			$array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
			if(strpos($string,':')){
				$value  =   array();
				foreach ($array as $val) {
					list($k, $v) = explode(':', $val);
					$value[$k]   = $v;
				}
			}else{
				$value  =   $array;
			}
			return $value;
		}
	}
	/**
	 * 链接参数处理为key
	 */
	if(!function_exists('url_handle_key')){
		function url_handle_key(String $url){
			if(empty($url))return $url;
			$urlArr = str_split($url);
			if(current($urlArr) === '/')unset($urlArr[0]);
			if(end($urlArr) === '/')unset($urlArr[0]);
			return str_replace('/','.',implode('',$urlArr));
		}
	}

	
