<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
		#ensure auth controller is loaded
	}

	public function email_check_exclude($email)
	{
		$query = $this->db->get_where($this->config->item('tables', 'ion_auth')['users'], array('email' => $email), 1);

		return ($query->num_rows() === 1) ? $query->row() : FALSE;
	}

	#replace native delete_user function
	public function delete_user($id)
	{
		$this->db->delete($this->config->item('tables', 'ion_auth')['users'], array('id' => $id));
		if ($this->db->affected_rows() === 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

/* End of file Auth_model.php */
/* Location: ./application/models/Auth_model.php */