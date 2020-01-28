<?php 
//Incluímos inicialmente la conexión a la base de datos
error_reporting(E_ALL);
ini_set('display_errors', '1');// Motrar todos los errores de PHP
ini_set('error_reporting', E_ALL);

require "../config/conexion.php";

Class Prestamos
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idcliente,$monto,$monto_total,$id_indexdb,$id_ruta,$created_by,$id_empresa)
	{
		$sql="INSERT INTO prestamos(idcliente,monto,monto_total,condicion,fecha_prestamo,id_indexdb,ID_RUTA,created_by,ID_EMPRESA)
		VALUES ('$idcliente','$monto','$monto_total','1',now(),$id_indexdb,$id_ruta,$created_by,$id_empresa)";
		return ejecutarConsulta($sql);
	}
	public function eliminar($idprestamo)
	{
		$sql="DELETE FROM prestamos WHERE idprestamo = '$idprestamo'";
		return ejecutarConsulta($sql);
	}


	public function insertarcliente($nombre,$cedula,$idruta,$iduser,$id_empresa)
	{
		$sql="INSERT INTO clientes (nombre,fecha_cliente,condicion,cedula,ID_RUTA,created_by,ID_EMPRESA)
		VALUES ('$nombre',now(),'1',$cedula,$idruta,$iduser,$id_empresa)";
		return ejecutarConsulta($sql);
	}

	public function leer_prestamos()
	{
		$sql = "select * from prestamos where condicion ='1'";
		return ejecutarConsulta($sql);
	}
	public function leer_abonos()
	{
		$sql = "select * from abonos";
		return ejecutarConsulta($sql);
	}
	public function insertarabono($idprestamo,$abonos)
	{
		$sql="INSERT INTO abonos(idprestamo,abono,fecha_abono)
		VALUES ('$idprestamo','$abonos',now())";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idprestamo,$monto,$monto_total)
	{
		$sql="UPDATE prestamos SET monto='$monto',monto_total='$monto_total' WHERE idprestamo='$idprestamo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idprestamo)
	{
		$sql="UPDATE prestamos SET condicion='0' WHERE idprestamo='$idprestamo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idprestamo)
	{
		$sql="UPDATE prestamos SET condicion='1' WHERE idprestamo='$idprestamo'";
		return ejecutarConsulta($sql);

	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idprestamo)
	{
		$sql="SELECT * FROM prestamos WHERE idprestamo='$idprestamo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($idruta)
	{
		$sql="SELECT p.idprestamo,p.idcliente,p.fecha_prestamo,c.nombre,p.monto,p.monto_total,p.condicion FROM prestamos p INNER JOIN clientes c ON p.idcliente=c.cedula where p.ID_RUTA='$idruta'";
		return ejecutarConsulta($sql);		
	}

	public function listarabonos($idprestamo)
	{
		$sql="SELECT * from abonos where idprestamo='$idprestamo'";
		return ejecutarConsulta($sql);		
	}
}

?>