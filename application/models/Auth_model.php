<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
		#ensure auth controller is loaded
	}

	public function count_users()
	{
		return $this->db->count_all($this->config->item('tables', 'ion_auth')['users']);
	}

	public function count_groups()
	{
		return $this->db->count_all($this->config->item('tables', 'ion_auth')['groups']);
	}

	public function count_db_sessions()
	{
		return $this->db->count_all('ci_sessions');
	}

	public function get_group($id = NULL)
	{
		$group = $this->ion_auth_model->group($id)->row();

		$this->db->join($this->config->item('tables', 'ion_auth')['users_groups'], $this->config->item('tables', 'ion_auth')['users_groups'].'.group_id = ' . $this->config->item('tables', 'ion_auth')['groups'].'.id', 'left');
		$this->db->where($this->config->item('tables', 'ion_auth')['users_groups'].'.group_id', $group->id);
		$this->db->from($this->config->item('tables', 'ion_auth')['groups']);
		$group->number_users = $this->db->count_all_results();
		#put group permissions
		$this->db->join('group_permissions', 'group_permissions.permission_id = permissions.id', 'left');
		$this->db->order_by('position', 'asc');
		$this->db->order_by('permission', 'asc');
		$permissions = $this->db->get_where('permissions', array('group_id' => $group->id));
		
		$group->permissions =  ($permissions->num_rows() > 0) ? $permissions->result() : array();
		
		return $group;
	}

	public function get_groups($limit = 0, $offset = 0)
	{
		$groups = $this->ion_auth_model->limit($limit)->offset($offset)->groups()->result();
		$temp = array();

		foreach ($groups as $group) {
			#count users on group
			$this->db->join($this->config->item('tables', 'ion_auth')['users_groups'], $this->config->item('tables', 'ion_auth')['users_groups'].'.group_id = ' . $this->config->item('tables', 'ion_auth')['groups'].'.id', 'left');
			$this->db->where($this->config->item('tables', 'ion_auth')['users_groups'].'.group_id', $group->id);
			$this->db->from($this->config->item('tables', 'ion_auth')['groups']);
			$group->number_users = $this->db->count_all_results();
			#put group permissions
			$this->db->join('group_permissions', 'group_permissions.permission_id = permissions.id', 'left');
			$this->db->order_by('position', 'asc');
			$this->db->order_by('permission', 'asc');
			$permissions = $this->db->get_where('permissions', array('group_id' => $group->id));

			$group->permissions =  ($permissions->num_rows() > 0) ? $permissions->result() : array();

			array_push($temp, $group);
		}

		return $temp;
	}

	public function get_permissions_list()
	{
		return $this->db->order_by('position', 'ASC')->order_by('permission', 'ASC')->get('permissions')->result();
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

	public function group_has_permission($group_id, $permission)
	{
		#with this function we will check if the module uri is added at least in one row, if number rows is 0, then module is not secured.
		$this->db->join('group_permissions', 'group_permissions.permission_id = permissions.id', 'left');
		$this->db->where('permissions.permission', $permission);
		$this->db->where('group_permissions.group_id', $group_id);
		return ($this->db->get_where('permissions', array('group_permissions.group_id' => $group_id, 'permissions.permission' => $permission), 1)->num_rows() === 1) ? TRUE : FALSE;

	}

}

/* End of file Auth_model.php */
/* Location: ./application/models/Auth_model.php */