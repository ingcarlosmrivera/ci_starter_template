<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();
		$this->data = array();

		if ($this->session->userdata('logged')) {
			$this->data['cliente'] = $this->session->userdata('user');
			$this->data['tipo']    = $this->session->userdata('tipo');
		}

		$this->data['css'] = $this->data['js'] = array();
		array_push($this->data['css'], 'cliente_skin');
	}
	public function index()
	{
		redirect('/clientes/login','refresh');
	}

	public function medicos($id_cliente = NULL)
	{
		if (!$this->session->userdata('logged')) {
			redirect('clientes/login','refresh');
		}

		if ($this->session->userdata('tipo') != 'medico') {
			set_message('Solo los médicos tienen acceso.', 'alert-danger');
			redirect('/clientes/dashboard','refresh');
		}

		#variables
		$idmedico = $this->session->userdata('id');

		$this->data['seleccionado'] = $id_cliente;

		if (!is_null($id_cliente)) {
			$this->db->select('idpedido, pedidos.creado, candidato, dni, pedidos.email, vacante, servicios.servicio, pedidos.estado, localidades.localidad as localidad, provincias.provincia as provincia', FALSE);
		    $this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
		    $this->db->join('provincias', 'provincias.idprovincia = pedidos.id_provincia', 'left');
		    $this->db->join('localidades', 'localidades.idlocalidad = pedidos.id_localidad', 'left');
		    $this->db->where('servicios.is_medical', true);
		    $this->db->order_by('idpedido', 'desc');
		    $this->db->limit('100');

			$this->db->where('pedidos.id_cliente', $id_cliente);

		    $pedidos = $this->db->get('pedidos');

		    if ($pedidos->num_rows() > 0) {
		    	$pedidos = $pedidos->result();
		    	$temp = array();

		    	foreach ($pedidos as $p) {
		    		#check adjuntos
					$query = $this->db->get_where('adjuntos', array('id_pedido' => $p->idpedido));
					if ($query->num_rows() > 0) {
						$p->adjuntos = $query->result();
					} else {
						$p->adjuntos = FALSE;
					}
					array_push($temp, $p);
		    	}

		    	$this->data['pedidos'] = $temp;
		    } else {
		    	$this->data['pedidos'] = FALSE;
		    }

		} else {
			$this->data['pedidos'] = FALSE;
		}

		#get clientes autorizados
		$this->db->join('clientes', 'clientes.idcliente = medicos_clientes.id_cliente ', 'left');
		$this->db->where('medicos_clientes.id_medico', $idmedico);
		$this->data['clientes'] = $this->db->get('medicos_clientes')->result();
		$this->data['medico'] = $this->session->userdata('user');



		array_push($this->data['js'], 'jquery_validate');
		$this->data['sidebar_active'] = 'clientes/medico';
		$this->load->view('clientes/medico', $this->data);
	}

	public function pedido($idprepedido = NULL)
	{
		if (!$this->session->userdata('logged')) {
			redirect('clientes/login','refresh');
		}

		if ($this->session->userdata('tipo') != 'subcliente') {
			set_message('Solo los subclientes pueden realizar pedidos.', 'alert-danger');
			redirect('/clientes/dashboard','refresh');
		}

		$this->load->model('general_model');

		#verificar si es duplicado
		if (!is_null($idprepedido) && !$this->data['prepedido'] = $this->general_model->get_prepedido($idprepedido)) {
			show_404();
		}

		if ($this->input->post()) {
			$prepedido = new stdClass();

			$prepedido->id_servicio   = $this->input->post('servicio', TRUE);
			$prepedido->id_cliente    = $this->input->post('id_cliente', TRUE);
			$prepedido->id_subcliente = ($this->session->userdata('tipo') == 'cliente') ? $this->session->userdata('user')->idcliente : $this->session->userdata('user')->idsubcliente;
			$prepedido->id_provincia  = $this->input->post('id_provincia', TRUE);
			$prepedido->id_localidad  = $this->input->post('id_localidad', TRUE);
			$prepedido->candidato     = $this->input->post('candidato', TRUE);
			$prepedido->dni           = $this->input->post('dni', TRUE);
			$prepedido->telefono      = $this->input->post('telefono', TRUE);
			$prepedido->email         = strtolower($this->input->post('email', TRUE));
			$prepedido->vacante       = $this->input->post('vacante', TRUE);
			$prepedido->direccion     = $this->input->post('direccion', TRUE);
			$prepedido->fileurl       = $this->input->post('fileurl', TRUE);
			$prepedido->observaciones = $this->input->post('observaciones', TRUE);

			#check si cliente requiere oc
			if ($this->db->get_where('clientes', array('idcliente' => $prepedido->id_cliente), 1)->row()->forzar_oc) {
				if ($this->input->post('oc', TRUE)) {
					$prepedido->requiere_oc = TRUE;
					$prepedido->oc = $this->input->post('oc', TRUE);
				} else {
					set_message("Este cliente requiere orden de compra.", "alert-danger");
					redirect('/clientes/pedido');
				}				
			}


            $response = $this->general_model->add_prepedido($prepedido);

            set_message('Su pedido se encuentra pendiente de aprobación.', 'alert-info');

            #si se va a crear notificaciones, este es el punto.
		    $this->json_response($response);
		}

		$this->data['sidebar_active'] = 'clientes/pedido';
		$this->data['clientes']       = $this->get_allowed_clientes();
		$this->data['provincias']     = $this->general_model->get_provincias();
		$this->data['js']             = array('select2', 'jquery_validate', 'dropzone');
		array_push($this->data['css'], 'select2');
		array_push($this->data['css'], 'dropzone');
		$this->load->view('clientes/pedido', $this->data);
	}

	public function dashboard()
	{
		if (!$this->session->userdata('logged')) {
			redirect('clientes/login','refresh');
		}

		$this->load->model('general_model');

		$this->data['sidebar_active'] = 'clientes/dashboard';

		$tipo = $this->session->userdata('tipo');
		$id   = ($this->session->userdata('tipo') == 'cliente') ? $this->session->userdata('user')->idcliente : $this->session->userdata('user')->idsubcliente;

		#filtrar por cliente o subcliente segun corresponda
			if ($tipo == 'cliente') {
				$this->db->where('prepedidos.id_cliente', $id);
			} else {
				$this->db->where('prepedidos.id_subcliente', $id);
			}

			$this->db->select('*, clientes.razon as cliente, subclientes.nombre as subcliente, prepedidos.creado as creado', FALSE);
			$this->db->where('procesado', 0);
			$this->db->order_by('idprepedido', 'asc');
			#joins
			$this->db->join('clientes', 'clientes.idcliente = prepedidos.id_cliente', 'left');
			$this->db->join('subclientes', 'subclientes.idsubcliente = prepedidos.id_subcliente', 'left');
			$this->db->join('servicios', 'servicios.idservicio = prepedidos.id_servicio', 'left');
			$this->db->join('provincias', 'provincias.idprovincia = prepedidos.id_provincia', 'left');
			$this->db->join('localidades', 'localidades.idlocalidad = prepedidos.id_localidad', 'left');

			$query = $this->db->get('prepedidos');

			if ($query->num_rows() > 0) {
				$this->data['prepedidos'] =  $query->result();
			} else {
				$this->data['prepedidos'] = FALSE;
			}


			$this->db->select("*, (SELECT provincia FROM provincias WHERE idprovincia = id_provincia) as provincia, (SELECT localidad FROM localidades WHERE idlocalidad = id_localidad) as localidad, (SELECT servicio FROM servicios WHERE idservicio = id_servicio) as servicio, estado, subclientes.nombre as subcliente, clientes.razon as cliente, pedidos.creado as creado");
		    $this->db->from('pedidos');
		    // $this->db->order_by("CASE ESTADO WHEN 'porconfirmar' THEN 1  WHEN 'activado' THEN 2 WHEN 'analisis' THEN 3 END");
		    $this->db->order_by('pedidos.creado', 'desc');
		    $this->db->limit(100);
		    $this->db->where_in('estado', array('porconfirmar', 'activado',	'analisis'));
		    $this->db->join('subclientes', 'subclientes.idsubcliente = pedidos.id_subcliente', 'left');
		    $this->db->join('clientes', 'clientes.idcliente = pedidos.id_cliente', 'left');

	    #filtrar por cliente o subcliente segun corresponda
			if ($tipo == 'cliente') {
				$this->db->where('pedidos.id_cliente', $id);
			} else {
				$this->db->where('pedidos.id_subcliente', $id);
			}

		    $query = $this->db->get();

		    
		    if ($query->num_rows() > 0) {
		      $this->data['pedidos'] = $query->result();
		    } else {
		      $this->data['pedidos'] = FALSE;
		    }

	    $this->db->select("*, (SELECT provincia FROM provincias WHERE idprovincia = id_provincia) as provincia, (SELECT localidad FROM localidades WHERE idlocalidad = id_localidad) as localidad, (SELECT servicio FROM servicios WHERE idservicio = id_servicio) as servicio, estado, subclientes.nombre as subcliente, clientes.razon as cliente, pedidos.creado as creado");
	    $this->db->from('pedidos');
	    $this->db->order_by("pedidos.creado, estado, facturado", "desc");
	    $this->db->limit(100);
	    $this->db->where_in('estado', array('finalizado'));
	    $this->db->join('subclientes', 'subclientes.idsubcliente = pedidos.id_subcliente', 'left');
	    $this->db->join('clientes', 'clientes.idcliente = pedidos.id_cliente', 'left');
	    #filtrar por cliente o subcliente segun corresponda
			if ($tipo == 'cliente') {
				$this->db->where('pedidos.id_cliente', $id);
			} else {
				$this->db->where('pedidos.id_subcliente', $id);
			}

		    $query = $this->db->get();

		    if ($query->num_rows() > 0) {
		      $temp = $query->result();
		      $this->data['finalizados'] = array();

		      foreach ($temp as $t) {
		      	$query = $this->db->get_where('adjuntos', array('adjuntos.id_pedido' => $t->idpedido));
		      	if ($query->num_rows() > 0) {
		      		$t->adjuntos = $query->result();
		      	} else {
		      		$t->adjuntos = false;
		      	}

		      	array_push($this->data['finalizados'], $t);
		      }

		      
		    } else {
		      $this->data['finalizados'] = FALSE;
		    }

	    if ($tipo == 'cliente') {
			$this->data['cotizaciones'] = $this->general_model->get_cotizaciones($id);
		} else {
			$this->data['cotizaciones'] = $this->general_model->get_cotizaciones($this->session->userdata('user')->id_cliente);
		}

	    array_push($this->data['js'], 'jquery_validate');
		$this->load->view('clientes/dashboard', $this->data);

	}

	public function busqueda()
	{
		if (!$this->session->userdata('logged')) {
			redirect('clientes/login','refresh');
		}

		$id   = ($this->session->userdata('tipo') == 'cliente') ? $this->session->userdata('user')->idcliente : $this->session->userdata('user')->idsubcliente;

		$tipo = $this->session->userdata('tipo');

	    $datos = array(
	      'creado'        => $this->input->post('creado'),
	      'dni'           => $this->input->post('dni'),
	      'candidato'     => $this->input->post('candidato'),
	      'email'         => $this->input->post('email'),
	      'vacante'       => $this->input->post('vacante'),
	      'telefono'      => $this->input->post('telefono')
	    );

	    #filtrar por cliente o subcliente segun corresponda
		if ($tipo == 'cliente') {
			$datos['id_cliente'] = $id;
		} else {
			$datos['id_subcliente'] = $id;
		}

	    $datos = array_filter($datos);

	    if (count($datos) > 0) {
	      foreach ($datos as $key => $value) {
	        if ($key != 'id_cliente' && $key != 'id_subcliente') {
	          $this->db->like($key, $value, 'both');
	        }
	      }
	    }

	    $this->db->select('idpedido, pedidos.creado, candidato, dni, pedidos.email, vacante, servicios.servicio, pedidos.estado, localidades.localidad as localidad, provincias.provincia as provincia', FALSE);
	    $this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
	    $this->db->join('provincias', 'provincias.idprovincia = pedidos.id_provincia', 'left');
	    $this->db->join('localidades', 'localidades.idlocalidad = pedidos.id_localidad', 'left');
	    $this->db->order_by('idpedido', 'desc');
	    $this->db->limit('50');
	    #filtrar por cliente o subcliente segun corresponda
		if ($tipo == 'cliente') {
			$this->db->where('pedidos.id_cliente', $id);
		} else {
			$this->db->where('pedidos.id_subcliente', $id);
		}
	    $pedidos = $this->db->get('pedidos');

	    if ($pedidos->num_rows() > 0) {
	    	$pedidos = $pedidos->result();
	    	$temp = array();

	    	foreach ($pedidos as $p) {
	    		#check adjuntos
				$query = $this->db->get_where('adjuntos', array('id_pedido' => $p->idpedido));
				if ($query->num_rows() > 0) {
					$p->adjuntos = $query->result();
				} else {
					$p->adjuntos = FALSE;
				}
				array_push($temp, $p);
	    	}

	    	$pedidos = $temp;
	    } else {
	    	$pedidos = FALSE;
	    }

		$this->data['sidebar_active'] = 'clientes/busqueda';
		$this->data['pedidos']        = $pedidos;
		$this->data['js']             = array('datepicker', 'jquery_validate');
		array_push($this->data['css'], 'datepicker');
		$this->data['datos']          = (object)$datos;

	    $this->load->view('clientes/busqueda_pedidos', $this->data);
	}

	public function facturas($idfactura = NULL)
	{
		if (!$this->session->userdata('logged')) {
			redirect('clientes/login','refresh');
		}

		$tipo = $this->session->userdata('tipo');
		$id   = ($this->session->userdata('tipo') == 'cliente') ? $this->session->userdata('user')->idcliente : $this->session->userdata('user')->idsubcliente;

		if (!is_null($idfactura)) {
			$condition = array(
				'idfactura'  => $idfactura,
				'id_cliente' => ($tipo == 'cliente') ? $id : $this->session->userdata('user')->id_cliente
			);

		    $this->db->select("*, (SELECT COUNT(*) FROM facturas_pedidos WHERE idfactura = id_factura) as numero_pedidos");
			$factura = $this->db->get_where('facturas', $condition, 1);

			if ($factura->num_rows() !== 1) {
				show_404();
			}

			$factura = $factura->row();

			$this->db->select('*, subclientes.nombre as subcliente');
			$this->db->join('pedidos', 'pedidos.idpedido = facturas_pedidos.id_pedido', 'left');
			$this->db->join('subclientes', 'subclientes.idsubcliente = pedidos.id_subcliente', 'left');
			$this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
			$factura->pedidos = $this->db->get_where('facturas_pedidos', array('id_factura' => $factura->idfactura))->result();

			#check adjuntos
			$temp = array();
			foreach ($factura->pedidos as $p) {
				$adjuntos = $this->db->get_where('adjuntos', array('id_pedido' => $p->idpedido));

				if ($adjuntos->num_rows() > 0) {
					$p->adjuntos = $adjuntos->result();
				} else {
					$p->adjuntos = FALSE;
				}
				array_push($temp, $p);
			}

			$factura->pedidos = $temp;
				
			$this->data['sidebar_active'] = 'clientes/facturas';
			$this->data['factura'] = $factura;
			$this->load->view('clientes/detalles_factura', $this->data);
			return;
		}

		$this->data['sidebar_active'] = 'clientes/facturas';


	    $this->db->select("*, (SELECT COUNT(*) FROM facturas_pedidos WHERE idfactura = id_factura) as numero_pedidos");
	    $this->db->from('facturas');
	    $this->db->order_by("idfactura", "desc");
	    $this->db->where(array('id_cliente' => ($tipo == 'cliente') ? $id : $this->session->userdata('user')->id_cliente));
	    $query = $this->db->get();

	    if ($query->num_rows() > 0) {
	     $this->data['facturas'] = $query->result();	      
	    } else {
	      $this->data['facturas'] = FALSE;
	    }


		$this->load->view('clientes/facturas', $this->data);
	}

	public function login()
	{
		#if logged in, redirect
		if ($this->session->userdata('logged')) {
			redirect('/clientes/dashboard','refresh');
		}

		#check if post or new load
		if ($this->input->post()) {
			#login and redirect if ok
			$email = $this->input->post('email', TRUE);
			$password = $this->input->post('password', TRUE);
			$tipo = $this->input->post('tipo', TRUE);

			if ($tipo == 'subcliente') {
				$this->db->join('clientes', 'clientes.idcliente = subclientes.id_cliente', 'left');
				$this->db->where('clientes.cuit', $password);
				$this->db->where('subclientes.email', $email);

				$query = $this->db->get('subclientes', 1);

				if ($query->num_rows() > 0) {
					$c = $query->row();
					$array = array(
						'logged'     => true,
						'tipo'       => 'subcliente',
						'id'         => $c->idsubcliente,
						'user'       => $c,
						'login_date' => date('Y-m-d H:i:s')
					);

					$this->session->set_userdata( $array );
				}
			} elseif ($tipo == 'cliente') {
				$this->db->where('clientes.cuit', $password);
				$this->db->where('clientes.email', $email);

				$query = $this->db->get('clientes', 1);

				if ($query->num_rows() > 0) {
					$c = $query->row();
					$array = array(
						'logged'     => true,
						'tipo'       => 'cliente',
						'id'         => $c->idcliente,
						'user'       => $c,
						'login_date' => date('Y-m-d H:i:s')
					);

					$this->session->set_userdata( $array );
				}
			} elseif ($tipo == 'medico') {
				$this->db->where('medicos.password', $password);
				$this->db->where('medicos.email', $email);

				$query = $this->db->get('medicos', 1);

				if ($query->num_rows() > 0) {
					$c = $query->row();
					$array = array(
						'logged'     => true,
						'tipo'       => 'medico',
						'id'         => $c->idmedico,
						'user'       => $c,
						'login_date' => date('Y-m-d H:i:s')
					);

					$this->session->set_userdata( $array );
					set_message('Sesión iniciada con éxito');
					redirect('/clientes/medicos','refresh');
				}
			}

			if ($this->session->userdata('logged')) {
				set_message('Sesión iniciada con éxito');
				redirect('/clientes/dashboard','refresh');
			} else {
				set_message('No se ha podido iniciar sesión, intente de nuevo', 'alert-danger');
				redirect('/clientes/login','refresh');
			}
		}

		array_push($this->data['css'], 'icheck');
		array_push($this->data['js'], 'icheck');

		$this->load->view('clientes/login', $this->data);
	}

	public function logout()
	{
		$this->session->sess_destroy();

		redirect('clientes/login','refresh');
	}

	private function json_response($success = TRUE)
	{
		if (!$this->session->userdata('logged')) {
			redirect('clientes/login','refresh');
		}

		if ($success) {
	      $response = array(
	        'class' => 'modal fade modal-success',
	        'status' => 'success',
	        'message' => 'Operación completada correctamente',
	        'icon' => 'fa fa-check fa-4x block text-center'
	      );
	    } else {
	      $response = array(
	        'class' => 'modal fade modal-danger',
	        'status' => 'error',
	        'message' => sprintf('%s <br>(Code %s) - %s', 'Ha ocurrido un error al guardar el registro.' ,$this->db->error()['code'], $this->db->error()['message']),
	        'icon' => 'fa fa-close fa-4x block text-center'
	      );
	    }

	    die(json_encode($response));
	}

	private function get_allowed_clientes()
	{
		$ids = array();

		if ($this->session->userdata('tipo') == 'cliente') {
			array_push($ids, ($this->session->userdata('tipo') == 'cliente') ? $this->session->userdata('user')->idcliente : $this->session->userdata('user')->idsubcliente);
		} else {
			array_push($ids, $this->session->userdata('user')->id_cliente);

			$query = $this->db->get_where('subclientes_clientes', array('id_subcliente' => ($this->session->userdata('tipo') == 'cliente') ? $this->session->userdata('user')->idcliente : $this->session->userdata('user')->idsubcliente));

			if ($query->num_rows() > 0) {
				foreach ($query->result() as $r) {
					array_push($ids, $r->id_cliente);
				}
			}
		}

		

		$this->db->where_in('idcliente', $ids);
		$this->db->order_by('razon', 'asc');

		return $this->db->get('clientes')->result();
	}

}

/* End of file Clientes.php */
/* Location: ./application/controllers/Clientes.php */