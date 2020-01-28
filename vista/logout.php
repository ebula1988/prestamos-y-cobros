<?php
  session_start();
  if(isset($_GET['close']) && $_GET['close']=="yes"){
    $_SESSION['iduser'] = NULL;
    $_SESSION['user'] = NULL;
    $_SESSION['nivel_adm'] = NULL;
    $_SESSION['correo'] = NULL;
    $_SESSION['ID_EMPRESA'] = NULL;
    $_SESSION['ID_RUTA'] = NULL;
    unset($_SESSION['iduser']);
    unset($_SESSION['user']);
    unset($_SESSION['nivel_adm']);
    unset($_SESSION['correo']);
    unset($_SESSION['ID_EMPRESA']);
    unset($_SESSION['ID_RUTA']);
    header("Location: login.php");
  }else{
  	header("Location: prestamos.php");
  }

 ?>
