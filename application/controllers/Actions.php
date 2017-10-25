<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Actions extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
	}

	public function dashboard_report($tipo)
	{

		if (!$this->auth->is_admin()) {
			$id = $this->auth->get_user()->id;
			$extra = "AND (p.id_proveedor = $id || p.id_analista = $id)";
		} else {
			$extra = "";
		}

		switch ($tipo) {
			case 'r1':
				$start = $this->input->post('start', TRUE);
				$end = $this->input->post('end', TRUE);

				$sql = "SELECT 
							a.month, 
							IFNULL(b.fecha, '-') AS fecha, 
							IFNULL(b.total, 0) AS total, 
							IFNULL(b.estado, '-') AS estado,
							IFNULL(b.ingresos, 0) AS ingresos,
							IFNULL(b.costos, 0) AS costos
						FROM
						(
						    SELECT 01 month, 1 monthOrder UNION 
						    SELECT 02 month, 2 monthOrder UNION 
						    SELECT 03 month, 3 monthOrder UNION 
						    SELECT 04 month, 4 monthOrder UNION 
						    SELECT 05 month, 5 monthOrder UNION 
						    SELECT 06 month, 6 monthOrder UNION 
						    SELECT 07 month, 7 monthOrder UNION 
						    SELECT 08 month, 8 monthOrder UNION 
						    SELECT 09 month, 9 monthOrder UNION 
						    SELECT 10 month, 10 monthOrder UNION 
						    SELECT 11 month, 11 monthOrder UNION 
						    SELECT 12 month, 12 monthOrder 
						) as a LEFT JOIN (
							SELECT 
								concat( DATE_FORMAT(creado ,'%b-%Y')) as `fecha`,
								creado, 
						      count(*) as `total`, 
								estado, 
								sum(precio) as ingresos,
								sum(costo) as costos
							FROM pedidos AS p
							WHERE
								creado BETWEEN '$start' AND '$end' 
								$extra
							GROUP BY YEAR(p.creado), MONTH(p.creado)
						) AS b on a.month = DATE_FORMAT(b.creado, '%m')
						ORDER BY month ASC";

					$query = $this->db->query($sql);

					if ($query->num_rows() > 0) {
						echo json_encode($query->result());
					} else {
						echo json_encode(FALSE);
					}
				break;

			case 'r2':
				$start = $this->input->post('start', TRUE);
				$end = $this->input->post('end', TRUE);

				$sql = "SELECT 
							concat( DATE_FORMAT(creado ,'%d/%m')) as `fecha`,
							creado, 
					      	count(*) as `total`,
							sum(precio) as ingresos,
							sum(costo) as costos
						FROM pedidos AS p
						WHERE
							creado BETWEEN '$start' AND '$end' 
							$extra
						GROUP BY YEAR(p.creado), MONTH(p.creado), DAY(p.creado)
						ORDER BY fecha ASC";

					$query = $this->db->query($sql);

					if ($query->num_rows() > 0) {
						echo json_encode($query->result());
					} else {
						echo json_encode(FALSE);
					}
				break;

			case 'r3':
				$start = $this->input->post('start', TRUE);
				$end = $this->input->post('end', TRUE);

				$sql = "SELECT 
					      	count(*) as `total`,
							estado
						FROM pedidos AS p
						WHERE
							creado BETWEEN '$start' AND '$end' 
							$extra
						GROUP BY estado";

					$query = $this->db->query($sql);

					if ($query->num_rows() > 0) {
						echo json_encode($query->result());
					} else {
						echo json_encode(FALSE);
					}
				break;
			
			default:
				# code...
				break;
		}
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

	public function delete_subcliente($id_cliente = NULL, $id_subcliente = NULL)
	{
		$this->load->model('general_model');
		$response = $this->general_model->delete_subcliente($id_cliente, $id_subcliente);

		$this->json_response($response);
	}

	public function delete_gasto()
	{
		$this->load->model('general_model');
		$this->db->where('idgasto', $this->input->post('idgasto', TRUE));
		$response = $this->db->delete('gastos');

		$this->json_response($response);
	}

	public function delete_servicio()
	{
		$this->load->model('general_model');
		$idservicio = $this->input->post('idservicio', TRUE);
		$this->db->where('idservicio', $idservicio);
		$response = $this->db->delete('servicios');
		$this->json_response($response);
	}

	public function delete_servicio_cliente($id_cliente = NULL, $id_servicio = NULL)
	{
		$this->load->model('general_model');
		$response = $this->general_model->delete_servicio_cliente($id_cliente, $id_servicio);

		$this->json_response($response);
	}

	public function delete_servicio_proveedores($id_proveedor = NULL, $id_servicio = NULL)
	{
		$this->load->model('general_model');
		$response = $this->general_model->delete_servicio_proveedores($id_proveedor, $id_servicio);

		$this->json_response($response);
	}

	public function get_localidades($id_provincia)
	{
		$this->load->model('general_model');
		echo json_encode($this->general_model->get_localidades($id_provincia));
		exit();
	}

	public function get_chat_history($idpedido)
	{
		$this->load->model('general_model');
		if ($chats = $this->general_model->get_chat_history($idpedido)) {
			$response = array(
				'status'         => 'success',
				'messages_found' => count($chats),
				'chats'          => $chats
				);
		} else {
			$response = array(
				'status'         => 'success',
				'messages_found' => 0
				);
		}

		echo json_encode($response);
	}

	private function adjuntar_factura($idfactura, $url)
	{
		$this->db->update('facturas', array('adjunto' => $url), array('idfactura' => $idfactura));
	}

	private function adjuntar_orden($idorden, $url)
	{
		$this->db->update('ordenes_compra', array('adjunto' => $url), array('idorden' => $idorden));
	}

	public function upload_file()
	{
		if ($_FILES AND $_FILES['file']['name']) {
			$config['upload_path']          = "./uploads/";
			$config['allowed_types']        = '*';
			$config['max_size']             = 10240;
			$config['file_name']            = "adjunto_".time();
			$this->load->library('upload', $config);

			header('Content-Type: application/json');
			if ( ! $this->upload->do_upload('file')){
				echo json_encode(
					array(
						'status' => 'error',
						'error' => $this->upload->display_errors() . $config['upload_path']
						)
					);
			} else {
				if ($this->input->post('id_pedido', TRUE)) {
					#save adjunto
					$this->db->insert('adjuntos', array('id_pedido' => $this->input->post('id_pedido', TRUE), 'fullpath' => $this->upload->data()['full_path'], 'filename' => $this->upload->data()['file_name']));
				}

				if ($this->input->post('callback', TRUE)) {
					$function = $this->input->post('callback', TRUE);

					$this->$function($this->input->post('id', TRUE), '/uploads/' . $this->upload->data()['file_name']);
				}

				echo json_encode(
					array(
						'status' => 'success',
						'fullpath' => $this->upload->data()['full_path'],
						'filename' => $this->upload->data()['file_name'],
						'upload_data' => $this->upload->data()
						)
					);
			}
		}
	}

	public function get_resumen_ventas()
	{
		$start = $this->input->post('start', TRUE);
		$end = $this->input->post('end', TRUE);

		$this->db->select("count(*) as cantidad_pedidos, COALESCE(sum(costo), 0) as costos, COALESCE(sum(precio), 0) as ingresos, COALESCE(DATE_FORMAT(MAX(creado), '%h:%i %p'), '-') as hora_ultimo_pedido, COALESCE((sum(precio) - sum(costo)), 0) as beneficios, COALESCE((SELECT SUM(total) FROM gastos where fecha between '{$start}' AND '{$end}'), 0) as gastos");
		

		$this->db->where('creado >=', $start);
		$this->db->where('creado <=', $end);

		$query = $this->db->get('pedidos');

		echo json_encode($query->row());
	}

	public function eliminar_prepedido($idprepedido)
	{
		$this->db->where('idprepedido', $idprepedido);
		$this->db->delete('prepedidos');

		set_message('Prepedido eliminado correctamente', 'alert-info');

		redirect('/clientes/dashboard','refresh');
	}

	public function upload_cotizacion()
	{
		if ($_FILES AND $_FILES['file']['name']) {
			$config['upload_path']          = "./uploads/";
			$config['allowed_types']        = '*';
			$config['max_size']             = 10240;
			$config['file_name']            = "adjunto_".time();
			$this->load->library('upload', $config);

			header('Content-Type: application/json');
			if ( ! $this->upload->do_upload('file')){
				echo json_encode(
					array(
						'status' => 'error',
						'error' => $this->upload->display_errors() . $config['upload_path']
						)
					);
			} else {
				$data = array(
					'id_cliente' => $this->input->post('id_cliente', TRUE), 
					'filename' => $_FILES['file']['name'],
					'fileurl' => '/uploads/' . $this->upload->data()['file_name']
				);

				$this->db->insert('cotizaciones', $data);

				echo json_encode(
					array(
						'status' => 'success',
						'fullpath' => $this->upload->data()['full_path'],
						'filename' => $this->upload->data()['file_name'],
						'upload_data' => $this->upload->data(),
						'temp' => $_FILES['file']['name']
					)
				);
			}
		}
	}

	#asociados a un cliente en particular
	public function search_servicios($idcliente, $text = '')
	{
		if ($text == 'undefined') {
			$text = '';
		}
		$this->load->model('general_model');

		$servicios = $this->general_model->search_servicios($text, $idcliente, 'servicios.servicio', 'servicios.servicio', 50, 0);

		die(json_encode($servicios));
	}

	public function get_costo_servicio($idproveedor = NULL, $idservicio)
	{
		$this->db->join('servicios_proveedores', 'servicios_proveedores.id_servicio = servicios.idservicio', 'left');
		$this->db->where('idservicio', $idservicio);
		$this->db->where('id_proveedor', $idproveedor);

		$query = $this->db->get('servicios', 1);

		if ($query->num_rows() > 0) {
			die(json_encode($query->row()));
		}

		die(json_encode(FALSE));
	}

	public function get_precio_servicio($idcliente = NULL, $idservicio)
	{
		$this->db->join('servicios_clientes', 'servicios_clientes.id_servicio = servicios.idservicio', 'left');
		$this->db->where('idservicio', $idservicio);
		$this->db->where('id_cliente', $idcliente);

		$query = $this->db->get('servicios', 1);

		if ($query->num_rows() > 0) {
			die(json_encode($query->row()));
		}

		die(json_encode(FALSE));
	}

	public function search_proveedores($text = '')
	{
		if ($text == 'undefined') {
			$text = '';
		}
		$this->load->model('general_model');

		$fields = "users.id as idproveedor, razon as proveedor, email";
		$proveedores = $this->general_model->search_proveedores($fields, $text, 'users.razon', 'users.razon', 50, 0);

		die(json_encode($proveedores));
	}

	public function search_clientes($text = '')
	{
		if ($text == 'undefined') {
			$text = '';
		}
		$this->load->model('general_model');

		$fields = "clientes.idcliente, razon as cliente, email, clientes.forzar_oc";
		$clientes = $this->general_model->search_clientes($fields, $text, 'clientes.razon', 'clientes.razon', 0, 0);

		die(json_encode($clientes));
	}

	public function search_clientes_pendientes_factura()
	{
		$text = ($this->input->post('q', TRUE)) ?: '';
		$start = $this->input->post('start', TRUE);
		$end = $this->input->post('end', TRUE);

		$this->load->model('general_model');

		$fields = "clientes.idcliente, razon as cliente, clientes.email, clientes.forzar_oc";
		$clientes = $this->general_model->search_clientes_pendientes_factura($fields, $text, $start, $end, 'clientes.razon', 'clientes.razon', 0, 0);

		die(json_encode($clientes));
	}

	public function get_subclientes($idcliente)
	{
		$this->load->model('general_model');

		$subclientes = $this->general_model->get_subclientes($idcliente);

		die(json_encode($subclientes));
	}

	public function flag_pedido()
	{
		if (!$this->input->post()) {
			$this->json_response(FALSE);
		}

		$condition = array(
			'idpedido' => $this->input->post('idpedido', TRUE)
		);

		$data = array(
			'flagged' => !$this->input->post('flag_status', TRUE)
		);

		$this->db->update('pedidos', $data, $condition);

		$this->json_response(TRUE);

	}

	public function update_estado_pedido()
	{
		if (!$this->input->post()) {
			$this->json_response(FALSE);
		}

		$condition = array(
			'idpedido' => $this->input->post('idpedido', TRUE),
			'estado' => $this->input->post('estado_actual', TRUE)
		);

		$data = array(
			'estado' => $this->input->post('nuevo_estado', TRUE),
			'flagged' => FALSE
		);

		$chat = array(
			'id_pedido' => $this->input->post('idpedido', TRUE),
			'usuario' => $this->auth->get_user()->razon,
			'comentario' => "SYSTEM: Estado cambiado - <b>{$this->input->post('estado_actual', TRUE)}</b> a <b>{$this->input->post('nuevo_estado', TRUE)}</b>"
		);
		$this->db->update('pedidos', $data, $condition);

		if ($this->input->post('nuevo_estado', TRUE) == 'activado') {
			#get email subcliente y notificar
			$this->load->model('general_model');

			$pedido = $this->general_model->get_pedido($this->input->post('idpedido', TRUE));
			#get subcliente
			$subcliente = $this->db->get_where('subclientes', array('idsubcliente' => $pedido->id_subcliente), 1)->row();

			$this->email->from('notificaciones@consultar-rrhh.com', 'no-responder');
        	$this->email->to($subcliente->email);
        	
        	$this->email->subject('Notificación de activación');
        	$this->email->message("Su pedido para el candidato '{$pedido->candidato}', DNI '{$pedido->dni}', vacante '{$pedido->vacante}' ha sido generado correctamente. Recibirá un email cuando el mismo se encuentre finalizado.");
        	$this->email->send();

        	#notificar proveedor y analista por email
        	$data = NULL;
        	$data['pedido'] = $pedido;
            $this->db->select('email', FALSE);
            $this->db->where('id', $pedido->id_proveedor);
            $this->db->or_where('id', $pedido->id_analista);
            $users = $this->db->get('users', 2)->result();

            foreach ($users as $user) {

            	$this->email->from('notificaciones@consultar-rrhh.com', 'no-responder');
            	$this->email->to($user->email);
            	
            	$this->email->subject('Notificación de asignación');
            	$message = $this->load->view('mails/nuevo_pedido', $data, TRUE);
            	$this->email->message($message);
            	
            	$this->email->send();
            }
		}

		if ($this->input->post('nuevo_estado', TRUE) == 'finalizado') {
			#get email subcliente y notificar
			$this->load->model('general_model');

			$pedido = $this->general_model->get_pedido($this->input->post('idpedido', TRUE));
			#get subcliente
			$subcliente = $this->db->get_where('subclientes', array('idsubcliente' => $pedido->id_subcliente), 1)->row();

			$this->email->from('notificaciones@consultar-rrhh.com', 'no-responder');
        	$this->email->to($subcliente->email);
        	
        	$this->email->subject('Notificación de pedido finalizado');
        	$this->email->message("Su pedido para el candidato '{$pedido->candidato}', DNI '{$pedido->dni}', vacante '{$pedido->vacante}' ha sido finalizado. Puede ingresar desde la aplicación para verificar el resultado del pedido.");
        	$this->email->send();
		}

		$this->save_chat($chat);	
	}

	public function get_factura($idfactura)
	{
		$factura = $this->db->get_where('facturas', array('idfactura' => $idfactura), 1)->row();

		$this->db->select_sum('pedidos.precio', 'total_factura');
		$this->db->join('pedidos', 'pedidos.idpedido = facturas_pedidos.id_pedido', 'left');
		$this->db->where('facturas_pedidos.id_factura', $idfactura);
		$factura->total_factura = $this->db->get('facturas_pedidos', 1)->row()->total_factura;

		echo json_encode($factura);
	}

	public function update_factura()
	{

		$data = [
			'numero_factura' => $this->input->post('numero_factura', TRUE),
			'total_factura' => $this->input->post('total_factura', TRUE)
		];

		$where = [
			'idfactura' => $this->input->post('idfactura', TRUE)
		];

		$this->db->update('facturas', $data, $where);

		$this->json_response();
	}

	public function update_estado_pedidos()
	{
		if (!$this->input->post()) {
			$this->json_response(FALSE);
		}

		foreach ($this->input->post('pedidos', TRUE) as $idpedido) {
			$condition = array(
				'idpedido' => $idpedido, 
				'estado' => $this->input->post('estado_actual', TRUE)
			);

			$data = array(
				'estado' => $this->input->post('nuevo_estado', TRUE)
			);

			$chat = array(
				'id_pedido' => $idpedido,
				'usuario' => $this->auth->get_user()->razon,
				'comentario' => "SYSTEM: Estado cambiado - <b>{$this->input->post('estado_actual', TRUE)}</b> a <b>{$this->input->post('nuevo_estado', TRUE)}</b>"
			);

			$this->db->update('pedidos', $data, $condition);

			if ($this->input->post('nuevo_estado', TRUE) == 'activado') {
				#get email subcliente y notificar
				$this->load->model('general_model');

				$pedido = $this->general_model->get_pedido($idpedido);
				#get subcliente
				$subcliente = $this->db->get_where('subclientes', array('idsubcliente' => $pedido->id_subcliente), 1)->row();

				$this->email->from('notificaciones@consultar-rrhh.com', 'no-responder');
	        	$this->email->to($subcliente->email);
	        	
	        	$this->email->subject('Notificación de activación');
	        	$this->email->message("Su pedido para el candidato '{$pedido->candidato}', DNI '{$pedido->dni}', vacante '{$pedido->vacante}' ha sido generado correctamente. Recibirá un email cuando el mismo se encuentre finalizado.");
	        	$this->email->send();
			}

			if ($this->input->post('nuevo_estado', TRUE) == 'finalizado') {
				#get email subcliente y notificar
				$this->load->model('general_model');

				$pedido = $this->general_model->get_pedido($idpedido);
				#get subcliente
				$subcliente = $this->db->get_where('subclientes', array('idsubcliente' => $pedido->id_subcliente), 1)->row();

				$this->email->from('notificaciones@consultar-rrhh.com', 'no-responder');
	        	$this->email->to($subcliente->email);
	        	
	        	$this->email->subject('Notificación de pedido finalizado');
	        	$this->email->message("Su pedido para el candidato '{$pedido->candidato}', DNI '{$pedido->dni}', vacante '{$pedido->vacante}' ha sido finalizado. Puede ingresar desde la aplicación para verificar el resultado del pedido.");
	        	$this->email->send();
			}

			$this->save_chat($chat, FALSE);
		}	

		$this->json_response(TRUE);
	}

	public function add_cliente_subcliente()
	{
		if ($this->db->insert('subclientes_clientes', array('id_cliente' => $this->input->post('id_cliente', TRUE), 'id_subcliente' => $this->input->post('id_subcliente', TRUE)))) {
			$this->json_response(TRUE);
		} else {
			$this->json_response(FALSE);
		}
	}

	public function get_subclientes_autorizados()
	{
		$this->db->join('clientes', 'clientes.idcliente = subclientes_clientes.id_cliente', 'left');
		$query = $this->db->get_where('subclientes_clientes', array('id_subcliente' => $this->input->post('id_subcliente', TRUE)));

		if ($query->num_rows() > 0) {
			echo json_encode($query->result());
		} else {
			echo json_encode(FALSE);
		}
	}

	public function buscar_pedidos_no_facturados($idcliente)
	{
		$idcliente = $this->input->post('idcliente', TRUE);
		$start = $this->input->post('start', TRUE);
		$end = $this->input->post('end', TRUE);

		$this->db->select('*, pedidos.creado as creado, clientes.razon as cliente, users.razon as proveedor', FALSE);
		$this->db->join('clientes', 'clientes.idcliente = pedidos.id_cliente', 'left');
		$this->db->join('subclientes', 'subclientes.idsubcliente = pedidos.id_subcliente', 'left');
		$this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
		$this->db->join('users', 'users.id = pedidos.id_proveedor', 'left');
		$this->db->where('pedidos.creado >=', $start);
		$this->db->where('pedidos.creado <=', $end);
		$this->db->where('pedidos.id_cliente', $idcliente);
		$this->db->where('facturado', 0);

		$query = $this->db->get('pedidos');

		if ($query->num_rows() > 0) {
			$pedidos = new stdClass();
			$pedidos->pedidos = $query->result();
			$pedidos->cantidad = count($pedidos->pedidos);
			$pedidos->total = $this->db->where('pedidos.creado >=', $start)->where('pedidos.creado <=', $end)->where('pedidos.id_cliente', $idcliente)->where('facturado', 0)->select_sum('precio', 'total')->get('pedidos')->row()->total;
		} else {
			$pedidos = false;
		}

		echo json_encode($pedidos);
	}

	public function buscar_pedidos_no_ordenados($idproveedor)
	{
		$this->db->select('*, users.razon as proveedor', FALSE);
		$this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
		$this->db->join('users', 'users.id = pedidos.id_proveedor', 'left');
		$query = $this->db->get_where('pedidos', array('pedidos.id_proveedor' => $idproveedor, 'ordenado' => 0));

		if ($query->num_rows() > 0) {
			$pedidos = $query->result();
		} else {
			$pedidos = false;
		}

		echo json_encode($pedidos);
	}

	public function save_chat($chat = NULL, $end = TRUE)
	{
		if (is_null($chat)) {

			$chat = new stdClass();
			$chat->id_pedido = $this->input->post('idpedido_nm', TRUE);
			$chat->usuario = $this->input->post('usuario_nm', TRUE);
			$chat->comentario = $this->input->post('mensaje_nm', TRUE);

			#get email subcliente y notificar
			$this->load->model('general_model');

			$pedido = $this->general_model->get_pedido($chat->id_pedido);
			#get subcliente
			$subcliente = $this->db->get_where('subclientes', array('idsubcliente' => $pedido->id_subcliente), 1)->row();
			#get proveedor
			$proveedor = $this->general_model->get_proveedor($pedido->id_proveedor);

			#get analista
			$analista = $this->general_model->get_analista($pedido->id_analista);


        	$data['usuario'] = $chat->usuario;
        	$data['comentario'] = $chat->comentario;
        	$data['pedido'] = $pedido;

        	$message = $this->load->view('mails/nuevo_mensaje', $data, TRUE);
        	$message_cliente = $this->load->view('mails/nuevo_mensaje_cliente', $data, TRUE);

        	$this->email->from('notificaciones@consultar-rrhh.com', 'no-responder');        	
        	$this->email->subject('Nuevo mensaje en pedido');
        	$this->email->to($proveedor->email);
        	$this->email->message($message);
        	@$this->email->send(FALSE);

        	$this->email->from('notificaciones@consultar-rrhh.com', 'no-responder');        	
        	$this->email->subject('Nuevo mensaje en pedido');
        	$this->email->to($analista->email);
        	$this->email->message($message);
        	@$this->email->send(FALSE);

        	$this->email->from('notificaciones@consultar-rrhh.com', 'no-responder');        	
        	$this->email->subject('Nuevo mensaje en pedido');
        	$this->email->to($subcliente->email);
        	$this->email->message($message_cliente);
        	@$this->email->send(FALSE);

        	// $this->email->from('notificaciones@consultar-rrhh.com', 'debug del sistema');        	
        	// $this->email->subject('debug de mensaje en pedido');
        	// $this->email->to('carlos.rivera@consultar-rrhh.com');
        	// $this->email->message($debug);
        	// $this->email->send();

		}
		
		if ($end) {
			$this->db->insert('chats', $chat);
			$response = array(
	        'class' => 'modal fade modal-success',
	        'status' => 'success',
	        'message' => 'Operación completada correctamente',
	        'icon' => 'fa fa-check fa-4x block text-center',
	        'lq' => $this->db->last_query(),
	        // 'pedido' => $pedido,
	        // 'subcliente' => $subcliente,
	        // 'proveedor' => $proveedor,
	        // 'analista' => $analista
	      	);
	      	die(json_encode($response));
		}
			
	}

	private function json_response($success = TRUE)
	{
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
	        // 'message' => sprintf('%s <br>(Code %s) - %s', 'Ha ocurrido un error al guardar el registro.' ,$this->db->error()['code'], $this->db->error()['message']),
	        'icon' => 'fa fa-close fa-4x block text-center'
	      );
	    }

	    die(json_encode($response));
	}

}

/* End of file Actions.php */
/* Location: ./application/controllers/Actions.php */