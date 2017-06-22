<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['site_title'] = 'My site title';
$config['assets_folder'] = '_assets/';

$config['required_css'] = array(
	'Normalize CSS' => 'https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css',
	'Bootstrap'     => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
	'Font awesome'  => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
	'ionicons'      => 'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
	'AdminLTE CSS'  => "{$config['assets_folder']}css/AdminLTE.min.css"
);

$config['required_js'] = array(
	'jQuery'       => 'https://code.jquery.com/jquery-2.2.4.min.js',
	'Bootstrap JS' => "{$config['assets_folder']}js/bootstrap.min.js"
);

$config['optional_css'] = array(
	'datepicker' => "{$config['assets_folder']}plugins/css/datepicker3.css",
	'bs-slider'  => "{$config['assets_folder']}plugins/css/slider.css",
	'select2'    => "{$config['assets_folder']}plugins/css/select2.min.css",
	'icheck'     => "{$config['assets_folder']}plugins/css/icheck.css",
	'timepicker' => "{$config['assets_folder']}plugins/css/bootstrap-timepicker.min.css",
);
$config['optional_js']  = array(
	'datepicker' => "{$config['assets_folder']}plugins/js/bootstrap-datepicker.js",
	'bs-slider'  => "{$config['assets_folder']}plugins/js/bootstrap-slider.js",
	'select2'    => "{$config['assets_folder']}plugins/js/select2.full.min.js",
	'icheck'     => "{$config['assets_folder']}plugins/js/icheck.min.js",
	'timepicker' => "{$config['assets_folder']}plugins/js/bootstrap-timepicker.min.js",
);