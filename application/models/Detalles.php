<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalles extends CI_Model {

	public $desde;
	public $hasta;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function get_detalles_activos_pasivos($desde, $hasta)
	{
		$this->desde = $desde;
		$this->hasta = $hasta;

		$detalles = new stdClass();

		#total ingresos
		$this->db->select_sum('precio', 'ingresos_brutos');
		$this->db->select_sum('costo', 'costos_variables_brutos');
		$this->base();
		$response = $this->db->get('pedidos')->row();

		#filtra por facturado
		$this->db->select_sum('precio', 'ingresos_brutos_facturados');
		$this->db->select_sum('costo', 'costos_variables_brutos_facturados');
		$this->db->where('facturado', true);
		$this->base();
		$r = $this->db->get('pedidos')->row();
		$response = (object) array_merge((array) $response, (array) $r);

		#filtra por facturado y pagado
		$this->db->select_sum('precio', 'ingresos_brutos_facturados_pagados');
		$this->db->select_sum('costo', 'costos_variables_brutos_facturados_pagados');
		$this->db->join('facturas_pedidos', 'facturas_pedidos.id_pedido = pedidos.idpedido', 'left');
		$this->db->join('facturas', 'facturas.idfactura = facturas_pedidos.id_factura', 'left');
		$this->db->where('pedidos.facturado', true);
		$this->db->where('facturas.pagada', true);
		$this->base();
		$r = $this->db->get('pedidos')->row();
		$response = (object) array_merge((array) $response, (array) $r);

		#filtra por facturado y pagado
		$this->db->select_sum('precio', 'ingresos_brutos_facturados_pagados');
		$this->db->select_sum('costo', 'costos_variables_brutos_facturados_pagados');
		$this->db->join('facturas_pedidos', 'facturas_pedidos.id_pedido = pedidos.idpedido', 'left');
		$this->db->join('facturas', 'facturas.idfactura = facturas_pedidos.id_factura', 'left');
		$this->db->where('pedidos.facturado', true);
		$this->db->where('facturas.pagada', true);
		$this->base();
		$r = $this->db->get('pedidos')->row();
		$response = (object) array_merge((array) $response, (array) $r);

		#cv con oc
		$this->db->select_sum('pedidos.costo', 'costos_variables_brutos_con_oc');
		$this->db->join('ordenes_pedidos', 'ordenes_pedidos.id_pedido = pedidos.idpedido', 'left');
		$this->db->join('ordenes_compra', 'ordenes_compra.idorden = ordenes_pedidos.id_orden', 'left');
		$this->base();
		$r = $this->db->get('pedidos')->row();
		$response = (object) array_merge((array) $response, (array) $r);

		#cv con oc y facturada
		$this->db->select_sum('pedidos.costo', 'costos_variables_brutos_con_oc_facturada');
		$this->db->join('ordenes_pedidos', 'ordenes_pedidos.id_pedido = pedidos.idpedido', 'left');
		$this->db->join('ordenes_compra', 'ordenes_compra.idorden = ordenes_pedidos.id_orden', 'left');
		$this->base();
		$this->db->where('ordenes_compra.facturada', TRUE);
		$r = $this->db->get('pedidos')->row();
		$response = (object) array_merge((array) $response, (array) $r);

		#cv con oc y facturada y pagada
		$this->db->select_sum('pedidos.costo', 'costos_variables_brutos_con_oc_facturada_pagada');
		$this->db->join('ordenes_pedidos', 'ordenes_pedidos.id_pedido = pedidos.idpedido', 'left');
		$this->db->join('ordenes_compra', 'ordenes_compra.idorden = ordenes_pedidos.id_orden', 'left');
		$this->base();
		$this->db->where('ordenes_compra.facturada', TRUE);
		$this->db->where('ordenes_compra.pagada', TRUE);
		$r = $this->db->get('pedidos')->row();
		$response = (object) array_merge((array) $response, (array) $r);

		#gastos fijos totales en rango de fechas
		$this->db->select_sum('total', 'gastos_totales');
		$this->db->where('gastos.fecha >=', $this->desde);
		$this->db->where('gastos.fecha <=', $this->hasta);
		$r = $this->db->get('gastos')->row();
		$response = (object) array_merge((array) $response, (array) $r);

		#gastos fijos totales en rango de fechas pagadas
		$this->db->select_sum('total', 'gastos_totales_pagados');
		$this->db->where('gastos.fecha >=', $this->desde);
		$this->db->where('gastos.fecha <=', $this->hasta);
		$this->db->where('gastos.pagado', TRUE);
		$r = $this->db->get('gastos')->row();
		$response = (object) array_merge((array) $response, (array) $r);


		return $response;
		// var_dump($this->replace($response));die();
	}

	public function replace($object)
	{
		foreach ($object as $k => $v) {
			if (is_null($v)) {
				$object->$k = 0;
			}
		}
		

		return $object;
	}

	public function base()
	{
		$this->db->where('pedidos.creado >=', $this->desde);
		$this->db->where('pedidos.creado <=', $this->hasta);
	}

}

/* End of file Detalles.php */
/* Location: ./application/models/Detalles.php */