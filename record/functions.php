<?php
	class transform {
		public function __clone() {}
		
		public static function textMarkup($input) { 
			return iconv("utf-8", "utf-8//ignore", $input);
		}
	}