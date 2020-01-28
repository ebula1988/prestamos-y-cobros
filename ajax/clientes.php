<?php 
session_start();
require_once "../modelos/clientes.php";

$clientes=new Clientes();

$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$cedula = isset($_POST["cedula"])? limpiarCadena($_POST["cedula"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idcliente)){
			$rspta=$clientes->insertar($nombre,$cedula,$_SESSION['idruta'],$_SESSION['iduser'],$_SESSION['ID_EMPRESA']);
			echo $rspta ? "Cliente registrado" : "Cliente no se pudo registrar";
		}
		else {
			$rspta=$clientes->editar($idcliente,$nombre,$cedula);
			echo $rspta ? "Cliente actualizado" : "Cliente no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$clientes->desactivar($idcliente);
 		echo $rspta ? "Cliente Desactivada" : "Cliente no se puede desactivar";
 		break;
	

	case 'activar':
		$rspta=$clientes->activar($idcliente);
 		echo $rspta ? "Cliente activado" : "Cliente no se puede activar";
 		break;
	

	case 'mostrar':
		$rspta=$clientes->mostrar($idcliente);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;
	break;

	case 'listar':
		if($_SESSION['nivel_adm']==1){
			$rspta=$clientes->listar_todos();
		}else{
			$rspta=$clientes->listar($_SESSION['idruta']);
		}
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcliente.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idcliente.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idcliente.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idcliente.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
 				"2"=>$reg->fecha_cliente,
 				"3"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
}
?>