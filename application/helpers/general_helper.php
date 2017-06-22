<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_site_title()
{
	$CI =& get_instance();

	return $CI->config->item('site_title');
}

function get_css($optional = FALSE)
{
	$CI =& get_instance();

	#get css from config
	$required_css = $CI->config->item('required_css');

	if (is_array($required_css) && count($required_css) > 0) {
		#print css
		foreach ($required_css as $key => $value) {
			$value = (strpos($value, 'http') !== FALSE) ? $value : site_url() . $value;
			echo "<!-- $key -->" . PHP_EOL;
	  		echo "<link rel='stylesheet' href='$value'>" . PHP_EOL;
		}
	}

	#check if optional css is required
	if ($optional) {
		#get js from config
		$optional_css = $CI->config->item('optional_css');

		if (is_array($optional_css) && is_array($optional) && count($optional_css) > 0) {
			foreach ($optional as $key => $value) {
				if (array_key_exists($value, $optional_css)) {
					$optional_css[$value] = (strpos($optional_css[$value], 'http') !== FALSE) ? $optional_css[$value] : site_url() . $optional_css[$value];
					echo "<!-- {optional file} -->" . PHP_EOL;
	  				echo "<link rel='stylesheet' href='$optional_css[$value]'>" . PHP_EOL;
				} else {
					echo "<!-- $value file not found in array -->" . PHP_EOL;
				}
			}
		}
	}
}

function get_js($optional = FALSE)
{
	$CI =& get_instance();

	#get css from config
	$required_js = $CI->config->item('required_js');

	if (is_array($required_js) && count($required_js) > 0) {
		#print js
		foreach ($required_js as $key => $value) {
			$value = (strpos($value, 'http') !== FALSE) ? $value : site_url() . $value;
			echo "<!-- $key -->" . PHP_EOL;
  			echo "<script src='$value'></script>" . PHP_EOL;
		}
	}

	#check if optional js is required
	if ($optional) {
		#get js from config
		$optional_js = $CI->config->item('optional_js');

		if (is_array($optional_js) && is_array($optional) && count($optional_js) > 0) {
			foreach ($optional as $key => $value) {
				if (array_key_exists($value, $optional_js)) {
					$optional_js[$value] = (strpos($optional_js[$value], 'http') !== FALSE) ? $optional_js[$value] : site_url() . $optional_js[$value];
					echo "<!-- {optional file} -->" . PHP_EOL;
		  			echo "<script src='$optional_js[$value]'></script>" . PHP_EOL;
				} else {
					echo "<!-- $value file not found in array -->" . PHP_EOL;
				}
			}
		}
	}
}

function dump_and_die($param, $pre = TRUE)
{
	if ($pre) {
		echo "<pre>";
	}

	var_dump($param);

	if ($pre) {
		echo "</pre>";
	}
}