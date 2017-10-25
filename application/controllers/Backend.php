<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backend extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('backend_model');

		if (!$this->auth->logged_in() && $this->uri->segment(2) !== "login") {
			redirect('backend/login','refresh');
		}

		$this->data['js'] = array();
		$this->data['css'] = array();
	}

	public function dashboard()
	{
		#check admin session
		$this->check_session();

		$this->data['sidebar_active'] = 'dashboard';
		array_push($this->data['js'], 'canvasjs');
		$this->load->view('backend/dashboard', $this->data);
	}

	public function index()
	{
		$this->login();
	}

	public function login()
	{
		#if logged in, redirect
		if ($this->auth->logged_in()) {
			redirect('/backend/dashboard','refresh');
		}

		#check if post or new load
		if ($this->input->post()) {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', "trim|required|min_length[{$this->config->item('min_password_length', 'ion_auth')}]|max_length[{$this->config->item('max_password_length', 'ion_auth')}]");

			if ($this->form_validation->run() !== FALSE) {
				#login and redirect if ok
				$email = $this->input->post('email', TRUE);
				$password = $this->input->post('password', TRUE);
				$remember = ($this->input->post('remember', TRUE)) ? $this->input->post('remember', TRUE) : FALSE;

				
				if ($this->auth->login($email, $password, $remember)) {
					set_message($this->auth->get_auth_messages());
					redirect('/backend/dashboard','refresh');
				} else {
					set_message($this->auth->get_auth_messages());
					redirect('/backend/login','refresh');
				}
			}
		}

		array_push($this->data['css'], 'icheck');
		array_push($this->data['js'], 'icheck');

		$this->load->view('backend/login', $this->data);
	}

	public function logout()
	{
		$this->auth->logout();
		redirect('/backend/login','refresh');
	}

	public function actions($action = NULL, $param1= NULL)
	{
		switch ($action) {
			case 'restore_password':
				if ($this->input->post()) {
					$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

					if ($this->form_validation->run() !== FALSE) {
						$email = $this->input->post('email', TRUE);

						#if ajax print json response
						if ($this->input->is_ajax_request()) {
							$response = array();

							if ($this->auth->forgotten_password($email)) {
								$response['status']  = 'success';
								$response['message'] = $this->auth->get_auth_messages();
							}
							else {
								$response['status']  = 'error';
								$response['message'] = $this->auth->get_auth_messages();
							}

							echo json_encode($response);
							exit();
						}

						#if not ajax send and redirect
						$this->auth->forgotten_password($email);
						set_message($this->auth->get_auth_messages());
						redirect('/backend/actions/restore_password', 'refresh');							
					}
						
				}

				$this->load->view('backend/restore_password', $this->data);
					

				break;

			case 'regenerate_password':

				if (!$param1) {
					show_404();
				}

				if ($this->data['user'] = $this->auth->forgotten_password_check($param1)) {
					$this->form_validation->set_rules('password', 'Contraseña', 'required|trim|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_repeat]');
					$this->form_validation->set_rules('password_repeat', 'Repetir nueva contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
					

					if ($this->form_validation->run() !== FALSE) {
						$email = $this->data['user']->email;
						set_message($this->auth->get_auth_messages());

						if ($this->auth->reset_password($email, $this->input->post('password'))) {
							redirect("backend/login", 'refresh');
						} else {
							redirect("backend/actions/restore_password", 'refresh');
						}
					}
				} else {
					// if the code is invalid then send them back to the forgot password page
					set_message($this->auth->get_auth_messages());
					redirect("backend/actions/restore_password", 'refresh');
				}

				$this->load->view('backend/regenerate_password', $this->data);
				break;
			
			default:
				# code...
				break;
		}
	}

	public function users($action = "list", $param1 = NULL, $param2 = NULL)
	{
		$this->check_session();
		$this->data['sidebar_active'] = 'users/';

		if (!$this->auth->is_admin()) {
			redirect('/backend/dashboard','refresh');
		}

		switch ($action) {
			case 'list':
				// if (!$this->auth->check_permission('list_users')) {
				// 	show_404();
				// }

				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 10; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(4);
				$this->data['users']   = $this->auth->get_users(NULL, $per_page, $current_page);
				
				$config['base_url']    = base_url().'backend/users/list/';
				$config['total_rows']  = $this->auth->count_users();//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
		        
				$this->load->view('backend/users/list', $this->data);
				break;

			case 'add':
				#check post request
				if ($this->input->post()) {
					$this->form_validation->set_rules('first_name', 'Nombre', "required|trim|max_length[50]");
					$this->form_validation->set_rules('last_name', 'Apellido', "required|trim|max_length[50]");
					$this->form_validation->set_rules('email', 'Email', "required|trim|max_length[50]|is_unique[{$this->config->item('tables', 'ion_auth')['users']}.email]");
					$this->form_validation->set_rules('password', 'Contraseña', "required|trim|min_length[{$this->config->item('min_password_length', 'ion_auth')}]|max_length[{$this->config->item('max_password_length', 'ion_auth')}]");
					$this->form_validation->set_rules('password_repeat', 'Repetir contraseña', "required|trim|min_length[{$this->config->item('min_password_length', 'ion_auth')}]|max_length[{$this->config->item('max_password_length', 'ion_auth')}]");
					$this->form_validation->set_rules('groups[]', 'Roles', "required");


					if ($this->form_validation->run() !== FALSE) {
						$email    = strtolower($this->input->post('email'));
			            $password = $this->input->post('password');
			            $groups   = array();
			            foreach ($this->input->post('groups', TRUE) as $key => $value) {
			            	array_push($groups, $value);
			            }

			            $additional_data = array(
			                'first_name' => $this->input->post('first_name'),
			                'last_name'  => $this->input->post('last_name')
			            );

			            #at this point, if register is success or not we have to set message and redirect. So...
			            $this->auth->register($email, $password, $email, $additional_data, $groups);
			            #set message and redirect
			            set_message($this->auth->get_auth_messages());
			            redirect("/backend/users/add", 'refresh');
					}
				}

				$this->data['js'] = array('jquery_validate', 'select2');
				$this->data['css'] = array('select2');
				$this->data['groups'] = $this->auth->get_groups();
				$this->load->view('backend/users/add', $this->data);
				break;

			case 'edit':
				#just in case
				$param1 = (int) $param1;
				if (is_null($param1)) {
					die('data not received');
				}

				#check post request
				if ($this->input->post()) {
					$this->form_validation->set_rules('user_id', 'ID de usuario', "required|trim");
					$this->form_validation->set_rules('first_name', 'Nombre', "required|trim|max_length[50]");
					$this->form_validation->set_rules('last_name', 'Apellido', "required|trim|max_length[50]");
					$this->form_validation->set_rules('groups[]', 'Grupos', "required");
					$this->form_validation->set_rules(
						'email', 'Email', 
						array(
							'required',
							'trim',
							'max_length[50]',
							array($this->auth, "email_check_exclude")
						)
					);

					#if password is submitted check and apply validation
					if ($this->input->post('password', TRUE)) {
						$this->form_validation->set_rules('password', 'Contraseña', "required|trim|min_length[{$this->config->item('min_password_length', 'ion_auth')}]|max_length[{$this->config->item('max_password_length', 'ion_auth')}]");
						$this->form_validation->set_rules('password_repeat', 'Repetir contraseña', "required|trim|min_length[{$this->config->item('min_password_length', 'ion_auth')}]|max_length[{$this->config->item('max_password_length', 'ion_auth')}]");
					}


					if ($this->form_validation->run() !== FALSE) {
						$user_id = $this->input->post('user_id', TRUE);
						$email    = strtolower($this->input->post('email'));
			            $groups   = $this->input->post('groups', TRUE);
			            $data = array(
							'first_name' => $this->input->post('first_name'),
							'last_name'  => $this->input->post('last_name'),
							'email'      => $email
						);

						// update the password if it was posted
						if ($this->input->post('password'))
						{
							$data['password'] = $this->input->post('password');
						}

						// Only allow updating groups if user is admin
						if ($this->auth->is_admin())
						{
							//Update the groups user belongs to
							if (isset($groups) && !empty($groups)) {

								$this->auth->remove_from_group('', $param1);

								foreach ($groups as $group) {
									$this->auth->add_to_group($group, $param1);
								}

							}
						}

						// check to see if we are updating the user
						$this->auth->update($user_id, $data);
						redirect("/backend/users/edit/{$user_id}", 'refresh');

					}
				}

				$this->data['js'] = array('jquery_validate', 'select2');
				$this->data['css'] = array('select2');
				$this->data['groups'] = $this->auth->get_groups();
				$this->data['user'] = $this->auth->get_user($param1);
				$this->load->view('backend/users/edit', $this->data);
				break;

			case 'status':
				#just in case
				$param1 = (int) $param1;
				if (is_null($param1)) {
					die('data not received');
				}

				$user = $this->auth->get_user($param1);
				($user->active == 1) ? $this->auth->deactivate($param1) : $this->auth->activate($param1);
				set_message($this->auth->get_auth_messages());
				$this->load->library('user_agent');
				redirect($this->agent->referrer(),'refresh');
				break;

			case 'delete':
				#process delete
				$result = $this->auth->delete_user($param1);
				set_message($this->auth->get_auth_messages());

				($result) ? redirect('/backend/users/list','refresh') : redirect("/backend/users/edit/$param1",'refresh');
				break;

			case 'session_manager':
				if (!is_null($param2)) {
					$this->backend_model->kick_session($param2);
					set_message('Session kicked!', 'alert-success');
					redirect('/backend/users/session_manager','refresh');
				}

				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page               = 10; //Número de registros mostrados por páginas
				$current_page           = $this->uri->segment(4);
				$this->data['sessions'] = $this->backend_model->get_all_user_sessions($per_page, $current_page);
				
				$config['base_url']     = base_url().'backend/users/session_manager/';
				$config['total_rows']   = $this->auth->count_db_sessions();//calcula el número de filas  
				$config['per_page']     = $per_page; //Número de registros mostrados por páginas
				$config['num_links']    = 2; //Número de links mostrados en la paginación
				$config["uri_segment"]  = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
		        
				$this->load->view('backend/users/session_manager', $this->data);
				break;
				
			
			default:
				# code...
				break;
		}
	}

	public function groups($action = "list", $param1 = NULL)
	{
		$this->check_session();
		$this->data['sidebar_active'] = 'groups/';

		if (!$this->auth->is_admin()) {
			redirect('/backend/dashboard','refresh');
		}

		switch ($action) {
			case 'list':
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 10; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(4);
				$this->data['groups']  = $this->auth->get_groups($per_page, $current_page);
				
				$config['base_url']    = base_url().'backend/groups/list/';
				$config['total_rows']  = $this->auth->count_groups();//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
		        
				$this->load->view('backend/groups/list', $this->data);
				break;

			case 'add':
				#check post request
				if ($this->input->post()) {
					$this->form_validation->set_rules('name', 'Nombre', "required|trim|max_length[20]");
					$this->form_validation->set_rules('description', 'Descripción', "required|trim|max_length[100]");
					$this->form_validation->set_rules('permissions[]', 'Permisos', "required|trim");

					if ($this->form_validation->run() !== FALSE) {
						$name = $this->input->post('name', TRUE);
						$description = $this->input->post('description', TRUE);
			            #at this point, if register is success or not we have to set message and redirect. So...
			            $group_id = $this->auth->create_group($name, $description);
			            #set permissions
						$this->auth->set_group_permissions($this->input->post('permissions', TRUE), $group_id);
			            #set message and redirect
			            set_message($this->auth->get_auth_messages());
			            redirect("/backend/groups/add", 'refresh');
					}
				}

				$this->data['js'] = array('jquery_validate', 'select2');
				$this->data['css'] = array('select2');
				$this->data['permissions'] = $this->auth->get_permissions_list();
				$this->load->view('backend/groups/add', $this->data);
				break;

			case 'edit':
				#just in case
				$param1 = (int) $param1;
				if (is_null($param1)) {
					die('data not received');
				}

				#check post request
				if ($this->input->post()) {
					$this->form_validation->set_rules('name', 'Nombre', "required|trim|max_length[20]");
					$this->form_validation->set_rules('description', 'Descripción', "required|trim|max_length[100]");
					$this->form_validation->set_rules('permissions[]', 'Permisos', "required|trim");

					if ($this->form_validation->run() !== FALSE) {
						$group_id = $this->input->post('group_id', TRUE);
						$name    = strtolower($this->input->post('name'));
						$description    = strtolower($this->input->post('description'));

						// check to see if we are updating the user
						$this->auth->update_group($group_id, $name, $description);
						#set permissions
						$this->auth->set_group_permissions($this->input->post('permissions', TRUE), $group_id);

						set_message($this->auth->get_auth_messages());
						redirect("/backend/groups/edit/{$group_id}", 'refresh');

					}
				}

				$this->data['js'] = array('jquery_validate', 'select2');
				$this->data['css'] = array('select2');
				$this->data['group'] = $this->auth->get_group($param1);
				$this->data['permissions'] = $this->auth->get_permissions_list();
				$this->load->view('backend/groups/edit', $this->data);
				break;

			case 'delete':
				#process delete
				$result = $this->auth->delete_group($param1);
				set_message($this->auth->get_auth_messages());

				($result) ? redirect('/backend/groups/list','refresh') : redirect("/backend/groups/edit/$param1",'refresh');
				break;				
			
			default:
				# code...
				break;
		}
	}



	public function ordenes($action = "list", $offset = 0)
	{
		$this->load->model('general_model');

		if (!$this->auth->is_admin()) {
			redirect('/backend/dashboard','refresh');
		}

		switch ($action) {
			case 'detalles':

				$this->data['sidebar_active'] = 'ordenes/detalles';
				$this->data['js'] = array('jquery_validate');
				$this->data['css'] = array();

				if ($this->input->post()) {
					$this->data['fechas']  = new stdClass();
					$this->data['fechas']->fecha1 = $this->input->post('fecha1', TRUE);
					$this->data['fechas']->fecha2 = $this->input->post('fecha2', TRUE);
				} else {
					$this->db->select_min('creado', 'fecha1');
					$this->db->select_max('creado', 'fecha2');
					$this->db->from('pedidos');
					$this->data['fechas'] = $this->db->get()->row();
				}

				$this->load->model('detalles');
				$this->data['n'] =  $this->detalles->get_detalles_activos_pasivos($this->data['fechas']->fecha1, $this->data['fechas']->fecha2);



				$this->load->view('backend/ordenes/detalles', $this->data);

				break;
			case 'list':

				if ($this->input->post('idorden', TRUE)) {
					#veriicar si se paga o factura
					if ($this->input->post('fechapago', TRUE)) {
						$data = new stdClass();
						$data->pagada = true;
						$data->fecha_pago = $this->input->post('fechapago', TRUE);

						$this->db->update('ordenes_compra', $data, array('idorden' => $this->input->post('idorden', TRUE)));
					} elseif ($this->input->post('fechafacturada', TRUE)) {
						$data = new stdClass();
						$data->facturada = true;
						$data->fecha_facturada = $this->input->post('fechafacturada', TRUE);
						$data->numero_factura = $this->input->post('numero_factura', TRUE);

						$this->db->update('ordenes_compra', $data, array('idorden' => $this->input->post('idorden', TRUE)));
					}
						

					set_message('Operación realizada correctamente');
					$this->json_response(TRUE);
				}
					
				#parámetros de búsqueda
				$this->data['ordenar_por']          = 'ordenes_compra.fecha'; #siempre
				$this->data['buscar_por']           = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'ordenes_compra.idorden';
				$this->data['busqueda']             = $this->input->post('text');
				$this->data['ordenes_fecha_inicio'] = ($this->input->post('ordenes_fecha_inicio')) ?: $this->db->select_min('fecha', 'fecha')->get('ordenes_compra')->row()->fecha;
				$this->data['ordenes_fecha_fin']    = ($this->input->post('ordenes_fecha_fin')) ?: $this->db->select_max('fecha', 'fecha')->get('ordenes_compra')->row()->fecha;
				$this->data['pagada']               = ($this->input->post('pagada')) ?: NULL;
				$this->data['facturada']            = (!empty($this->input->post('facturada')) ) ?: NULL;


				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 200; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(4);
				$this->data['ordenes']    = $this->general_model->search_ordenes($this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page, $this->data['ordenes_fecha_inicio'], $this->data['ordenes_fecha_fin'], $this->data['facturada'], $this->data['pagada']);
				// die($this->db->last_query());

				$config['base_url']    = base_url().'backend/ordenes/list/';
				$config['total_rows']  = $this->general_model->count_ordenes($this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenes_fecha_inicio'], $this->data['ordenes_fecha_fin'], $this->data['facturada'], $this->data['pagada']);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
				
				if ($this->session->flashdata('temp')) {
					$this->data['montos'] = $this->session->flashdata('temp');
				}

				if ($this->input->post('download', TRUE) == 'on') {
					$v = $this->general_model->search_ordenes($this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], 0, 0, $this->data['ordenes_fecha_inicio'], $this->data['ordenes_fecha_fin'], $this->data['facturada'], $this->data['pagada']);

					pedidos_to_excel($v);
				}
				$this->data['sidebar_active'] = 'ordenes/list';
				array_push($this->data['js'], 'jquery_validate');
				array_push($this->data['js'], 'dropzone');
				array_push($this->data['css'], 'dropzone');
				$this->load->view('backend/ordenes/list', $this->data);
				break;

			case 'add':

				if ($this->input->post()) {
					#ajax para generar orden
					$id_proveedor = $this->input->post('idproveedor', TRUE);
					$idpedidos = $this->input->post('pedidos', TRUE);

					#obtener pedidos con id recibido y ordenado = 0
					$this->db->where('ordenado', 0);
					$this->db->where_in('idpedido', $idpedidos);

					$pedidos = $this->db->get('pedidos')->result();

					#totalizar
					$total_orden = 0;
					foreach ($pedidos as $pedido) {
						$total_orden += $pedido->costo;
					}

					#guardar data de orden y generar PDF
					$orden = array(
						'id_proveedor' => $id_proveedor,
						'total_orden'  =>$total_orden
					);

					$this->db->insert('ordenes_compra', $orden);
					#get idorden
					$id_orden = $this->db->insert_id();
					#guardar pedidos relacionados
					foreach ($idpedidos as $p) {
						$data = array(
							'id_pedido' => $p,
							'id_orden' => $id_orden
						);

						$this->db->insert('ordenes_pedidos', $data);
					}

					#marcar pedidos como facturados
					$this->db->where_in('idpedido', $idpedidos);
					$this->db->update('pedidos', array('ordenado' => 1));

					#si se va a enviar mail al proveedor, este es el sitio

					set_message("Orden de compra generada correctamente. Puedes verla <a href='/backend/orden_pdf/$id_orden' target='_blank'>aquí</a>", 'alert-success');
					$this->json_response(TRUE);

				}
								
				$this->data['sidebar_active'] = 'ordenes/add';
				$this->data['js'] = array('select2', 'jquery_validate');
				$this->data['css'] = array('select2');
				$this->load->view('backend/ordenes/add', $this->data);
				break;

			case 'consultar':
					
				#parámetros de búsqueda
				$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
				$this->data['id_proveedor']  = ($this->input->post('id_proveedor')) ? $this->input->post('id_proveedor') : 'facturas.numero_factura';
				$this->data['fecha1']    = $this->input->post('text');
				$this->data['fecha2']    = $this->input->post('text2');

				if ($this->input->post()) {
					$this->data['pedidos']    = $this->general_model->search_pedidos_proveedor($this->data['fecha1'], $this->data['fecha2'], $this->data['id_proveedor'], $this->data['ordenar_por']);
					$this->data['proveedor'] = $this->general_model->get_proveedor($this->data['id_proveedor']);
				} else {
					$this->data['pedidos']    = FALSE;
					$this->data['proveedor'] = FALSE;
				}
				
				$this->data['sidebar_active'] = 'ordenes/consultar';
				$this->data['js'] = array('select2', 'jquery_validate', 'datepicker');
				$this->data['css'] = array('select2', 'datepicker');

				$this->load->view('backend/ordenes/consultar', $this->data);
				break;

			default:
				# code
				break;
		}
	}

	public function facturacion($action = "list", $offset = 0)
	{
		$this->load->model('general_model');

		if (!$this->auth->is_admin()) {
			redirect('/backend/dashboard','refresh');
		}

		switch ($action) {
			case 'list':

				if ($this->input->post('idfactura', TRUE)) {
					#pagar factura
					$data = new stdClass();
					$data->pagada = true;
					$data->fecha_pago = $this->input->post('fechapago', TRUE);

					$this->db->update('facturas', $data, array('idfactura' => $this->input->post('idfactura', TRUE)));

					set_message('Operación realizada correctamente');
					$this->json_response(TRUE);
				}
					
				#parámetros de búsqueda
				$this->data['ordenar_por'] = 'facturas.fecha'; #siempre
				$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'facturas.numero_factura';
				$this->data['busqueda']    = $this->input->post('text');
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 200; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(4);
				$this->data['facturas']    = $this->general_model->search_facturas($this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page);

				$config['base_url']    = base_url().'backend/facturacion/list/';
				$config['total_rows']  = $this->general_model->count_facturas($this->data['busqueda'], $this->data['buscar_por']);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
				
				
				$this->data['sidebar_active'] = 'facturacion/list';
				array_push($this->data['js'], 'jquery_validate');
				array_push($this->data['js'], 'dropzone');
				array_push($this->data['css'], 'dropzone');
				$this->load->view('backend/facturacion/list', $this->data);
				break;

			case 'add':

				if ($this->input->post()) {
					#ajax para generar factura
					$id_cliente = $this->input->post('idcliente', TRUE);
					$factura = $this->input->post('factura', TRUE);
					$idpedidos = $this->input->post('pedidos', TRUE);

					#obtener pedidos con id recibido y facturado = 0
					$this->db->where('facturado', 0);
					$this->db->where_in('idpedido', $idpedidos);

					$pedidos = $this->db->get('pedidos')->result();

					#totalizar
					$total_factura = 0;
					foreach ($pedidos as $pedido) {
						$total_factura += $pedido->precio;
					}

					#guardar data de factura y generar PDF
					$factura = array(
						'id_cliente'     => $id_cliente,
						'numero_factura' => $factura,
						'total_factura'  =>$total_factura
					);

					$this->db->insert('facturas', $factura);
					#get idfactura
					$id_factura = $this->db->insert_id();
					#guardar pedidos relacionados
					foreach ($idpedidos as $p) {
						$data = array(
							'id_pedido' => $p,
							'id_factura' => $id_factura
						);

						$this->db->insert('facturas_pedidos', $data);
					}

					#marcar pedidos como facturados
					$this->db->where_in('idpedido', $idpedidos);
					$this->db->update('pedidos', array('facturado' => 1));

					#si se va a enviar mail al cliente, este es el sitio

					set_message("Factura generada correctamente. Puedes verla <a href='/backend/factura_pdf/$id_factura' target='_blank'>aquí</a>", 'alert-success');
					$this->json_response(TRUE);

				}


				$this->data['pedidos_fecha_inicio'] = $this->db->select_min('creado', 'creado')->where('facturado', FALSE)->get('pedidos')->row()->creado;
				$this->data['pedidos_fecha_fin']    = $this->db->select_max('creado', 'creado')->where('facturado', FALSE)->get('pedidos')->row()->creado;			
				$this->data['sidebar_active'] = 'facturacion/add';
				$this->data['js'] = array('select2', 'jquery_validate');
				$this->data['css'] = array('select2');
				$this->load->view('backend/facturacion/add', $this->data);
				break;

			case 'consultar':
					
				#parámetros de búsqueda
				$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
				$this->data['id_proveedor']  = ($this->input->post('id_proveedor')) ? $this->input->post('id_proveedor') : 'facturas.numero_factura';
				$this->data['fecha1']    = $this->input->post('text');
				$this->data['fecha2']    = $this->input->post('text2');

				if ($this->input->post()) {
					$this->data['pedidos']    = $this->general_model->search_pedidos_proveedor($this->data['fecha1'], $this->data['fecha2'], $this->data['id_proveedor'], $this->data['ordenar_por']);
					$this->data['proveedor'] = $this->general_model->get_proveedor($this->data['id_proveedor']);
				} else {
					$this->data['pedidos']    = FALSE;
					$this->data['proveedor'] = FALSE;
				}
				
				$this->data['sidebar_active'] = 'facturacion/consultar';
				$this->data['js'] = array('select2', 'jquery_validate', 'datepicker');
				$this->data['css'] = array('select2', 'datepicker');

				$this->load->view('backend/facturacion/consultar', $this->data);
				break;

			case 'gastos':
				if ($this->input->post()) {
					if ($this->input->post('pagando', TRUE)) {
						$gasto = new stdClass();
						$gasto->pagado     = TRUE;
						$gasto->fecha_pago = $this->input->post('fecha_pago', TRUE);

						$this->db->update('gastos', $gasto, array('idgasto' => $this->input->post('idgasto', TRUE)));

						redirect('/backend/facturacion/gastos','refresh');
						
					} elseif ($this->input->post('fecha', TRUE))  {
						$gasto = new stdClass();

						if ($im = $this->input->post('paste', TRUE)) {
							$image = imagecreatefromstring(base64_decode($im));
							$filename = '/uploads/' . "adjunto_".time().".jpg";

							if (imagejpeg($image, FCPATH . $filename, 75)) {
								$gasto->adjunto = $filename;
							}

						} else {
							if ($_FILES AND $_FILES['file']['name']) {
								$config['upload_path']          = "./uploads/";
								$config['allowed_types']        = '*';
								$config['max_size']             = 10240;
								$config['file_name']            = "adjunto_".time();
								$this->load->library('upload', $config);

								if ( ! $this->upload->do_upload('file')){
									set_message('Error cargando archivo, intente de nuevo', 'alert-danger');
									redirect('/backend/facturacion/gastos','refresh');
								} 

								$gasto->adjunto = '/uploads/' . $this->upload->data()['file_name'];

							}
						}


						$gasto->fecha         = $this->input->post('fecha', TRUE);
						$gasto->concepto      = $this->input->post('concepto', TRUE);
						$gasto->total         = $this->input->post('total', TRUE);
						$gasto->observaciones = $this->input->post('observaciones', TRUE);
						$gasto->cuit 		  = ($this->input->post('cuit', TRUE)) ?: '-';


						if ($this->input->post('pagado', TRUE) == 'on') {
							$gasto->pagado     = TRUE;
							$gasto->fecha_pago = $this->input->post('fecha_pago', TRUE);
						}
							

						$this->general_model->add_gasto($gasto);

						redirect('/backend/facturacion/gastos','refresh');
					}
				}

				#parámetros de búsqueda
				$this->data['ordenar_por']          = 'gastos.idgasto'; #siempre
				$this->data['buscar_por']           = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'gastos.cuit';
				$this->data['busqueda']             = $this->input->post('text');
				$this->data['ordenes_fecha_inicio'] = ($this->input->post('ordenes_fecha_inicio')) ?: $this->db->select_min('fecha', 'fecha')->get('gastos')->row()->fecha;
				$this->data['ordenes_fecha_fin']    = ($this->input->post('ordenes_fecha_fin')) ?: $this->db->select_max('fecha', 'fecha')->get('gastos')->row()->fecha;
				$this->data['pagado']               = ($this->input->post('pagado')) ?: NULL;

				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 10; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(4);
				$this->data['gastos']    = $this->general_model->search_gastos($this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenes_fecha_inicio'], $this->data['ordenes_fecha_fin'], $this->data['pagado'], $this->data['ordenar_por'], $per_page, $current_page);

				$config['base_url']    = base_url().'backend/facturacion/gastos/';
				$config['total_rows']  = $this->general_model->count_gastos($this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenes_fecha_inicio'], $this->data['ordenes_fecha_fin'], $this->data['pagado']);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
				
				$this->data['sidebar_active'] = 'facturacion/gastos';
				$this->data['js'] = array('jquery_validate');
				$this->load->view('backend/facturacion/gastos', $this->data);
				break;

			case 'edit':

				if (!$this->data['proveedor'] = $this->general_model->get_proveedor($offset)) {
					show_404();
				}

				#check if post
				if ($this->input->post()) {
					$user_id = $this->input->post('idproveedor', TRUE);
					$email    = strtolower($this->input->post('email'));
		            $data = array(
						'email'        => $this->input->post('email'),
						'razon'        => $this->input->post('razon'),
						'cbu'          => $this->input->post('cbu'),
						'cuenta'       => $this->input->post('cuenta'),
						'cuit'         => $this->input->post('cuit'),
						'banco'        => $this->input->post('banco'),
						'telefono'     => $this->input->post('telefono'),
						'domicilio'    => $this->input->post('domicilio'),
						'id_provincia' => $this->input->post('id_provincia'),
						'id_localidad' => $this->input->post('id_localidad'),
		            );

					// update the password if it was posted
					if ($this->input->post('password'))
					{
						$data['password'] = $this->input->post('password');
					}

					// check to see if we are updating the user
					$this->auth->update($user_id, $data);
					redirect("/backend/proveedores/edit/{$user_id}", 'refresh');
				}

				$this->data['js'] = array('jquery_validate');
				$this->data['provincias'] = $this->general_model->get_provincias();
				$this->data['localidades'] = $this->general_model->get_localidades($this->data['proveedor']->id_provincia);

				$this->load->view('backend/proveedores/edit', $this->data);
				break;

			case 'view':
				if (!$this->data['proveedor'] = $this->general_model->get_proveedor($offset)) {
					show_404();
				}

				$this->load->view('backend/proveedores/view', $this->data);
				break;

			default:
				# code
				break;
		}
	}

	public function test()
	{
		$this->load->library('email');
		$this->email->from('no-reply@consultar-rrhh.com');
        $this->email->to('tomas@consultar-rrhh.com');
        $this->email->subject("test");
        $message = "<p>This email has been sent success</p>";
        $this->email->message($message);
        $this->email->send();
		$this->email->print_debugger();die();
	}

	public function pedidos($status = "porconfirmar", $offset = 0)
	{
		#status permitidos
		$allowed = array(
			'new',
			'copy',
			'prepedido',
			'porconfirmar',
			'activado',
			'analisis',
			'finalizado',
			'all',
			'facturado',
			'view',
			'edit'
		);

		if (!in_array($status, $allowed)) {
			show_404();
		}

		$this->load->model('general_model');
		
		array_push($this->data['js'], 'jquery_validate');

		switch ($status) {
			case 'new':
				if (!$this->auth->is_admin()) {
					redirect('/backend/dashboard','refresh');
				}

				if ($this->input->post()) {
					$pedido = new stdClass();

					if ($this->input->post('creado', TRUE)) {
						$pedido->creado    = $this->input->post('creado', TRUE);
					}

					$pedido->id_servicio   = $this->input->post('servicio', TRUE);
					$pedido->id_proveedor  = $this->input->post('proveedor', TRUE);
					$pedido->id_analista   = $this->input->post('analista', TRUE);
					$pedido->id_cliente    = $this->input->post('cliente', TRUE);
					$pedido->id_subcliente = $this->input->post('subcliente', TRUE);
					$pedido->id_provincia  = $this->input->post('id_provincia', TRUE);
					$pedido->id_localidad  = $this->input->post('id_localidad', TRUE);
					$pedido->candidato     = $this->input->post('candidato', TRUE);
					$pedido->dni           = $this->input->post('dni', TRUE);
					$pedido->telefono      = $this->input->post('telefono', TRUE);
					$pedido->email         = strtolower($this->input->post('email', TRUE));
					$pedido->vacante       = $this->input->post('vacante', TRUE);
					$pedido->direccion     = $this->input->post('direccion', TRUE);
					$pedido->costo         = $this->input->post('costo', TRUE);
					$pedido->precio        = $this->input->post('precio', TRUE);

					if ($this->input->post('requiere_oc', TRUE) == 'on') {
						$pedido->requiere_oc = TRUE;
						$pedido->oc = $this->input->post('oc', TRUE);
					}

					$pedido->estado        = 'porconfirmar';
					$pedido->observaciones = $this->input->post('observaciones', TRUE);

					$prepedido = $this->input->post('idprepedido', TRUE);

		            $response = $this->general_model->add_pedido($pedido, $prepedido);

		            #get idpedido and save adjunto
		            $idpedido = $this->db->insert_id();
		            $url_callback = "/backend/pedidos/view/$idpedido";

		            if ($this->input->post('fileurl', TRUE)) {
		            	$this->db->insert('adjuntos', array('id_pedido' => $idpedido, 'fullpath' => '/uploads/' . $this->input->post('fileurl', TRUE), 'filename' => $this->input->post('fileurl', TRUE)));
		            }
		            
		            #get_pedido
		            $d['pedido'] = $this->general_model->get_pedido($idpedido);

		            #si se va a crear notificaciones, este es el punto.

				    $this->json_response($response, $url_callback);

				}

				#verificar si es un prepedido
				if ($offset !== 0) {
					if (!$this->data['prepedido'] = $this->general_model->get_prepedido($offset)) {
						show_404();
					}
				}
				$this->data['sidebar_active'] = 'pedidos/new';

				$this->data['provincias'] = $this->general_model->get_provincias();
				$this->data['js'] = array('select2', 'jquery_validate', 'icheck');
				$this->data['css'] = array('select2', 'icheck');
				$this->load->view('backend/pedidos/new', $this->data);
				break;

			case 'copy':
				if (!$this->auth->is_admin()) {
					set_message('Debes ser administrador para duplicar pedidos', 'alert-danger');
					redirect('/backend/dashboard','refresh');
				}

				if ($this->input->post()) {
					$pedido = new stdClass();

					$pedido->id_servicio   = $this->input->post('servicio', TRUE);
					$pedido->id_proveedor  = $this->input->post('proveedor', TRUE);
					$pedido->id_analista   = $this->input->post('analista', TRUE);
					$pedido->id_cliente    = $this->input->post('cliente', TRUE);
					$pedido->id_subcliente = $this->input->post('subcliente', TRUE);
					$pedido->id_provincia  = $this->input->post('id_provincia', TRUE);
					$pedido->id_localidad  = $this->input->post('id_localidad', TRUE);
					$pedido->candidato     = $this->input->post('candidato', TRUE);
					$pedido->dni           = $this->input->post('dni', TRUE);
					$pedido->telefono      = $this->input->post('telefono', TRUE);
					$pedido->email         = strtolower($this->input->post('email', TRUE));
					$pedido->vacante       = $this->input->post('vacante', TRUE);
					$pedido->direccion     = $this->input->post('direccion', TRUE);
					$pedido->costo         = $this->input->post('costo', TRUE);
					$pedido->precio        = $this->input->post('precio', TRUE);
					$pedido->observaciones = $this->input->post('observaciones', TRUE);

					if ($this->input->post('oc', TRUE)) {
						$pedido->oc = $this->input->post('oc', TRUE);
					}

					$idpedido = $this->input->post('idpedido', TRUE);

		            $response = $this->general_model->edit_pedido($pedido, $idpedido);

		            #si se va a crear notificaciones, este es el punto.
				    $this->json_response($response);

				}

				if (!$this->data['pedido'] = $this->general_model->get_pedido($offset)) {
					show_404();
				}

				$this->data['sidebar_active'] = "pedidos/new";

				$this->data['provincias'] = $this->general_model->get_provincias();
				$this->data['js'] = array('jquery_validate', 'dropzone', 'select2');
				$this->data['css'] = array('dropzone', 'select2');
				$this->load->view('backend/pedidos/copy', $this->data);
				break;			


			case 'prepedido':
				if (!$this->auth->is_admin()) {
					redirect('/backend/dashboard','refresh');
				}

				$this->data['sidebar_active'] = 'pedidos/prepedido';
				
				$this->data['prepedidos']    = $this->general_model->get_prepedidos();
				$this->load->view('backend/pedidos/prepedidos', $this->data);
				break;

			case 'edit':
				if (!$this->auth->is_admin()) {
					set_message('Debes ser administrador para editar pedidos', 'alert-danger');
					redirect('/backend/dashboard','refresh');
				}

				if ($this->input->post()) {
					$pedido = new stdClass();

					$pedido->id_servicio   = $this->input->post('servicio', TRUE);
					$pedido->id_proveedor  = $this->input->post('proveedor', TRUE);
					$pedido->id_analista   = $this->input->post('analista', TRUE);
					$pedido->id_cliente    = $this->input->post('cliente', TRUE);
					$pedido->id_subcliente = $this->input->post('subcliente', TRUE);
					$pedido->id_provincia  = $this->input->post('id_provincia', TRUE);
					$pedido->id_localidad  = $this->input->post('id_localidad', TRUE);
					$pedido->candidato     = $this->input->post('candidato', TRUE);
					$pedido->dni           = $this->input->post('dni', TRUE);
					$pedido->telefono      = $this->input->post('telefono', TRUE);
					$pedido->email         = strtolower($this->input->post('email', TRUE));
					$pedido->vacante       = $this->input->post('vacante', TRUE);
					$pedido->direccion     = $this->input->post('direccion', TRUE);
					$pedido->costo         = $this->input->post('costo', TRUE);
					$pedido->precio        = $this->input->post('precio', TRUE);
					$pedido->observaciones = $this->input->post('observaciones', TRUE);

					if ($this->input->post('oc', TRUE)) {
						$pedido->oc = $this->input->post('oc', TRUE);
					}

					$idpedido = $this->input->post('idpedido', TRUE);

		            $response = $this->general_model->edit_pedido($pedido, $idpedido);

		            #si se va a crear notificaciones, este es el punto.
				    $this->json_response($response);

				}

				if (!$this->data['pedido'] = $this->general_model->get_pedido($offset)) {
					show_404();
				}

				$this->data['sidebar_active'] = "pedidos/new";

				$this->data['provincias'] = $this->general_model->get_provincias();
				$this->data['js'] = array('jquery_validate', 'dropzone', 'select2');
				$this->data['css'] = array('dropzone', 'select2');
				$this->load->view('backend/pedidos/edit', $this->data);
				break;			


			case 'all':
				#parámetros de búsqueda
				if ($this->input->post('sin_costo', TRUE) == 'on' || $this->input->post('sin_precio', TRUE) == 'on') {
						$extra = array();

						($this->input->post('sin_costo', TRUE) == 'on') ? $extra['costo'] = 0 : '';
						($this->input->post('sin_precio', TRUE) == 'on') ? $extra['precio'] = 0 : '';
				} else {
					$extra = FALSE;
				}
				if ($offset) {
					$params = base64_decode($offset);
					$params = explode('+', $params);

					$a = explode('=', $params[0]);
					$b = explode('=', $params[1]);
					$c = explode('=', $params[2]);
					$d = explode('=', $params[3]);
					$e = explode('=', $params[4]);

					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = $a[1];
					$this->data['busqueda']    = $b[1];
					$this->data['condicion']   = $c[1];
					$this->data['sin_costo']   = $d[1];
					$this->data['sin_precio']  = $e[1];

					if ($this->data['sin_costo'] == 'on' || $this->data['sin_precio'] == 'on') {
						$extra = array();

						($this->data['sin_costo'] == 'on') ? $extra['costo'] = 0 : '';
						($this->data['sin_precio'] == 'on') ? $extra['precio'] = 0 : '';
					}

				} else {
					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'pedidos.candidato';
					$this->data['busqueda']    = $this->input->post('text');
					$this->data['condicion']   = $this->input->post('condicion');
					$this->data['sin_costo']   = $this->input->post('sin_costo', TRUE);
					$this->data['sin_precio']  = $this->input->post('sin_precio', TRUE);
				}
					

				$params = base64_encode("buscar_por={$this->data['buscar_por']}+busqueda={$this->data['busqueda']}+condicion={$this->data['condicion']}+sin_costo={$this->data['sin_costo']}+sin_precio={$this->data['sin_precio']}");
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 15; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(5);
				$this->data['pedidos'] = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page, $this->data['condicion'], $extra);

				$config['base_url']    = base_url()."backend/pedidos/$status/$params/";
				$config['total_rows']  = $this->general_model->count_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['condicion'], $extra);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 5;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		

				if ($this->input->get('export', TRUE)) {
					$pedidos = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], 0, 0, $this->data['condicion'], $extra);
					pedidos_to_excel($pedidos);
					die();
				}

				$this->data['export_link'] = $config['base_url'] . "?export=true";
				$this->data['sidebar_active'] = 'pedidos/all';
				$this->data['status'] = 'Todos los estados';
				$this->data['rows'] = $config['total_rows'];
				$this->data['estado_actual'] = $status;
				
				$this->load->view('backend/pedidos/list', $this->data);
				break;				

			case 'porconfirmar':
				if (!$this->auth->is_admin()) {
					set_message('Debes ser administrador para confirmar pedidos', 'alert-danger');
					redirect('/backend/dashboard','refresh');
				}

				#parámetros de búsqueda
				if ($this->input->post('sin_costo', TRUE) == 'on' || $this->input->post('sin_precio', TRUE) == 'on') {
						$extra = array();

						($this->input->post('sin_costo', TRUE) == 'on') ? $extra['costo'] = 0 : '';
						($this->input->post('sin_precio', TRUE) == 'on') ? $extra['precio'] = 0 : '';
				} else {
					$extra = FALSE;
				}
				if ($offset) {
					$params = base64_decode($offset);
					$params = explode('+', $params);

					$a = explode('=', $params[0]);
					$b = explode('=', $params[1]);
					$c = explode('=', $params[2]);
					$d = explode('=', $params[3]);
					$e = explode('=', $params[4]);

					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = $a[1];
					$this->data['busqueda']    = $b[1];
					$this->data['condicion']   = $c[1];
					$this->data['sin_costo']   = $d[1];
					$this->data['sin_precio']  = $e[1];

					if ($this->data['sin_costo'] == 'on' || $this->data['sin_precio'] == 'on') {
						$extra = array();

						($this->data['sin_costo'] == 'on') ? $extra['costo'] = 0 : '';
						($this->data['sin_precio'] == 'on') ? $extra['precio'] = 0 : '';
					}

				} else {
					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'pedidos.candidato';
					$this->data['busqueda']    = $this->input->post('text');
					$this->data['condicion']   = $this->input->post('condicion');
					$this->data['sin_costo']   = $this->input->post('sin_costo', TRUE);
					$this->data['sin_precio']  = $this->input->post('sin_precio', TRUE);
				}
					

				$params = base64_encode("buscar_por={$this->data['buscar_por']}+busqueda={$this->data['busqueda']}+condicion={$this->data['condicion']}+sin_costo={$this->data['sin_costo']}+sin_precio={$this->data['sin_precio']}");
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 15; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(5);
				$this->data['pedidos'] = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page, $this->data['condicion'], $extra);

				$config['base_url']    = base_url()."backend/pedidos/$status/$params/";
				$config['total_rows']  = $this->general_model->count_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['condicion'], $extra);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 5;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		

				if ($this->input->get('export', TRUE)) {
					$pedidos = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], 0, 0, $this->data['condicion'], $extra);
					pedidos_to_excel($pedidos);
					die();
				}

				$this->data['export_link'] = $config['base_url'] . "?export=true";
				$this->data['sidebar_active'] = 'pedidos/porconfirmar';
				$this->data['status'] = 'Por Confirmar';
				$this->data['rows'] = $config['total_rows'];
				$this->data['estado_actual'] = $status;
				
				$this->load->view('backend/pedidos/list', $this->data);
				break;						


			case 'activado':
				#parámetros de búsqueda
				#parámetros de búsqueda
				if ($this->input->post('sin_costo', TRUE) == 'on' || $this->input->post('sin_precio', TRUE) == 'on') {
						$extra = array();

						($this->input->post('sin_costo', TRUE) == 'on') ? $extra['costo'] = 0 : '';
						($this->input->post('sin_precio', TRUE) == 'on') ? $extra['precio'] = 0 : '';
				} else {
					$extra = FALSE;
				}
				if ($offset) {
					$params = base64_decode($offset);
					$params = explode('+', $params);

					$a = explode('=', $params[0]);
					$b = explode('=', $params[1]);
					$c = explode('=', $params[2]);
					$d = explode('=', $params[3]);
					$e = explode('=', $params[4]);

					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = $a[1];
					$this->data['busqueda']    = $b[1];
					$this->data['condicion']   = $c[1];
					$this->data['sin_costo']   = $d[1];
					$this->data['sin_precio']  = $e[1];

					if ($this->data['sin_costo'] == 'on' || $this->data['sin_precio'] == 'on') {
						$extra = array();

						($this->data['sin_costo'] == 'on') ? $extra['costo'] = 0 : '';
						($this->data['sin_precio'] == 'on') ? $extra['precio'] = 0 : '';
					}

				} else {
					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'pedidos.candidato';
					$this->data['busqueda']    = $this->input->post('text');
					$this->data['condicion']   = $this->input->post('condicion');
					$this->data['sin_costo']   = $this->input->post('sin_costo', TRUE);
					$this->data['sin_precio']  = $this->input->post('sin_precio', TRUE);
				}
					

				$params = base64_encode("buscar_por={$this->data['buscar_por']}+busqueda={$this->data['busqueda']}+condicion={$this->data['condicion']}+sin_costo={$this->data['sin_costo']}+sin_precio={$this->data['sin_precio']}");
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 15; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(5);
				$this->data['pedidos'] = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page, $this->data['condicion'], $extra);

				$config['base_url']    = base_url()."backend/pedidos/$status/$params/";
				$config['total_rows']  = $this->general_model->count_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['condicion'], $extra);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 5;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		

				if ($this->input->get('export', TRUE)) {
					$pedidos = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], 0, 0, $this->data['condicion'], $extra);
					pedidos_to_excel($pedidos);
					die();
				}

				$this->data['export_link'] = $config['base_url'] . "?export=true";
				$this->data['sidebar_active'] = 'pedidos/activado';
				$this->data['status'] = 'Activados';
				$this->data['rows'] = $config['total_rows'];
				$this->data['estado_actual'] = $status;
				
				$this->load->view('backend/pedidos/list', $this->data);
				break;						

			case 'analisis':
				#parámetros de búsqueda
				if ($this->input->post('sin_costo', TRUE) == 'on' || $this->input->post('sin_precio', TRUE) == 'on') {
						$extra = array();

						($this->input->post('sin_costo', TRUE) == 'on') ? $extra['costo'] = 0 : '';
						($this->input->post('sin_precio', TRUE) == 'on') ? $extra['precio'] = 0 : '';
				} else {
					$extra = FALSE;
				}
				if ($offset) {
					$params = base64_decode($offset);
					$params = explode('+', $params);

					$a = explode('=', $params[0]);
					$b = explode('=', $params[1]);
					$c = explode('=', $params[2]);
					$d = explode('=', $params[3]);
					$e = explode('=', $params[4]);

					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = $a[1];
					$this->data['busqueda']    = $b[1];
					$this->data['condicion']   = $c[1];
					$this->data['sin_costo']   = $d[1];
					$this->data['sin_precio']  = $e[1];

					if ($this->data['sin_costo'] == 'on' || $this->data['sin_precio'] == 'on') {
						$extra = array();

						($this->data['sin_costo'] == 'on') ? $extra['costo'] = 0 : '';
						($this->data['sin_precio'] == 'on') ? $extra['precio'] = 0 : '';
					}

				} else {
					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'pedidos.candidato';
					$this->data['busqueda']    = $this->input->post('text');
					$this->data['condicion']   = $this->input->post('condicion');
					$this->data['sin_costo']   = $this->input->post('sin_costo', TRUE);
					$this->data['sin_precio']  = $this->input->post('sin_precio', TRUE);
				}
					

				$params = base64_encode("buscar_por={$this->data['buscar_por']}+busqueda={$this->data['busqueda']}+condicion={$this->data['condicion']}+sin_costo={$this->data['sin_costo']}+sin_precio={$this->data['sin_precio']}");
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 15; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(5);
				$this->data['pedidos'] = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page, $this->data['condicion'], $extra);

				$config['base_url']    = base_url()."backend/pedidos/$status/$params/";
				$config['total_rows']  = $this->general_model->count_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['condicion'], $extra);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 5;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		

				if ($this->input->get('export', TRUE)) {
					$pedidos = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], 0, 0, $this->data['condicion'], $extra);
					pedidos_to_excel($pedidos);
					die();
				}

				$this->data['export_link'] = $config['base_url'] . "?export=true";
				$this->data['sidebar_active'] = 'pedidos/analisis';
				$this->data['status'] = 'Análisis';
				$this->data['rows'] = $config['total_rows'];
				$this->data['estado_actual'] = $status;
				
				$this->load->view('backend/pedidos/list', $this->data);
				break;							

			case 'finalizado':
				#parámetros de búsqueda
				#parámetros de búsqueda
				if ($this->input->post('sin_costo', TRUE) == 'on' || $this->input->post('sin_precio', TRUE) == 'on') {
						$extra = array();

						($this->input->post('sin_costo', TRUE) == 'on') ? $extra['costo'] = 0 : '';
						($this->input->post('sin_precio', TRUE) == 'on') ? $extra['precio'] = 0 : '';
				} else {
					$extra = FALSE;
				}
				if ($offset) {
					$params = base64_decode($offset);
					$params = explode('+', $params);

					$a = explode('=', $params[0]);
					$b = explode('=', $params[1]);
					$c = explode('=', $params[2]);
					$d = explode('=', $params[3]);
					$e = explode('=', $params[4]);

					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = $a[1];
					$this->data['busqueda']    = $b[1];
					$this->data['condicion']   = $c[1];
					$this->data['sin_costo']   = $d[1];
					$this->data['sin_precio']  = $e[1];

					if ($this->data['sin_costo'] == 'on' || $this->data['sin_precio'] == 'on') {
						$extra = array();

						($this->data['sin_costo'] == 'on') ? $extra['costo'] = 0 : '';
						($this->data['sin_precio'] == 'on') ? $extra['precio'] = 0 : '';
					}

				} else {
					$this->data['ordenar_por'] = 'pedidos.idpedido'; #siempre
					$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'pedidos.candidato';
					$this->data['busqueda']    = $this->input->post('text');
					$this->data['condicion']   = $this->input->post('condicion');
					$this->data['sin_costo']   = $this->input->post('sin_costo', TRUE);
					$this->data['sin_precio']  = $this->input->post('sin_precio', TRUE);
				}
					

				$params = base64_encode("buscar_por={$this->data['buscar_por']}+busqueda={$this->data['busqueda']}+condicion={$this->data['condicion']}+sin_costo={$this->data['sin_costo']}+sin_precio={$this->data['sin_precio']}");
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 15; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(5);
				$this->data['pedidos'] = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page, $this->data['condicion'], $extra);

				$config['base_url']    = base_url()."backend/pedidos/$status/$params/";
				$config['total_rows']  = $this->general_model->count_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['condicion'], $extra);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 5;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		

				if ($this->input->get('export', TRUE)) {
					$pedidos = $this->general_model->search_pedidos($status, $this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], 0, 0, $this->data['condicion'], $extra);
					pedidos_to_excel($pedidos);
					die();
				}

				$this->data['export_link'] = $config['base_url'] . "?export=true";
				$this->data['sidebar_active'] = 'pedidos/finalizado';
				$this->data['status'] = 'Finalizados';
				$this->data['rows'] = $config['total_rows'];
				$this->data['estado_actual'] = $status;
				
				$this->load->view('backend/pedidos/list', $this->data);
				break;							

			case 'view':
				if (!$this->data['pedido'] = $this->general_model->get_pedido($offset)) {
					show_404();
				}

				if ($this->auth->is_admin()) {
					$this->data['allow_upload'] = TRUE;
				} else {
					$this->data['allow_upload'] = FALSE;
				}

				$this->data['sidebar_active'] = "pedidos/{$this->data['pedido']->estado}";

				$this->data['js'] = array('jquery_validate', 'dropzone');
				$this->data['css'] = array('dropzone');
				$this->load->view('backend/pedidos/view', $this->data);
				break;

			default:
				# code
				break;
		}
	}

	public function proveedores($action = "list", $offset = 0)
	{
		$this->load->model('general_model');
		$this->data['sidebar_active'] = 'proveedores/';

		if (!$this->auth->is_admin()) {
			redirect('/backend/dashboard','refresh');
		}

		switch ($action) {
			case 'list':
				#parámetros de búsqueda
				$this->data['ordenar_por'] = 'users.razon'; #siempre
				$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'users.razon';
				$this->data['busqueda']    = $this->input->post('text');
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 10; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(4);
				$this->data['proveedores']    = $this->general_model->get_proveedores($this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page);

				$config['base_url']    = base_url().'backend/proveedores/list/';
				$config['total_rows']  = $this->general_model->count_proveedores($this->data['busqueda'], $this->data['buscar_por']);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
				
				

				$this->load->view('backend/proveedores/list', $this->data);
				break;

			case 'add':
				if ($this->input->post()) {
					#save and exit
					$proveedor = array();
					foreach ($_POST as $key => $value) {
						$proveedor[$key] = $this->input->post($key, TRUE);
					}

					$email    = strtolower($this->input->post('email'));
		            $password = $this->input->post('password');

		            $additional_data = array(
		                'razon' => $this->input->post('razon'),
		                'cbu'  => $this->input->post('cbu'),
		                'cuenta' => $this->input->post('cuenta'),
		                'cuit'  => $this->input->post('cuit'),
		                'banco' => $this->input->post('banco'),
		                'telefono'  => $this->input->post('telefono'),
		                'domicilio' => $this->input->post('domicilio'),
		                'id_provincia'  => $this->input->post('id_provincia'),
		                'id_localidad'  => $this->input->post('id_localidad'),
		            );

		            #at this point, if register is success or not we have to set message and redirect. So...
		            $this->auth->register($email, $password, $email, $additional_data);
		            #set message and redirect
		            set_message($this->auth->get_auth_messages());
		            redirect("/backend/proveedores/list", 'refresh');

				}

				$this->data['js'] = array('jquery_validate');
				$this->data['provincias'] = $this->general_model->get_provincias();
				$this->load->view('backend/proveedores/add', $this->data);
				break;

			case 'edit':

				if (!$this->data['proveedor'] = $this->general_model->get_proveedor($offset)) {
					show_404();
				}

				#check if post
				if ($this->input->post()) {
					$user_id = $this->input->post('idproveedor', TRUE);
					$email    = strtolower($this->input->post('email'));
		            $data = array(
						'email'        => $this->input->post('email'),
						'razon'        => $this->input->post('razon'),
						'cbu'          => $this->input->post('cbu'),
						'cuenta'       => $this->input->post('cuenta'),
						'cuit'         => $this->input->post('cuit'),
						'banco'        => $this->input->post('banco'),
						'telefono'     => $this->input->post('telefono'),
						'domicilio'    => $this->input->post('domicilio'),
						'id_provincia' => $this->input->post('id_provincia'),
						'id_localidad' => $this->input->post('id_localidad'),
		            );

					// update the password if it was posted
					if ($this->input->post('password'))
					{
						$data['password'] = $this->input->post('password');
					}

					// check to see if we are updating the user
					$this->auth->update($user_id, $data);
					redirect("/backend/proveedores/edit/{$user_id}", 'refresh');
				}

				$this->data['js'] = array('jquery_validate');
				$this->data['provincias'] = $this->general_model->get_provincias();
				$this->data['localidades'] = $this->general_model->get_localidades($this->data['proveedor']->id_provincia);

				$this->load->view('backend/proveedores/edit', $this->data);
				break;

			case 'view':
				#check if post
				if ($this->input->post()) {

					$data = new stdClass();
					$data->id_servicio = $this->input->post('servicio', TRUE);
					$data->id_proveedor  = $this->input->post('proveedor', TRUE);
					$data->costo      = $this->input->post('costo', TRUE);

					$response = $this->general_model->add_servicio_proveedores($data);
			    	$this->json_response($response);
				}

				if (!$this->data['proveedor'] = $this->general_model->get_proveedor($offset)) {
					show_404();
				}

				$this->data['servicios_proveedores'] = $this->general_model->get_servicios_proveedores($offset);
				$this->data['servicios'] = $this->general_model->get_servicios();


				array_push($this->data['js'], 'jquery_validate');
				$this->load->view('backend/proveedores/view', $this->data);
				break;

			default:
				# code
				break;
		}
	}

	public function clientes($action = "list", $offset = 0)
	{
		$this->load->model('general_model');
		$this->data['sidebar_active'] = 'clientes/';

		if (!$this->auth->is_admin()) {
			redirect('/backend/dashboard','refresh');
		}

		switch ($action) {
			case 'list':
				#parámetros de búsqueda
				$this->data['ordenar_por'] = 'idcliente'; #siempre
				$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'razon';
				$this->data['busqueda']    = $this->input->post('text');
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 10; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(4);
				$this->data['clientes']    = $this->general_model->get_clientes($this->data['busqueda'], $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page);

				$config['base_url']    = base_url().'backend/clientes/list/';
				$config['total_rows']  = $this->general_model->count_clientes();//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
				
				

				$this->load->view('backend/clientes/list', $this->data);
				break;

			case 'add':
				if ($this->input->post()) {
					#save and exit
					$cliente = array(
						'razon'     => $this->input->post('razon'),
						'cuit'      => $this->input->post('cuit'),
						'telefono'  => $this->input->post('telefono'),
						'email'     => $this->input->post('email'),
						'codigo'    => $this->input->post('codigo'),
						'direccion' => $this->input->post('direccion'),
				    );

					$response = $this->general_model->add_cliente($cliente);
				    $this->json_response($response);
				    
				}

				$this->data['js'] = array('jquery_validate');
				$this->load->view('backend/clientes/add', $this->data);
				break;

			case 'edit':

				if (!$this->data['cliente'] = $this->general_model->get_cliente($offset)) {
					show_404();
				}

				#check if post
				if ($this->input->post()) {
					$data = new stdClass();
					$data->idcliente = $this->input->post('idcliente', TRUE);
					$data->razon     = $this->input->post('razon', TRUE);
					$data->cuit      = $this->input->post('cuit', TRUE);
					$data->telefono  = $this->input->post('telefono', TRUE);
					$data->email     = $this->input->post('email', TRUE);
					$data->direccion = $this->input->post('direccion', TRUE);
					$data->forzar_oc = $this->input->post('forzar_oc', TRUE);


					$response = $this->general_model->update_cliente($data);
			    	$this->json_response($response);
				}

				$this->data['js'] = array('jquery_validate');
				$this->load->view('backend/clientes/edit', $this->data);
				break;

			case 'view':
				#check if post
				if ($this->input->post()) {
					$action = $this->input->post('action', TRUE);

					if ($action == 'servicio') {
						$data = new stdClass();
						$data->id_servicio = $this->input->post('servicio', TRUE);
						$data->id_cliente  = $this->input->post('cliente', TRUE);
						$data->precio      = $this->input->post('precio', TRUE);

						$response = $this->general_model->add_servicio_cliente($data);
				    	$this->json_response($response);
					} elseif ($action == 'subcliente') {
						$data = new stdClass();
						$data->nombre     = $this->input->post('nombre', TRUE);
						$data->id_cliente = $this->input->post('cliente', TRUE);
						$data->telefono   = $this->input->post('telefono', TRUE);
						$data->email      = $this->input->post('email', TRUE);

						$response = $this->general_model->add_subcliente($data);
				    	$this->json_response($response);
					} elseif ($action == 'medico') {
						$data = new stdClass();
						$data->id_medico     = $this->input->post('medico', TRUE);
						$data->id_cliente = $this->input->post('cliente', TRUE);

						$response = $this->db->insert('medicos_clientes', $data);
				    	$this->json_response($response);
					}
				}

				if (!$this->data['cliente'] = $this->general_model->get_cliente($offset)) {
					show_404();
				}

				$this->data['js'] = array('jquery_validate', 'dropzone', 'select2');
				$this->data['css'] = array('dropzone', 'select2');
				$this->data['cotizaciones'] = $this->general_model->get_cotizaciones($offset);
				$this->data['servicios'] = $this->general_model->get_servicios();
				$this->data['medicos'] = $this->db->get('medicos')->result();
				$this->data['clientes'] = $this->general_model->get_clientes('', 'idcliente', 'idcliente', 0);
				$this->data['servicios_cliente'] = $this->general_model->get_servicios_cliente($offset);
				
				$this->db->join('medicos', 'medicos.idmedico = medicos_clientes.id_medico ', 'left');
				$this->db->where('medicos_clientes.id_cliente', $offset);
				$this->data['medicos_cliente'] = $this->db->get('medicos_clientes')->result();
				$this->data['subclientes'] = $this->general_model->get_subclientes($offset);
				$this->load->view('backend/clientes/view', $this->data);
				break;

			default:
				# code
				break;
		}
	}

	public function servicios($action = "list", $offset = 0)
	{
		$this->load->model('general_model');
		$this->data['sidebar_active'] = 'servicios/';

		if (!$this->auth->is_admin()) {
			redirect('/backend/dashboard','refresh');
		}

		switch ($action) {
			case 'list':
				#parámetros de búsqueda
				$this->data['ordenar_por'] = 'servicio'; #siempre
				$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'servicio';
				$this->data['busqueda']    = $this->input->post('text');
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 10; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(4);
				$this->data['servicios']    = $this->general_model->search_servicios($this->data['busqueda'], NULL, $this->data['buscar_por'], $this->data['ordenar_por'], $per_page, $current_page);

				$config['base_url']    = base_url().'backend/servicios/list/';
				$config['total_rows']  = $this->general_model->count_servicios($this->data['busqueda'], $this->data['buscar_por']);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
				
				

				$this->load->view('backend/servicios/list', $this->data);
				break;

			case 'add':
				if ($this->input->post()) {
					#save and exit
					$cliente = array(
						'servicio'   => $this->input->post('servicio'),
						'codigo'     => $this->input->post('codigo'),
						'is_medical' => ($this->input->post('is_medical') == 1) ? TRUE : FALSE
				    );

					$response = $this->general_model->add_servicio($cliente);
				    $this->json_response($response);
				    
				}

				$this->data['js'] = array('jquery_validate');
				$this->load->view('backend/servicios/add', $this->data);
				break;

			case 'edit':

				if (!$this->data['servicio'] = $this->general_model->get_servicio($offset)) {
					show_404();
				}

				#check if post
				if ($this->input->post()) {
					$data = new stdClass();
					$data->idservicio = $this->input->post('idservicio', TRUE);
					$data->servicio     = $this->input->post('servicio', TRUE);
					$data->codigo  = $this->input->post('codigo', TRUE);
					$data->is_medical  = ($this->input->post('is_medical') == 1) ? TRUE : FALSE;

					$response = $this->general_model->update_servicio($data);
			    	$this->json_response($response);
				}

				$this->data['js'] = array('jquery_validate');
				$this->load->view('backend/servicios/edit', $this->data);
				break;

			case 'view':
				#disabled by now
				show_404();
				#check if post
				if ($this->input->post()) {
					$action = $this->input->post('action', TRUE);

					if ($action == 'servicio') {
						$data = new stdClass();
						$data->id_servicio = $this->input->post('servicio', TRUE);
						$data->id_cliente  = $this->input->post('cliente', TRUE);
						$data->precio      = $this->input->post('precio', TRUE);

						$response = $this->general_model->add_servicio_cliente($data);
				    	$this->json_response($response);
					} elseif ($action == 'subcliente') {
						$data = new stdClass();
						$data->nombre     = $this->input->post('nombre', TRUE);
						$data->id_cliente = $this->input->post('cliente', TRUE);
						$data->telefono   = $this->input->post('telefono', TRUE);
						$data->email      = $this->input->post('email', TRUE);

						$response = $this->general_model->add_subcliente($data);
				    	$this->json_response($response);
					}
				}

				if (!$this->data['cliente'] = $this->general_model->get_cliente($offset)) {
					show_404();
				}

				$this->data['js'] = array('jquery_validate', 'dropzone');
				$this->data['servicios'] = $this->general_model->get_servicios();
				$this->data['servicios_cliente'] = $this->general_model->get_servicios_cliente($offset);
				$this->data['subclientes'] = $this->general_model->get_subclientes($offset);
				$this->load->view('backend/clientes/view', $this->data);
				break;

			default:
				# code
				break;
		}
	}

	public function medicos($action = "list", $offset = 0)
	{
		$this->load->model('general_model');
		$this->data['sidebar_active'] = 'medicos/';

		if (!$this->auth->is_admin()) {
			redirect('/backend/dashboard','refresh');
		}

		switch ($action) {
			case 'list':
				#parámetros de búsqueda
				$this->data['ordenar_por'] = 'idmedico'; #siempre
				$this->data['buscar_por']  = ($this->input->post('buscar_por')) ? $this->input->post('buscar_por') : 'nombre';
				$this->data['busqueda']    = $this->input->post('text');
				
				#paginación
				$this->load->library('pagination'); 
				#cargar variables iniciales de paginación
				$per_page              = 10; //Número de registros mostrados por páginas
				$current_page          = $this->uri->segment(4);
				$this->data['medicos']    = $this->general_model->get_medicos();

				$config['base_url']    = base_url().'backend/servicios/list/';
				$config['total_rows']  = count($this->data['medicos']);//calcula el número de filas  
				$config['per_page']    = $per_page; //Número de registros mostrados por páginas
				$config['num_links']   = 2; //Número de links mostrados en la paginación
				$config["uri_segment"] = 4;//el segmento de la paginación
				$this->pagination->initialize($config); //inicializamos la paginación		
				
				

				$this->load->view('backend/medicos/list', $this->data);
				break;

			case 'add':
				if ($this->input->post()) {
					#save and exit
					$medico = array(
						'nombre'     => $this->input->post('nombre'),
						'email'      => $this->input->post('email'),
						'password'   => $this->input->post('password')
				    );

					$response = $this->db->insert('medicos', $medico);


				    set_message('Médico registrado correctamente');
				    redirect('/backend/medicos/list');
				    
				}

				$this->data['servicios'] = $this->general_model->get_servicios();
				$this->data['js'] = array('jquery_validate', 'select2');
				$this->data['css'] = array('select2');
				$this->load->view('backend/medicos/add', $this->data);
				break;

			case 'edit':

				if (!$this->data['medico'] = $this->db->get_where('medicos', array('idmedico' => $offset))->row()) {
					show_404();
				}

				#check if post
				if ($this->input->post()) {
					$data = new stdClass();
					$data->idmedico = $this->input->post('idmedico', TRUE);
					$data->nombre     = $this->input->post('nombre', TRUE);
					$data->email  = $this->input->post('email', TRUE);
					$data->password  = $this->input->post('password', TRUE);

					$response = $this->db->update('medicos', $data, array('idmedico' => $data->idmedico));

			    	if ($response) {	
			    		set_message('Datos actualizados correctamente');
			    	} else {
			    		set_message('Ha ocurrido un error', 'alert-danger');
			    	}

			    	redirect('/backend/medicos/list');
				}

				$this->data['js'] = array('jquery_validate');
				$this->load->view('backend/medicos/edit', $this->data);
				break;

			case 'view':
				#disabled by now
				show_404();
				#check if post
				if ($this->input->post()) {
					$action = $this->input->post('action', TRUE);

					if ($action == 'servicio') {
						$data = new stdClass();
						$data->id_servicio = $this->input->post('servicio', TRUE);
						$data->id_cliente  = $this->input->post('cliente', TRUE);
						$data->precio      = $this->input->post('precio', TRUE);

						$response = $this->general_model->add_servicio_cliente($data);
				    	$this->json_response($response);
					} elseif ($action == 'subcliente') {
						$data = new stdClass();
						$data->nombre     = $this->input->post('nombre', TRUE);
						$data->id_cliente = $this->input->post('cliente', TRUE);
						$data->telefono   = $this->input->post('telefono', TRUE);
						$data->email      = $this->input->post('email', TRUE);

						$response = $this->general_model->add_subcliente($data);
				    	$this->json_response($response);
					}
				}

				if (!$this->data['cliente'] = $this->general_model->get_cliente($offset)) {
					show_404();
				}

				$this->data['js'] = array('jquery_validate', 'dropzone');
				$this->data['servicios'] = $this->general_model->get_servicios();
				$this->data['servicios_cliente'] = $this->general_model->get_servicios_cliente($offset);
				$this->data['subclientes'] = $this->general_model->get_subclientes($offset);
				$this->load->view('backend/clientes/view', $this->data);
				break;

			default:
				# code
				break;
		}
	}


	private function json_response($success = TRUE, $url_callback = '')
	{
		if ($success) {
	      $response = array(
	        'class' => 'modal fade modal-success',
	        'status' => 'success',
	        'message' => 'Operación completada correctamente',
	        'icon' => 'fa fa-check fa-4x block text-center',
	        'redirect' => $url_callback
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

	public function session_manager()
	{
		
	}

	public function factura_pdf($id_factura)
	{
		$this->load->model('general_model');
		if (!$factura = $this->general_model->get_factura($id_factura)) {
			show_404();
		}
		// echo '<pre>';
		// var_dump($factura);die();

		$this->load->library('phpinvoice');

		$this->phpinvoice->setLogo("_assets/img/logo.png");
		$this->phpinvoice->setColor("#f58220");
		$this->phpinvoice->setType("Remito de servicios");
		$this->phpinvoice->setReference($factura->numero_factura);
		$this->phpinvoice->setDate(date('d/m/Y',strtotime($factura->fecha)));
		$this->phpinvoice->setTime(date('h:i:s A',strtotime($factura->fecha)));
		// $this->phpinvoice->setDue(date('M dS ,Y',strtotime('+3 months')));
		$this->phpinvoice->setFrom(array($this->config->item('company')['nombre'], $this->config->item('company')['direccion'], $this->config->item('company')['zip'], $this->config->item('company')['telefono']));
		$this->phpinvoice->setTo(array($factura->cliente->razon, 'CUIT: ' . $factura->cliente->cuit, $factura->cliente->telefono, $factura->cliente->email));

		/* Adding Items in table */
		$total = 0;
		foreach ($factura->pedidos as $item) {
			$item->servicio .= "<br>Fecha pedido: " . _date($item->creado);
			$item->servicio .= "<br>Vacante: " . $item->vacante;
			$item->servicio .= "<br>Solicitante: " . $item->nombre;
			if ($item->requiere_oc) {
				$item->servicio .= "<br>Orden de compra: " . $item->oc;
			}

			$this->phpinvoice->addItem(strtoupper($item->candidato), $item->servicio, 1, false, $item->precio,false, 0);
			$total += $item->precio;
		}
		

		/* Add totals */
		$this->phpinvoice->addTotal("Total facturado", $total, true);

		#chequear si esta pagada y agregar detalles
		if ($factura->pagada) {
			/* Set badge */ 
			$this->phpinvoice->addBadge("PAGADA");
			/* Add title */
			$this->phpinvoice->addTitle("Información de pago");
			/* Add Paragraph */
			$this->phpinvoice->addParagraph("Fecha de pago marcada:  " . _date($factura->fecha_pago));
			/* Set footer note */
		}

		if ($factura->adjunto) {
			$a = @getimagesize(site_url() . $orden->adjunto);
			if(is_array($a)){
				#chck if is an image
				if (strpos($a['mime'], 'image') !== FALSE) {
					$this->phpinvoice->setAttach(site_url() . $orden->adjunto);
				}
			}
		}
			
			
		$this->phpinvoice->setFooternote(sprintf('%s | CUIT %s | %s | %s', $this->config->item('company')['nombre'], $this->config->item('company')['cuit'], $this->config->item('company')['telefono'], $this->config->item('company')['email']));
		/* Render */
		$this->phpinvoice->render('example1.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
	}

	public function orden_pdf($id_orden)
	{
		$this->load->model('general_model');
		if (!$orden = $this->general_model->get_orden($id_orden)) {
			show_404();
		}
		
		// var_dump($orden);die();

		$this->load->library('phpinvoice');

		$this->phpinvoice->setLogo("_assets/img/logo.png");
		$this->phpinvoice->setColor("#f58220");
		$this->phpinvoice->setType("Orden de Compra");
		$this->phpinvoice->setReference("OC-{$orden->idorden}");
		$this->phpinvoice->setDate(date('d/m/Y',strtotime($orden->fecha)));
		$this->phpinvoice->setTime(date('h:i:s A',strtotime($orden->fecha)));
		// $this->phpinvoice->setDue(date('M dS ,Y',strtotime('+3 months')));
		$this->phpinvoice->setFrom(array($this->config->item('company')['nombre'], $this->config->item('company')['direccion'], $this->config->item('company')['zip'], $this->config->item('company')['telefono']));
		$this->phpinvoice->setTo(array($orden->proveedor->razon, 'CUIT: ' . $orden->proveedor->cuit, $orden->proveedor->telefono, $orden->proveedor->email));

		/* Adding Items in table */
		foreach ($orden->pedidos as $item) {
			$item->servicio .= "<br>DNI: " . $item->dni;
			$item->servicio .= "<br>Vacante: " . $item->vacante;
			$this->phpinvoice->addItem($item->candidato, $item->servicio, 1, false, $item->costo,false, 0);
		}
		

		/* Add totals */
		$this->phpinvoice->addTotal("Total orden", $orden->total_orden, true);

		#chequear si esta pagada y agregar detalles
		if ($orden->facturada) {
			/* Set badge */ 
			$this->phpinvoice->addBadge("FACTURADA");
			/* Add title */
			$this->phpinvoice->addTitle("Información de facturación");
			/* Add Paragraph */
			$this->phpinvoice->addParagraph("Fecha de facturación marcada:  " . _date($orden->fecha_facturada) . " - Factura: " . $orden->numero_factura);
			/* Set footer note */
		}
		if ($orden->pagada) {
			/* Set badge */ 
			$this->phpinvoice->addBadge("PAGADA");
			/* Add title */
			$this->phpinvoice->addTitle("Información de pago");
			/* Add Paragraph */
			$this->phpinvoice->addParagraph("Fecha de pago marcada:  " . _date($orden->fecha_pago));
			/* Set footer note */
		}

		if ($orden->adjunto) {
			$a = @getimagesize(site_url() . $orden->adjunto);
			if(is_array($a)){
				#chck if is an image
				if (strpos($a['mime'], 'image') !== FALSE) {
					$this->phpinvoice->setAttach(site_url() . $orden->adjunto);
				}
			}
		}
			
		$this->phpinvoice->setFooternote(sprintf('%s | CUIT %s | %s | %s', $this->config->item('company')['nombre'], $this->config->item('company')['cuit'], $this->config->item('company')['telefono'], $this->config->item('company')['email']));
		/* Render */
		$this->phpinvoice->render('example1.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
	}

	public function check_session()
	{
		if (!$this->auth->logged_in()) {
			set_message('must_be_logged_in', 'alert-danger');
			redirect('/backend/login','refresh');
		}

	}
}

/* End of file Backend.php */
/* Location: ./application/controllers/Backend.php */