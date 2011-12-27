<?php
interface Template{
	public static function print_page($content);
	public static function print_http_error($error_code);
}
?>