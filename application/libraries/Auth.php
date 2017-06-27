<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'Ion_auth.php';

class Auth extends Ion_auth
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->library('ion_auth', 'ion_auth');
	}

	public function get_user($id = NULL)
	{
		$user = (isset($id)) ? $this->user($id)->row() : $this->user()->row();
		$user->groups = $this->get_user_groups($user->id);

		return $user;
	}

	public function get_users($group_id = NULL)
	{
		$users = (isset($group_id)) ? $this->users($group_id)->result() : $this->users()->result();

		$temp = array();

		foreach ($users as $user) {
			$user->groups = $this->get_user_groups($user->id);
			array_push($temp, $user);
		}

		return $temp;
	}

	public function get_user_groups($id = NULL)
	{
		return (isset($id)) ? $this->get_users_groups($id)->result() : $this->get_users_groups()->result();
	}

	public function get_group($id = NULL)
	{
		$group = $this->group($id)->row();

			$this->db->join($this->config->item('tables', 'ion_auth')['users_groups'], $this->config->item('tables', 'ion_auth')['users_groups'].'.group_id = ' . $this->config->item('tables', 'ion_auth')['groups'].'.id', 'left');
			$this->db->where($this->config->item('tables', 'ion_auth')['users_groups'].'.group_id', $group->id);
			$this->db->from($this->config->item('tables', 'ion_auth')['groups']);
			$group->number_users = $this->db->count_all_results();
		
		return $group;
	}

	public function get_groups()
	{
		$groups = $this->groups()->result();
		$temp = array();

		foreach ($groups as $group) {
			$this->db->join($this->config->item('tables', 'ion_auth')['users_groups'], $this->config->item('tables', 'ion_auth')['users_groups'].'.group_id = ' . $this->config->item('tables', 'ion_auth')['groups'].'.id', 'left');
			$this->db->where($this->config->item('tables', 'ion_auth')['users_groups'].'.group_id', $group->id);
			$this->db->from($this->config->item('tables', 'ion_auth')['groups']);
			$group->number_users = $this->db->count_all_results();
			array_push($temp, $group);
		}

		return $temp;
	}


	public function get_auth_messages()
	{
		if (!empty($this->errors())) {
			$this->set_error_delimiters("<h4 class='no-margin text-center'>", "</h4>");
			return $this->errors();
		}

		if (!empty($this->messages())) {
			$this->set_message_delimiters("<h4 class='no-margin text-center'>", "</h4>");
			return $this->messages();
		}
	}

	public function forgotten_password($identity)    //changed $email to $identity
	{
		if ( $this->ion_auth_model->forgotten_password($identity) )   //changed
		{
			// Get user information
     		$identifier = $this->ion_auth_model->identity_column; // use model identity column, so it can be overridden in a controller
      		$user = $this->where($identifier, $identity)->where('active', 1)->users()->row();  // changed to get_user_by_identity from email

			if ($user)
			{
				$data = array(
					'identity'		=> $user->{$this->config->item('identity', 'ion_auth')},
					'forgotten_password_code' => $user->forgotten_password_code
				);

				if(!$this->config->item('use_ci_email', 'ion_auth'))
				{
					$this->set_message('forgot_password_successful');
					return $data;
				}
				else
				{
					$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth'), $data, true);
					$this->email->clear();
					$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
					$this->email->to($user->email);
					$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
					$this->email->message($message);

					if ($this->email->send())
					{
						$this->set_message('forgot_password_successful');
						return TRUE;
					}
					else
					{
						$this->set_error('forgot_password_unsuccessful');
						return FALSE;
					}
				}
			}
			else
			{
				$this->set_error('forgot_password_unsuccessful');
				return FALSE;
			}
		}
		else
		{
			$this->set_error('forgot_password_unsuccessful');
			return FALSE;
		}
	}

	public function delete_user($id)
	{
		#cant delete logged account
		if ($id === $this->get_user()->id) {
			#same logged account can't be deleted
			$this->auth->set_error('delete_unsuccessful');
			return FALSE;
		}

		if ($response = $this->auth_model->delete_user($id)) {
			$this->set_message('delete_successful');
		} else {
			$this->set_error('delete_unsuccessful');
		}

		return $response;

	}

	#this is for se in form_validation
	public function email_check_exclude($email)
	{
		$response = $this->auth_model->email_check_exclude($email);

		if (!$response) {
			return TRUE;
		} else {
			#check if is the same user, this always need a POST to be performed
			$id = $this->input->post('user_id', TRUE);
			return ($id == $response->id) ? TRUE : FALSE;
		}
	}
}