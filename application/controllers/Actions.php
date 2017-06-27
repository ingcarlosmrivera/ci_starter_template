<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Actions extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
	}

	public function email_check()
	{
		if ($this->input->post()) {
			$email = $this->input->post('email', TRUE);
			echo json_encode(!$this->auth->email_check($email));
		} else {
			die(json_encode(array('status' => 'error', 'message' => 'forbidden' )));
		}
	}

	public function email_check_exclude()
	{
		if ($this->input->post()) {
			$email = $this->input->post('email', TRUE);
			echo json_encode($this->auth->email_check_exclude($email));
		} else {
			die(json_encode(array('status' => 'error', 'message' => 'forbidden' )));
		}
	}

}

/* End of file Actions.php */
/* Location: ./application/controllers/Actions.php */