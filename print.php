<?php

$recetas = simplexml_load_file("datos.xml");
if(isset($_POST["submit"])){
  $id = count($recetas->children());
  $titulo = (isset($_POST['titulo']) && strlen($_POST['titulo'])>0)?$_POST['titulo']:'Receta sin titulo';
  $descripcion = (isset($_POST['descripcion'])&& strlen($_POST['descripcion'])>0)?$_POST["descripcion"]:" ";
  $autor = (isset($_POST['usuario'])&& strlen($_POST['usuario'])>0)?$_POST["usuario"]:"Anónimo";
  $mail = (isset($_POST['mail'])&& strlen($_POST['mail'])>0)?$_POST["mail"]:" ";
  $tiempo = (isset($_POST['tiempo'])&& strlen($_POST['tiempo'])>0)?$_POST['tiempo']: "-";
  $personas = (isset($_POST['personas'])&& strlen($_POST['personas'])>0)?$_POST['personas']:'1';
  $imagen = (isset($_POST['img'])&& strlen($_POST['img'])>0)?$_POST['img']:'./images/default.png';
  $calorias = (isset($_POST['calorias'])&& strlen($_POST['calorias'])>0)?$_POST['calorias']:" - ";
  $texto = $_POST['nueva-preparacion-pasos'];
  switch ($_POST['tipo']) {
    case 1:
      $tipo = 'Primero';
      break;
    case 2:
      $tipo = 'Segundo';
      break;
    case 3:
      $tipo = 'Postre';
      break;
    case 4:
      $tipo = 'Bebida';
      break;
  }
  $ingredientes = $_POST['ingrediente'];
  $receta = $recetas->addChild('receta');
  $receta->addAttribute('id', $id);
  $receta->addChild('titulo', $titulo);
  $receta->addChild('descripcion', $descripcion);
  $receta->addChild('autor', $autor);
  $receta->addChild('mail', $mail);
  $lista_ingredientes = $receta->addChild('ingredientes');
  foreach($ingredientes as $val){
    $lista_ingredientes->addChild('ingrediente', $val);
  }
  $receta->addChild('tiempo', $tiempo);
  $receta->addChild('personas', $personas);
  $receta->addChild('imagen', $imagen);
  $receta->addChild('calorias', $calorias);
  $receta->addChild('tipo', $tipo);
  $receta->addChild('texto', $texto);
  $receta->addChild('comentarios');
  $recetas->asXML('datos.xml');


  header("Location: ./receta.php?id=$id");
}
 ?>
