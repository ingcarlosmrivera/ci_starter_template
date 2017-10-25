<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['site_title'] = 'Consultar RRHH';
$config['site_name']  = 'Consultar - RRHH';
$config['assets_folder'] = '_assets/';
$config['company'] = array(
	'nombre'    => 'Consultar H&S S.A.',
	'slogan'    => 'Tercerización de Procesos de RRHH',
	'cuit'      => '30 71244688 5',
	'direccion' => 'Perú 345 12 C CABA',
	'zip'       => 'CP 1067',
	'telefono'  => '+54 11 5238 2404',
	'email'     => 'consultar@consultar-rrhh.com'
);

$config['required_css'] = array(
	'Normalize CSS'   => 'https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css',
	'Bootstrap'       => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
	'Font awesome'    => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
	'ionicons'        => 'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
	'AdminLTE CSS'    => "{$config['assets_folder']}css/AdminLTE.min.css",
	'Skin'            => "{$config['assets_folder']}css/skins/skin-blue.min.css",
	'izitoast'        => "{$config['assets_folder']}plugins/css/izitoast.css",
	'daterangepicker' => "{$config['assets_folder']}plugins/css/daterangepicker.css",
);

$config['required_js'] = array(
	'jQuery'          => 'https://code.jquery.com/jquery-2.2.4.min.js',
	'Bootstrap JS'    => "{$config['assets_folder']}js/bootstrap.min.js",
	'App JS'          => "{$config['assets_folder']}js/app.min.js",
	'izitoast'        => "{$config['assets_folder']}plugins/js/izitoast.js",
	'moment'          => "{$config['assets_folder']}plugins/js/moment.min.js",
	'daterangepicker' => "{$config['assets_folder']}plugins/js/daterangepicker.js",
);

$config['optional_css'] = array(
	'datepicker'   => "{$config['assets_folder']}plugins/css/datepicker3.css",
	'bs-slider'    => "{$config['assets_folder']}plugins/css/slider.css",
	'select2'      => "{$config['assets_folder']}plugins/css/select2.min.css",
	'dropzone'     => "{$config['assets_folder']}plugins/css/dropzone.min.css",
	'icheck'       => "{$config['assets_folder']}plugins/css/icheck.css",
	'timepicker'   => "{$config['assets_folder']}plugins/css/bootstrap-timepicker.min.css",
	'cliente_skin' => "{$config['assets_folder']}css/skins/skin-purple.min.css",
);
$config['optional_js']  = array(
	'datepicker' => "{$config['assets_folder']}plugins/js/bootstrap-datepicker.js",
	'bs-slider'  => "{$config['assets_folder']}plugins/js/bootstrap-slider.js",
	'select2'    => "{$config['assets_folder']}plugins/js/select2.full.min.js",
	'dropzone'   => "{$config['assets_folder']}plugins/js/dropzone.min.js",
	'icheck'     => "{$config['assets_folder']}plugins/js/icheck.min.js",
	'timepicker' => "{$config['assets_folder']}plugins/js/bootstrap-timepicker.min.js",
	'jquery_validate' => 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js',
	'canvasjs'		  => "{$config['assets_folder']}plugins/js/canvasjs.min.js"
);

$config['date_format'] = 'd/m/Y h:i A';