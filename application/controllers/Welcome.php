<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->db->order_by('timestamp', 'desc');
        $query = $this->db->get("ci_sessions");
        $rows = array();
        echo "<pre>";
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
        var_dump($rows);
	}

}

/* End of file Welcome.php */
/* Location: ./application/controllers/Welcome.php */
