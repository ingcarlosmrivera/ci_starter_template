<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
	}

	#clientes
		public function count_clientes() 
		{
			return $this->db->count_all('clientes');
		}

		public function get_clientes($busqueda = '', $buscar_por = 'idcliente', $ordenar_por = 'idcliente', $limit = 10, $offset = 0) 
		{
			$this->db->order_by($ordenar_por, 'asc');
			$this->db->like($buscar_por, $busqueda, 'both');
			$this->db->limit($limit, $offset);

			$query = $this->db->get('clientes');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function search_clientes($fields = NULL, $busqueda = '', $buscar_por = 'users.id', $ordenar_por = 'users.id', $limit = 10, $offset = 0) 
		{
			if (!is_null($fields)) {
				$this->db->select($fields);
			}
			$this->db->order_by($ordenar_por, 'asc');
			$this->db->like($buscar_por, $busqueda, 'both');
			$this->db->limit($limit, $offset);

			$query = $this->db->get('clientes');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function search_clientes_pendientes_factura($fields = NULL, $busqueda = '', $start, $end, $buscar_por = 'users.id', $ordenar_por = 'users.id', $limit = 10, $offset = 0) 
		{
			if (!is_null($fields)) {
				$this->db->select($fields);
			}

			$this->db->join('clientes', 'clientes.idcliente = pedidos.id_cliente');
			$this->db->order_by($ordenar_por, 'asc');
			$this->db->like($buscar_por, $busqueda, 'both');
			$this->db->where('pedidos.creado >=', $start);
			$this->db->where('pedidos.creado <=', $end);
			$this->db->where('pedidos.facturado', FALSE);
			$this->db->group_by('pedidos.id_cliente');
			$this->db->limit($limit, $offset);

			$query = $this->db->get('pedidos');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function get_subclientes($id_cliente) 
		{
			$this->db->select('subclientes.idsubcliente, subclientes.nombre, subclientes.email, subclientes.telefono');
			$this->db->join('subclientes', 'subclientes.id_cliente = clientes.idcliente', 'left');
			$this->db->where('subclientes.id_cliente', $id_cliente);

			$query = $this->db->get('clientes');

			$this->db->select('subclientes.idsubcliente, subclientes.nombre, subclientes.email, subclientes.telefono');
			$this->db->join('subclientes_clientes', 'subclientes_clientes.id_subcliente = subclientes.idsubcliente', 'left');
			$this->db->where('subclientes_clientes.id_cliente', $id_cliente);

			$query2 = $this->db->get('subclientes');

			if ($query->num_rows() > 0) {
				$query = $query->result();

				// now other
				if ($query2->num_rows() > 0) {
					$query2 = $query2->result();
					$query = array_merge($query, $query2);
				}

				return array_unique($query, SORT_REGULAR);
			}

			// now other
			if ($query2->num_rows() > 0) {
				return $query2->result();

			}

			return FALSE;
		}

		public function get_cliente($idcliente) 
		{
			$this->db->where('idcliente', $idcliente);
			$this->db->limit(1);
			$query = $this->db->get('clientes');

			if ($query->num_rows() > 0) {
				return $query->row();
			}

			return FALSE;
		}

		public function add_cliente($cliente) 
		{
			return $this->db->insert('clientes', $cliente);
		}

		public function update_cliente($cliente) 
		{
			$this->db->where('idcliente', $cliente->idcliente);
			return $this->db->update('clientes', $cliente);
		}

		public function add_subcliente($subcliente) 
		{
			return $this->db->insert('subclientes', $subcliente);
		}

		public function delete_subcliente($id_cliente, $id_subcliente)
		{
			$this->db->where('id_cliente', $id_cliente);
			$this->db->where('idsubcliente', $id_subcliente);

			return $this->db->delete('subclientes');
		}

	#servicios
		public function add_servicio($servicio) 
		{
			return $this->db->insert('servicios', $servicio);
		}

		public function get_servicios()
		{
			$this->db->order_by('servicio', 'asc');

			$query = $this->db->get('servicios');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function get_servicio($idservicio) 
		{
			$this->db->where('idservicio', $idservicio);
			$this->db->limit(1);
			$query = $this->db->get('servicios');

			if ($query->num_rows() > 0) {
				return $query->row();
			}

			return FALSE;
		}

		public function update_servicio($servicio) 
		{
			$this->db->where('idservicio', $servicio->idservicio);
			return $this->db->update('servicios', $servicio);
		}

		public function search_servicios($busqueda = '', $id_cliente = NULL, $buscar_por = 'idservicio', $ordenar_por = 'idservicio', $limit = 10, $offset = 0) 
		{
			$this->db->order_by($ordenar_por, 'asc');
			$this->db->like($buscar_por, $busqueda, 'both');
			$this->db->limit($limit, $offset);

			if (!is_null($id_cliente)) {
				$this->db->where('servicios_clientes.id_cliente', $id_cliente, FALSE);
				$this->db->join('servicios_clientes', 'servicios_clientes.id_servicio = servicios.idservicio', 'left');
			}


			$query = $this->db->get('servicios');


			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function count_servicios($busqueda, $buscar_por) 
		{
			$this->db->like($buscar_por, $busqueda, 'both');
			return $this->db->count_all_results('servicios');
		}

		public function get_medicos() 
		{
			$query = $this->db->get('medicos');


			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function get_servicios_cliente($idcliente)
		{
			$this->db->order_by('servicio', 'asc');
			$this->db->join('servicios_clientes', 'servicios_clientes.id_servicio = servicios.idservicio', 'left');
			$this->db->where('servicios_clientes.id_cliente', $idcliente);

			$query = $this->db->get('servicios');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function add_servicio_cliente($data)
		{
			return $this->db->insert('servicios_clientes', $data);
		}

		public function get_servicios_proveedores($idproveedor)
		{
			$this->db->order_by('servicio', 'asc');
			$this->db->join('servicios_proveedores', 'servicios_proveedores.id_servicio = servicios.idservicio', 'left');
			$this->db->where('servicios_proveedores.id_proveedor', $idproveedor);

			$query = $this->db->get('servicios');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function add_servicio_proveedores($data)
		{
			return $this->db->insert('servicios_proveedores', $data);
		}

		public function delete_servicio_proveedores($id_proveedor, $id_servicio)
		{
			$this->db->where('id_proveedor', $id_proveedor);
			$this->db->where('id_servicio', $id_servicio);

			return $this->db->delete('servicios_proveedores');
		}

		public function delete_servicio_cliente($id_cliente, $id_servicio)
		{
			$this->db->where('id_cliente', $id_cliente);
			$this->db->where('id_servicio', $id_servicio);

			return $this->db->delete('servicios_clientes');
		}

	#proveedores
		public function get_proveedores($busqueda = '', $buscar_por = 'users.id', $ordenar_por = 'users.id', $limit = 10, $offset = 0) 
		{
			$this->db->select('*, users.id as idproveedor', FALSE);
			$this->db->order_by($ordenar_por, 'asc');
			$this->db->like($buscar_por, $busqueda, 'both');
			$this->db->limit($limit, $offset);

			#join provincias y localidades
			$this->db->join('provincias', 'provincias.idprovincia = users.id_provincia', 'left');
			$this->db->join('localidades', 'localidades.idlocalidad = users.id_localidad', 'left');
			#join con grupo proveedores
			$this->db->join('users_groups', 'users_groups.user_id = users.id', 'left');
			$this->db->join('groups', 'users_groups.group_id = groups.id', 'left');
			#filtrar proveedores
			$this->db->where('groups.name', 'proveedores');

			$query = $this->db->get('users');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function search_proveedores($fields = NULL, $busqueda = '', $buscar_por = 'users.id', $ordenar_por = 'users.id', $limit = 10, $offset = 0) 
		{
			if (!is_null($fields)) {
				$this->db->select($fields);
			}
			$this->db->order_by($ordenar_por, 'asc');
			$this->db->like($buscar_por, $busqueda, 'both');
			$this->db->limit($limit, $offset);

			#join provincias y localidades
			$this->db->join('provincias', 'provincias.idprovincia = users.id_provincia', 'left');
			$this->db->join('localidades', 'localidades.idlocalidad = users.id_localidad', 'left');
			#join con grupo proveedores
			$this->db->join('users_groups', 'users_groups.user_id = users.id', 'left');
			$this->db->join('groups', 'users_groups.group_id = groups.id', 'left');
			#filtrar proveedores
			$this->db->where('groups.name', 'proveedores');

			$query = $this->db->get('users');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function get_proveedor($idproveedor) 
		{
			$this->db->select('*, users.id as idproveedor', FALSE);
			#join provincias y localidades
			$this->db->join('provincias', 'provincias.idprovincia = users.id_provincia', 'left');
			$this->db->join('localidades', 'localidades.idlocalidad = users.id_localidad', 'left');
			
			$this->db->where('users.id', $idproveedor);

			$query = $this->db->get('users', 1);

			if ($query->num_rows() > 0) {
				return $query->row();
			}

			return FALSE;
		}

		public function get_analista($idanalista) 
		{
			$this->db->select('*, users.id as idanalista', FALSE);
			#join provincias y localidades
			$this->db->join('provincias', 'provincias.idprovincia = users.id_provincia', 'left');
			$this->db->join('localidades', 'localidades.idlocalidad = users.id_localidad', 'left');

			$this->db->where('users.id', $idanalista);

			$query = $this->db->get('users', 1);

			if ($query->num_rows() > 0) {
				return $query->row();
			}

			return FALSE;
		}

		public function count_proveedores($busqueda, $buscar_por) 
		{
			$this->db->like($buscar_por, $busqueda, 'both');
			#join con grupo proveedores
			$this->db->join('users_groups', 'users_groups.user_id = users.id', 'left');
			$this->db->join('groups', 'users_groups.group_id = groups.id', 'left');
			#filtrar proveedores
			$this->db->where('groups.name', 'proveedores');

			return $this->db->count_all_results('users');
		}

	#prepedidos
		public function add_prepedido($prepedido)
		{
			return $this->db->insert('prepedidos', $prepedido);
		}

		public function get_prepedidos($idsubcliente = NULL)
		{
			$this->db->select('*, clientes.razon as cliente, subclientes.nombre as subcliente, prepedidos.creado as creado, prepedidos.email as email', FALSE);
			$this->db->where('procesado', 0);
			if (!is_null($idsubcliente)) {
				$this->db->where('prepedidos.id_subcliente', $idsubcliente);
			}
			$this->db->order_by('idprepedido', 'asc');
			#joins
			$this->db->join('clientes', 'clientes.idcliente = prepedidos.id_cliente', 'left');
			$this->db->join('subclientes', 'subclientes.idsubcliente = prepedidos.id_subcliente', 'left');
			$this->db->join('servicios', 'servicios.idservicio = prepedidos.id_servicio', 'left');
			$this->db->join('provincias', 'provincias.idprovincia = prepedidos.id_provincia', 'left');
			$this->db->join('localidades', 'localidades.idlocalidad = prepedidos.id_localidad', 'left');

			$query = $this->db->get('prepedidos');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function get_prepedido($idprepedido)
		{
			$this->db->select('*, clientes.razon as cliente, subclientes.nombre as subcliente, provincias.provincia, localidades.localidad, prepedidos.email as email, prepedidos.telefono as telefono, prepedidos.direccion as direccion, prepedidos.id_cliente as id_cliente', FALSE);
			$this->db->where('procesado', 0);
			$this->db->order_by('idprepedido', 'asc');
			#joins
			$this->db->join('clientes', 'clientes.idcliente = prepedidos.id_cliente', 'left');
			$this->db->join('subclientes', 'subclientes.idsubcliente = prepedidos.id_subcliente', 'left');
			$this->db->join('servicios', 'servicios.idservicio = prepedidos.id_servicio', 'left');
			$this->db->join('provincias', 'provincias.idprovincia = prepedidos.id_provincia', 'left');
			$this->db->join('localidades', 'localidades.idlocalidad = prepedidos.id_localidad', 'left');

			$this->db->where('idprepedido', $idprepedido);
			$this->db->where('procesado', 0);

			$query = $this->db->get('prepedidos');

			if ($query->num_rows() > 0) {
				return $query->row();
			}

			return FALSE;
		}

	#pedidos
		public function add_pedido($pedido, $prepedido) 
		{
			if (!is_null($prepedido)) {
				$data = array(
					'procesado' => 1
				);

				$this->db->where('idprepedido', $prepedido);
				$this->db->update('prepedidos', $data);
			}

			return $this->db->insert('pedidos', $pedido);
		}

		public function edit_pedido($pedido, $idpedido) 
		{
			$this->db->where('idpedido', $idpedido);
			return	$this->db->update('pedidos', $pedido);
			
		}

		public function get_chat_history($id_pedido)
		{
		    $this->db->where('id_pedido', $id_pedido);
		    $this->db->order_by('fecha', 'asc');
		    $query = $this->db->get('chats');
		    if ($query->num_rows() > 0) {
		      return $query->result();
		    }

		    return false;
		}

		public function get_pedido($idpedido) 
		{
			if ($this->auth->logged_in() && !$this->auth->is_admin()) {
				$id = $this->auth->get_user()->id;
				$this->db->where("(pedidos.id_proveedor = '$id' || pedidos.id_analista = '$id')");
			}

			$this->db->select('pedidos.idpedido, pedidos.creado, pedidos.id_servicio, pedidos.id_cliente, pedidos.id_subcliente, pedidos.id_provincia, pedidos.id_localidad, pedidos.id_proveedor, pedidos.id_analista, servicios.servicio, pedidos.estado, pedidos.candidato, pedidos.dni, pedidos.costo, pedidos.precio, pedidos.telefono, pedidos.email, pedidos.vacante, pedidos.direccion, pedidos.requiere_oc, pedidos.oc, provincias.provincia, localidades.localidad, clientes.razon as cliente, subclientes.nombre as subcliente, (SELECT users.razon from users where id = pedidos.id_proveedor) as proveedor, (SELECT users.razon from users where id = pedidos.id_analista) as analista, pedidos.observaciones');
			$this->db->join('clientes', 'clientes.idcliente = pedidos.id_cliente', 'left');
			$this->db->join('subclientes', 'subclientes.idsubcliente = pedidos.id_subcliente', 'left');
			$this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
			$this->db->join('provincias', 'provincias.idprovincia = pedidos.id_provincia', 'left');
			$this->db->join('localidades', 'localidades.idlocalidad = pedidos.id_localidad', 'left');

			$this->db->where('pedidos.idpedido', $idpedido);

			$query = $this->db->get('pedidos', 1);
			
			if ($query->num_rows() > 0) {
				$pedido = $query->row();

				#check adjuntos
				$query = $this->db->get_where('adjuntos', array('id_pedido' => $idpedido));
				if ($query->num_rows() > 0) {
					$pedido->adjuntos = $query->result();
				} else {
					$pedido->adjuntos = FALSE;
				}

				
				return $pedido;
			}

			return FALSE;
		}

		public function count_pedidos($status, $busqueda, $buscar_por, $flagged = FALSE, $extra = FALSE) 
		{
			if (!$this->auth->is_admin()) {
				$id = $this->auth->get_user()->id;
				$this->db->where("(pedidos.id_proveedor = '$id' || pedidos.id_analista = '$id')");
			}

			$this->db->select('idpedido, pedidos.creado, pedidos.candidato, pedidos.dni, pedidos.estado, clientes.razon as cliente, (SELECT razon from users where pedidos.id_proveedor = users.id) as proveedor, servicios.servicio, subclientes.nombre as subcliente, (SELECT razon from users where pedidos.id_analista = users.id) as analista', FALSE);
			$this->db->join('clientes', 'clientes.idcliente = pedidos.id_cliente', 'left');
			$this->db->join('subclientes', 'subclientes.idsubcliente = pedidos.id_subcliente', 'left');
			$this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');

			if ($extra) {
				$this->db->where($extra);
			}

			if ($buscar_por == 'proveedor' || $buscar_por == 'analista') {
				$this->db->having("$buscar_por LIKE '%$busqueda%'");
			} else {
				$this->db->like($buscar_por, $busqueda, 'both');
			}	

			if ($status != 'all') {
				$this->db->where('pedidos.estado', $status);
			}

			if ($flagged) {
				if ($flagged == 'flagged') {
					$this->db->where('flagged', TRUE);
				} elseif ($flagged == 'no_flagged') {
					$this->db->where('flagged', FALSE);
				}
			}

			return $this->db->get('pedidos')->num_rows();
		}

		public function search_pedidos($status, $busqueda = '', $buscar_por = 'users.id', $ordenar_por = 'pedidos.idpedido', $limit = 10, $offset = 0 , $flagged = FALSE, $extra = FALSE) 
		{
			if (!$this->auth->is_admin()) {
				$id = $this->auth->get_user()->id;
				$this->db->where("(pedidos.id_proveedor = '$id' || pedidos.id_analista = '$id')");
			}

			$this->db->select('idpedido, pedidos.creado,pedidos.flagged,  pedidos.candidato, pedidos.dni, pedidos.estado, clientes.razon as cliente, provincias.provincia, localidades.localidad, (SELECT razon from users where pedidos.id_proveedor = users.id) as proveedor, servicios.servicio, subclientes.nombre as subcliente, (SELECT razon from users where pedidos.id_analista = users.id) as analista', FALSE);
			$this->db->join('clientes', 'clientes.idcliente = pedidos.id_cliente', 'left');
			$this->db->join('subclientes', 'subclientes.idsubcliente = pedidos.id_subcliente', 'left');
			$this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
			$this->db->join('provincias', 'provincias.idprovincia = pedidos.id_provincia', 'left');
			$this->db->join('localidades', 'localidades.idlocalidad = pedidos.id_localidad', 'left');
			$this->db->order_by($ordenar_por, 'desc');

			if ($extra) {
				$this->db->where($extra);
			}

			if ($buscar_por == 'proveedor' || $buscar_por == 'analista') {
				$this->db->having("$buscar_por LIKE '%$busqueda%'");
			} else {
				$this->db->like($buscar_por, $busqueda, 'both');
			}	

			if ($status != 'all') {
				$this->db->where('pedidos.estado', $status);
			}

			if ($flagged) {
				if ($flagged == 'flagged') {
					$this->db->where('flagged', TRUE);
					$this->db->order_by('flagged', 'desc');
				} elseif ($flagged == 'no_flagged') {
					$this->db->where('flagged', FALSE);
				}
			}
				
			$this->db->limit($limit, $offset);

			$query = $this->db->get('pedidos');


			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

	#varios
		public function get_cotizaciones($idcliente)
		{
			$this->db->where('id_cliente', $idcliente);
			$this->db->order_by('idcotizacion', 'asc');
			$query = $this->db->get('cotizaciones');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}


		public function get_provincias()
		{
			return $this->db->order_by('provincia', 'asc')->get('provincias')->result();
		}

		public function get_localidades($id_provincia)
		{
			return $this->db->order_by('localidad', 'asc')->get_where('localidades', array('id_provincia' => $id_provincia))->result();
		}

		public function get_factura($idfactura)
		{
			$this->db->where('idfactura', $idfactura);
			$factura = $this->db->get('facturas', 1);

			if ($factura->num_rows() > 0) {
				$factura = $factura->row();
				$factura->cliente = $this->db->get_where('clientes', array('idcliente' => $factura->id_cliente), 1)->row();

				#obtener pedidos asociados
				$this->db->join('pedidos', 'pedidos.idpedido = facturas_pedidos.id_pedido', 'left');
				$this->db->join('subclientes', 'subclientes.idsubcliente = pedidos.id_subcliente', 'left');
				$this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
				$this->db->where('facturas_pedidos.id_factura', $factura->idfactura);
				$factura->pedidos = $this->db->get('facturas_pedidos')->result();

				return $factura;
			}

			return false;
		}

		public function count_facturas($busqueda, $buscar_por) 
		{
			$this->db->join('clientes', 'clientes.idcliente = facturas.id_cliente', 'left');
			$this->db->like($buscar_por, $busqueda, 'both');
			return $this->db->count_all_results('facturas');
		}

		public function search_facturas($busqueda = '', $buscar_por = 'facturas.numero_factura', $ordenar_por = 'facturas.fecha', $limit = 10, $offset = 0) 
		{
			$this->db->select("*, (SELECT COUNT(*) FROM facturas_pedidos WHERE idfactura = id_factura) as numero_pedidos");
			$this->db->join('clientes', 'clientes.idcliente = facturas.id_cliente', 'left');
			$this->db->order_by($ordenar_por, 'asc');
			$this->db->like($buscar_por, $busqueda, 'both');
			$this->db->limit($limit, $offset);

			$query = $this->db->get('facturas');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		

		public function count_ordenes($busqueda, $buscar_por, $inicio = FALSE, $fin = FALSE, $facturada = NULL, $pagada = NULL) 
		{
			$this->db->join('users', 'users.id = ordenes_compra.id_proveedor', 'left');
			$this->db->like($buscar_por, $busqueda, 'both');

			if ($inicio && $fin) {
				$this->db->where('ordenes_compra.fecha >=', $inicio);
				$this->db->where('ordenes_compra.fecha <=', $fin);
			}

			if (!is_null($pagada)) {
				$this->db->where('pagada', $pagada);
			}

			if (!is_null($facturada)) {
				$this->db->where('facturada', $facturada);
			}

			return $this->db->count_all_results('ordenes_compra');
		}

		public function search_ordenes($busqueda = '', $buscar_por = 'ordenes_compra.numero_orden', $ordenar_por = 'ordenes_compra.fecha', $limit = 10, $offset = 0, $inicio = FALSE, $fin = FALSE, $facturada = NULL, $pagada = NULL) 
		{
			$this->db->select("*, (SELECT COUNT(*) FROM ordenes_pedidos WHERE id_orden = idorden) as numero_pedidos");
			$this->db->join('users', 'users.id = ordenes_compra.id_proveedor', 'left');
			$this->db->order_by($ordenar_por, 'asc');
			$this->db->like($buscar_por, $busqueda, 'both');
			$this->db->limit($limit, $offset);

			if ($inicio && $fin) {
				$this->db->where('ordenes_compra.fecha >=', $inicio);
				$this->db->where('ordenes_compra.fecha <=', $fin);
				$bet = "fecha BETWEEN '{$inicio}' AND '{$fin}' AND";
			} else {
				$bet = '';
			}

			if (!is_null($pagada)) {
				$this->db->where('pagada', filter_var($pagada, FILTER_VALIDATE_BOOLEAN));
				$bet .= " pagada = {$pagada} AND";
			}

			if (!is_null($facturada)) {
				$this->db->where('facturada', $facturada);
				$bet .= " facturada = {$facturada} AND";
			}

			$query = $this->db->get('ordenes_compra');
			// die($this->db->last_query());

			if ($query->num_rows() > 0) {
				#sum and put flashdata
				$montos['total'] = $this->db->query("SELECT truncate(SUM(total_orden),2) as total, users.razon as proveedor FROM ordenes_compra JOIN users on users.id = ordenes_compra.id_proveedor WHERE $bet $buscar_por like '%$busqueda%'")->row()->total;
				$montos['pagado'] = $this->db->query("SELECT truncate(SUM(total_orden),2) as total, users.razon as proveedor FROM ordenes_compra JOIN users on users.id = ordenes_compra.id_proveedor WHERE $bet pagada = 1 AND $buscar_por like '%$busqueda%'")->row()->total;

				$this->session->set_flashdata('temp', $montos);
				return $query->result();
			}

			return FALSE;
		}

		public function search_gastos($busqueda = '', $buscar_por = 'gastos.cuit', $inicio, $fin, $pagado = NULL, $ordenar_por = 'gastos.idgasto', $limit = 10, $offset = 0)
		{
			$this->db->like($buscar_por, $busqueda);
			$this->db->where('gastos.fecha >=', $inicio);
			$this->db->where('gastos.fecha <=', $fin);
			if (!is_null($pagado)) {
				$pagado = filter_var($pagado, FILTER_VALIDATE_BOOLEAN);
				$this->db->where('gastos.pagado', $pagado);
			}

			$this->db->order_by($ordenar_por, 'desc');
			$this->db->limit($limit, $offset);
			$gastos = $this->db->get('gastos');
echo $this->db->last_query();
			if ($gastos->num_rows() > 0) {
				return $gastos->result();
			}

			return false;
		}

		public function count_gastos($busqueda = '', $buscar_por = 'gastos.cuit', $inicio, $fin, $pagado = NULL) 
		{
			$this->db->like($buscar_por, $busqueda);
			$this->db->where('gastos.fecha >=', $inicio);
			$this->db->where('gastos.fecha <=', $fin);
			if (!is_null($pagado)) {
				$this->db->where('gastos.pagado', $pagado);
			}
			return $this->db->count_all_results('gastos');
		}

		public function add_gasto($gasto)
		{
			return $this->db->insert('gastos', $gasto);
		}

		public function search_pedidos_proveedor($fecha1, $fecha2,  $id_proveedor, $ordenar_por = 'pedidos.creado') 
		{
			$this->db->select('*, clientes.razon as cliente');
			$this->db->select('idpedido, pedidos.creado, pedidos.candidato, clientes.razon as cliente, users.razon as proveedor, pedidos.costo, ordenes_pedidos.id_orden', FALSE);
			$this->db->join('users', 'users.id = pedidos.id_proveedor', 'left');
			$this->db->join('clientes', 'clientes.idcliente = pedidos.id_cliente', 'left');
			$this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
			$this->db->join('ordenes_pedidos', 'ordenes_pedidos.id_pedido = pedidos.idpedido', 'left');
			$this->db->order_by($ordenar_por, 'asc');
			$this->db->where('pedidos.creado >=', $fecha1);
			$this->db->where('pedidos.creado <=', $fecha2 . ' 23:59:59');
			$this->db->where('pedidos.id_proveedor', $id_proveedor);

			$query = $this->db->get('pedidos');

			if ($query->num_rows() > 0) {
				return $query->result();
			}

			return FALSE;
		}

		public function get_orden($idorden)
		{
			$this->db->where('idorden', $idorden);
			$orden = $this->db->get('ordenes_compra', 1);

			if ($orden->num_rows() > 0) {
				$orden = $orden->row();
				$orden->proveedor = $this->db->get_where('users', array('id' => $orden->id_proveedor), 1)->row();

				#obtener pedidos asociados
				$this->db->join('pedidos', 'pedidos.idpedido = ordenes_pedidos.id_pedido', 'left');
				$this->db->join('servicios', 'servicios.idservicio = pedidos.id_servicio', 'left');
				$this->db->where('ordenes_pedidos.id_orden', $orden->idorden);
				$orden->pedidos = $this->db->get('ordenes_pedidos')->result();

				return $orden;
			}

			return false;
		}



}

/* End of file General_model.php */
/* Location: ./application/models/General_model.php */