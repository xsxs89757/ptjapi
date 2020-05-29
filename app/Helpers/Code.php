<?php

	namespace App\Helpers;

	class Code
	{
		/**
		 * static status
		 * @var array
		 */
		public static $statusTexts = [
			40001 => 'xxxxxx1',
			50001 => 'xxxxxx2'
		];

		/**
		 * @param    code
		 * @return   message
		 */
		public static function getStatusText($code=null){
			if($code && self::$statusTexts[$code]){
				return self::$statusTexts[$code];
			}
			
		}
	}
