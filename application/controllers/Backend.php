<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backend extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('backend_model');

		$this->data['js'] = array();
		$this->data['css'] = array();
	}

	public function dashboard()
	{
		#check admin session
		$this->check_session();

		$this->load->view('backend/starter', $this->data);
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

	public function session_manager()
	{
		
	}

	public function check_session()
	{
		if (!$this->auth->logged_in()) {
			set_message('must_be_logged_in', 'alert-danger');
			redirect('/backend/login','refresh');
		}

		if (!$this->auth->is_admin()) {
			set_message('restricted_admin_area', 'alert-danger');
			redirect('/','refresh');
		}
	}
}

/* End of file Backend.php */
/* Location: ./application/controllers/Backend.php */