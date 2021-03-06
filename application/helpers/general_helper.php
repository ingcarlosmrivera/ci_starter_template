<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_site_title()
{
	$CI =& get_instance();

	return $CI->config->item('site_title');
}

function get_site_name()
{
	$CI =& get_instance();

	return $CI->config->item('site_name');
}

function get_user()
{
	$CI =& get_instance();

	return $CI->auth->get_user();
}

function get_random_contextual_class()
{
	$class = array('default', 'info', 'success', 'warning', 'danger');
	return $class[rand(0,4)];
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

	die();
}

function set_message($message = "", $alert_class = 'alert-success')
{
	$CI =& get_instance();

	$m = new stdClass();
	#si es array, asume message para mensaje y alert-class para la clase, esto para utilizar mejor el auth->get_auth_messages()

	if (isset($message['message']) && isset($message['alert_class'])) {
		
		$alert_class = $message['alert_class'];
		$message = $message['message'];
		
	}

	$m->alert_class = $alert_class;
	$m->message     = $message;

	$CI->session->set_flashdata('message', $m);
}

function print_alert_temp($time = 3000, $bar_class = 'success', $alert_class = 'info', $message = '')
{
	$template =  "<div class='alert-temp' data-time='%s'>
		<div class='progress progress-temp no-margin-bottom'>
			<div class='progress-bar progress-bar-%s' role='progressbar' aria-valuemin='0' aria-valuemax='100'>
			</div>
		</div>
		<div class='alert alert-%s'>
			<h4 class='no-margin'>%s</h4>
		</div>
	</div>";

	return sprintf($template, $time, $bar_class, $alert_class, $message);
}

function date_($date)
{
	$CI =& get_instance();
	return date($CI->config->item('date_format'), $date);
}