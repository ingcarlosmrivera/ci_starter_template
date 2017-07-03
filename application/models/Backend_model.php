<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backend_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		
	}

	public function get_all_user_sessions($limit = 0, $offset = 0)
	{
		$this->db->limit($limit, $offset);
		$this->db->order_by('timestamp', 'desc');
        $query = $this->db->get("ci_sessions");

        $rows = array();

        foreach ($query->result() as $row)
        {   
            $row->data = explode(';', $row->data);
            $row->userdata = array();

            foreach ($row->data as $data) {
            	if (strpos($data, '|')) {
            		$data = explode('|', $data);
            		$row->userdata[$data[0]] = @unserialize($data[1].';');
            	}
            }
            unset($row->data);
            array_push($rows, $row);
        } 
        return $rows;
	}

	public function kick_session($id)
	{
		return $this->db->delete('ci_sessions', array('id' => $id));
	}

}

/* End of file Backend_model.php */
/* Location: ./application/models/Backend_model.php */