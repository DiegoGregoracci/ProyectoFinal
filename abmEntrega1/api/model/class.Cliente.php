<?php
class Cliente {
	private $nombre, $apellido, $direccion, $tel1, $tel2, $vehiculos;
	
	public function __construct($nombre, $apellido, $direccion, $tel1, $tel2) {
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->direccion = $direccion;
		$this->tel1 = $tel1;
		$this->tel2 = $tel2;
	}

	public getNombre() {
		return $this->nombre;
	}
	public getApellido() {
		return $this->apellido;
	}
	public getNombreCompleto() {
		return $this->nombre + $this->apellido;
	}
	public getVehiculos() {
		return $this->vehiculos;
	}
}
?>