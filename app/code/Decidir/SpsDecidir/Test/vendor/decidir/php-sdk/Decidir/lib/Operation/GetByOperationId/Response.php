<?php
namespace Decidir\Operation\GetByOperationId;

class Response extends \Decidir\Data\AbstractData {

	protected $fecha_original;
	protected $monto;
	protected $cuotas;
	protected $nro_ticket;
	protected $estado_descri;
	protected $id_motivo;
	protected $motivo_adicional;
	protected $titular;
	protected $id_tipo_doc;
	protected $nro_doc;
	protected $tipo_doc;
	protected $codaut;
	protected $nro_tarj4;
	protected $motivo;
	protected $valdom;
	protected $mail;
	protected $id_site;
	protected $id_transaction_site;
	protected $id_estado;
	protected $id_mediopago;
	protected $mediopago_descri;
	protected $sexo_titular;
	protected $calle;
	protected $nro_puerta;
	protected $paramsitio;
	protected $fechavto_couta1;
	
	public function __construct(array $data) {
		
		$this->setRequiredFields(array(
			"monto" => array(
				"name" => "Monto",
				"original" => "MONTO"
			),
			"estado_descri" => array(
				"name" => "ESTADO_DESCRI",
				"original" => "ESTADO_DESCRI"
			),
			"id_motivo" => array(
				"name" => "IDMOTIVO",
				"original" => "IDMOTIVO"
			),
			"codaut" => array(
				"name" => "CODAUT",
				"original" => "CODAUT"
			),
			"mail" => array(
				"name" => "MAIL",
				"original" => "MAIL"
			),
			"id_site" => array(
				"name" => "IDSITE",
				"original" => "IDSITE"
			),
			"id_estado" => array(
				"name" => "IDESTADO",
				"original" => "IDESTADO"
			),
			"id_mediopago" => array(
				"name" => "IDMEDIOPAGO",
				"original" => "IDMEDIOPAGO"
			),	
			"mediopago_descri" => array(
				"name" => "MEDIOPAGO_DESCRI",
				"original" => "MEDIOPAGO_DESCRI"
			),	
		));
		
		$this->setOptionalFields(array(
			"id_transaction_site" => array(
				"name" => "Id de Transaccion",
				"original" => "IDTRANSACCIONSITE"
			),			
			"fecha_original" => array(
				"name" => "Fecha Original",
				"original" => "FECHA_ORIGINAL"
			),	
			"cuotas" => array(
				"name" => "CUOTAS",
				"original" => "CUOTAS"
			),
			"nro_ticket" => array(
				"name" => "NROTICKET",
				"original" => "NROTICKET"
			),
			"motivo_adicional" => array(
				"name" => "MOTIVO_ADICIONAL",
				"original" => "MOTIVO_ADICIONAL"
			),
			"titular" => array(
				"name" => "TITULAR",
				"original" => "TITULAR"
			),	
			"id_tipo_doc" => array(
				"name" => "IDTIPODOC",
				"original" => "IDTIPODOC"
			),
			"nro_doc" => array(
				"name" => "NRODOC",
				"original" => "NRODOC"
			),
			"tipo_doc" => array(
				"name" => "TIPODOC",
				"original" => "TIPODOC"
			),
			"nro_tarj4" => array(
				"name" => "NROTARJ4",
				"original" => "NROTARJ4"
			),
			"motivo" => array(
				"name" => "MOTIVO",
				"original" => "MOTIVO"
			),
			"valdom" => array(
				"name" => "VALDOM",
				"original" => "VALDOM"
			),
			"sexo_titular" => array(
				"name" => "SEXOTITULAR",
				"original" => "SEXOTITULAR"
			),
			"calle" => array(
				"name" => "CALLE",
				"original" => "CALLE"
			),
			"nro_puerta" => array(
				"name" => "NROPUERTA",
				"original" => "NROPUERTA"
			),
			"paramsitio" => array(
				"name" => "PARAMSITIO",
				"original" => "PARAMSITIO"
			),
			"fechavto_couta1" => array(
				"name" => "FECHAVTOCUOTA1",
				"original" => "FECHAVTOCUOTA1"
			),	
		));
		
		parent::__construct($data);
	}

	public function getFecha_original(){
		return $this->fecha_original;
	}

	public function getMonto(){
		return $this->monto;
	}

	public function getCuotas(){
		return $this->cuotas;
	}

	public function getNro_ticket(){
		return $this->nro_ticket;
	}

	public function getEstado_descri(){
		return $this->estado_descri;
	}

	public function getId_motivo(){
		return $this->id_motivo;
	}

	public function getMotivo_adicional(){
		return $this->motivo_adicional;
	}

	public function getTitular(){
		return $this->titular;
	}

	public function getId_tipo_doc(){
		return $this->id_tipo_doc;
	}

	public function getNro_doc(){
		return $this->nro_doc;
	}

	public function getTipo_doc(){
		return $this->tipo_doc;
	}

	public function getCodaut(){
		return $this->codaut;
	}

	public function getNro_tarj4(){
		return $this->nro_tarj4;
	}

	public function getMotivo(){
		return $this->motivo;
	}

	public function getValdom(){
		return $this->valdom;
	}

	public function getMail(){
		return $this->mail;
	}

	public function getId_site(){
		return $this->id_site;
	}

	public function getId_estado(){
		return $this->id_estado;
	}

	public function getId_mediopago(){
		return $this->id_mediopago;
	}

	public function getMediopago_descri(){
		return $this->mediopago_descri;
	}

	public function getSexo_titular(){
		return $this->sexo_titular;
	}

	public function getCalle(){
		return $this->calle;
	}

	public function getNro_puerta(){
		return $this->nro_puerta;
	}

	public function getParamsitio(){
		return $this->paramsitio;
	}

	public function getFechavto_couta1(){
		return $this->fechavto_couta1;
	}
	
}