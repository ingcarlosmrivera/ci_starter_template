<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$var = 1;	($var) ? echo ('true') : echo ('false');
	}

}

/* End of file Welcome.php */
/* Location: ./application/controllers/Welcome.php */
