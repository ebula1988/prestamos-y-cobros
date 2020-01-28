<?php 
session_start();
require_once "../modelos/prestamos.php";


$prestamos=new Prestamos();

$idprestamo=isset($_POST["idprestamo"])? limpiarCadena($_POST["idprestamo"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$monto=isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
$monto_total=isset($_POST["monto_total"])? limpiarCadena($_POST["monto_total"]):"";
$abonos = isset($_POST["abonos"])? limpiarCadena($_POST["abonos"]):"";


switch ($_GET["op"]){


	case 'guardaryeditar':


		if (empty($idprestamo)){
			
			$rspta=$prestamos->insertar($idcliente,$monto,$monto_total,-1,$_SESSION['idruta'],$_SESSION['iduser'],$_SESSION['ID_EMPRESA']);
			echo $rspta ? "prestamo registrado" : "prestamo no se pudo registrar";
		}
		else {
			$rspta=$prestamos->editar($idprestamo,$monto,$monto_total);
			if($rspta){
				$resp=$prestamos->insertarabono($idprestamo,$abonos);
				echo $rspta ? "prestamo actualizado" : "prestamo no se pudo actualizar";
			}
			
		}
	break;

	case 'eliminar':

    if (!empty($idprestamo)){
      $rspta=$prestamos->eliminar($idprestamo);
      echo $rspta ? "prestamo eliminado" : "prestamo no se pudo eliminar";
    }

    break;

	case 'insertoffline':
		$id_indexdb=isset($_POST["id"])? limpiarCadena($_POST["id"]):"";
		$rspta=$prestamos->insertar($idcliente,$monto,$monto_total,$id_indexdb);

		if($rspta){
			$id_indexdb_selected = isset($_POST["id_indexdb_selected"])? limpiarCadena($_POST["id_indexdb_selected"]):"";
			if($id_indexdb_selected!=-1){
				$resp=$prestamos->insertarabono(mysqli_insert_id($conexion),$abonos);
			}
		}
		$array   = ["str" => "Objeto Offline Añadido Exitosamente!",
                    "monto"=> $monto,
                	"id_indexdb_selected"=> $id_indexdb_selected];
       	return print(json_encode($array));
	break;
	case 'guardarCliente':
		$cedula = isset($_POST["cedula"])? limpiarCadena($_POST["cedula"]):"";
		$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
		$rspta=$prestamos->insertarcliente($nombre,$cedula,$_SESSION['idruta'],$_SESSION['iduser'],$_SESSION['ID_EMPRESA']);
       	return print "<option value=$cedula>$nombre</option>";
	break;

	case 'desactivar':
		$rspta=$prestamos->desactivar($idprestamo);
 		echo $rspta ? "prestamo Desactivado" : "prestamo no se puede desactivar";
 		break;
	
	case 'activar':
		$rspta=$prestamos->activar($idprestamo);
 		echo $rspta ? "prestamo activado" : "prestamo no se puede activar";
 		break;
	 

	case 'mostrar':
		$rspta=$prestamos->mostrar($idprestamo);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
 		break;

 	case 'leer_prestamos':
 		$result=$prestamos->leer_prestamos();
 		//Codificar el resultado utilizando json
 		while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
    	}
    	echo json_encode($myArray);
 	break;
 	case 'leer_abonos':
 		$result=$prestamos->leer_abonos();
 		//Codificar el resultado utilizando json
 		while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
    	}
    	echo json_encode($myArray);
 	break;
	case 'listar':
	
		$rspta=$prestamos->listar($_SESSION['idruta']);

 		//Vamos a declarar un array
 		$data= Array();
 		$contador = 0;
 		while ($reg=$rspta->fetch_object()){

			$str = '<button class="btn btn-warning" onclick="mostrar('.$reg->idprestamo.','.$contador.')" type="button"><i class="glyphicon glyphicon-usd"></i> Abono</button>';
			if($_SESSION['nivel_adm']==1){
				$str = $str . '<button class="btn btn-danger" id="btnEliminar'.$reg->idprestamo.'" onclick="eliminarPrestamo('.$reg->idprestamo.')" type="button"><i class="fa fa-close"></i></button>';
			}
 			$data[]=array(
 				"0"=>$str.'<img src="../public/img/up.png" class="mini-iconos" onclick="ordenar()">',
 				"1"=>$reg->fecha_prestamo,
 				"2"=>$reg->nombre,
 				"3"=>$reg->monto,
 				"4"=>$reg->monto_total,
 				
 				);
 			$contador++;
 		}

 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

		case 'listarabonos':
		$idpres = $_GET['idpres'];
		$rspta=$prestamos->listarabonos($idpres);
 		//Vamos a declarar un array
 		$data= Array();
 		$i = 1;
 		while ($reg=$rspta->fetch_object()){

 			$data[]=array(
 				"0"=>$i,
 				"1"=>$reg->abono,
 				"2"=>$reg->fecha_abono
 				);
 			$i++;
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;

	case "selectCategoria":
		require_once "../modelos/clientes.php";
		$clientes = new Clientes();

		if($_SESSION['nivel_adm']==1){
			$rspta=$clientes->listar_todos();
		}else{
			$rspta=$clientes->listar($_SESSION['idruta']);
		}

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->cedula . '>' . $reg->nombre . '</option>';
				}
	break;
}
?>