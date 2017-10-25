<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente
{
	private $ci;
	private $idcliente;
	private $razon;
	private $email_cliente;
	private $cuit_cliente;
	private $id;
	private $nombre;
	private $telefono_subcliente;
	private $email_subcliente;

	private $logged = FALSE;

	function __construct()
	{
		$this->ci =& get_instance();
		if ($this->ci->session->userdata('logged') && ($this->ci->session->userdata('tipo') == 'cliente' || $this->ci->session->userdata('tipo') == 'subcliente')) {

			$this->id = $this->ci->session->userdata('id');

			if ($this->ci->session->userdata('tipo') == 'cliente') {
				$this->ci->db->where('clientes.idcliente', $this->id);
				$this->ci->db->limit(1);

				$subcliente = $this->ci->db->get('clientes')->row();

				foreach ($subcliente as $key => $value) {
					$this->$key = $value;
				}

				$this->logged = TRUE;
			} else {
				$this->ci->db->select('clientes.idcliente, clientes.razon, clientes.email as email_cliente, clientes.cuit as cuit_cliente, subclientes.nombre as razon, subclientes.telefono as telefono, subclientes.email as email');
				$this->ci->db->join('clientes', 'clientes.idcliente = subclientes.id_cliente', 'left');
				$this->ci->db->where('subclientes.idsubcliente', $this->id);
				$this->ci->db->limit(1);

				$subcliente = $this->ci->db->get('subclientes')->row();

				foreach ($subcliente as $key => $value) {
					$this->$key = $value;
				}

				$this->logged = TRUE;
			}

				
		}
	}

	public function is_logged()
	{
		return $this->logged;
	}

	public function login($email, $cuit, $tipo)
	{
		if ($tipo == 'cliente') {
			$this->ci->db->where('clientes.cuit', $cuit);
			$this->ci->db->where('clientes.email', $email);

			$query = $this->ci->db->get('clientes', 1);

			if ($query->num_rows() > 0) {
				$c = $query->row();
				$array = array(
					'logged' => true,
					'tipo' => 'cliente',
					'id' => $c->idcliente,
					'login_date' => date('Y-m-d H:i:s')
				);

				$this->ci->session->set_userdata( $array );

				return TRUE;
			}
		} else {
			$this->ci->db->join('clientes', 'clientes.idcliente = subclientes.id_cliente', 'left');
			$this->ci->db->where('clientes.cuit', $cuit);
			$this->ci->db->where('subclientes.email', $email);

			$query = $this->ci->db->get('subclientes', 1);

			if ($query->num_rows() > 0) {
				$c = $query->row();
				$array = array(
					'logged' => true,
					'tipo' => 'subcliente',
					'id' => $c->idsubcliente,
					'login_date' => date('Y-m-d H:i:s')
				);

				$this->ci->session->set_userdata( $array );

				return TRUE;
			}
		}
			

		return FALSE;
	}

	public function get_allowed_clientes()
	{
		$ids = array();
		array_push($ids, $this->idcliente);

		$query = $this->ci->db->get_where('subclientes_clientes', array('id_subcliente' => $this->id));

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $r) {
				array_push($ids, $r->id_cliente);
			}
		}

		$this->ci->db->where_in('idcliente', $ids);
		$this->ci->db->order_by('razon', 'asc');
		return $this->ci->db->get('clientes')->result();
	}

	public function is_cliente()
	{
		return ($this->ci->session->userdata('tipo') == 'cliente') ? true : false;
	}

	public function _get($key)
	{
		return (isset($this->$key)) ? $this->$key : '';
	}

}