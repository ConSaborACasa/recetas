<?php
session_start();
if( strcasecmp($_SERVER['REQUEST_METHOD'],"POST") === 0) {
$_SESSION['postdata'] = $_POST;
header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
exit;
}
if( isset($_SESSION['postdata'])) {
$_POST = $_SESSION['postdata'];
unset($_SESSION['postdata']);
}


$recetas = simplexml_load_file("datos.xml");
if(isset($_GET["id"])){
  $id = $_GET["id"];

  foreach ($recetas as $actual) {
    if($actual['id'] == $id)
      break;
  }

}else{
  header("Location: ./index.html");
}

if (isset($_POST['submit'])) {
  $autor = (isset($_POST['usuario'])&& strlen($_POST['usuario'])>0)?htmlspecialchars($_POST["usuario"]):"Anónimo";
  $mail = (isset($_POST['mail'])&& strlen($_POST['mail'])>0)?htmlspecialchars($_POST["mail"]):" ";
  $texto = htmlspecialchars($_POST['texto']);
  $nuevocom = $actual->comentarios->addChild('comentario');
  $nuevocom->addChild('texto', $texto);
  $nuevocom->addChild('fecha', date('l jS \of F Y h:i:s A'));
  $nuevocom->addChild('usuario', $autor);
  $recetas->asXML('datos.xml');
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Recetas Uni</title>
    <link rel="icon" type="image/png" href="./images/icon.png" />
    <link rel="stylesheet" href="estilo.css">
    <style>
      /* styles.css o tu archivo de estilos */
@media (max-width: 576px) { /* Estilos para dispositivos móviles pequeños */
  .media img {
    max-width: 100px; /* Ajusta el tamaño máximo de la imagen */
  }
  .media-body {
    width: calc(100% - 120px); /* Ajusta el ancho del contenido de la media */
  }
  .form-control {
    font-size: 14px; /* Ajusta el tamaño de fuente para dispositivos móviles */
  }
}

    </style>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6115667685103314"
    crossorigin="anonymous"></script>
  </head>
  <body>
    <header>
      <div class="top">
        <div class="top-logo">
        <img src="./images/alimentemos.png" alt="" width="50px" height="50px">
        <h1>Con Sabor a casa</h1>
        </div>
        <div class="top-icons">
          <a href="#" data-texto="Añadir nueva receta" class="button-hover" id="btn-nueva">Añadir nueva receta</a>
          <a href="#" data-texto="Buscar" class="button-hover" id="btn-buscador">Buscar</a>

        </div>
      </div>
    </header>
    <nav>
      <div class="menu">
        <ul>
          <a href="index.html"><li>Inicio</li></a>
          <li>Recetas</li>
        </ul>
      </div>
    </nav>
    <main>
      <section class="receta caja">
        <h1 class="receta-titulo"><?php echo $actual->titulo ?></h1>
        <p class="receta-subtitulo"><?php echo $actual->descripcion ?></p>
        <div class="receta-info">
          <div class="receta-info-chef"><p><?php echo $actual->autor ?></p></div>
          <div class="receta-info-tiempo"><?php echo $actual->tiempo ?></div>
          <div class="receta-info-cal"><?php echo $actual->calorias ?></div>
        </div>
        <div class="receta-ingredientes">
          <div class="titulo encabezado"><i class="icono icono-ingredientes"></i><p>Ingredientes</p></div>
          <ul>
            <?php
              $lista = "";
              echo $actual->ingredientes;
              foreach ($actual->ingredientes->ingrediente as $value) {
                $lista .= "<li>";
                $lista .= $value;
                $lista .= "</li>";
              }
              echo $lista;
             ?>
          </ul>
        </div>
        <div class="receta-preparacion">
          <div class="encabezado"><i class="icono icono-cooking"></i><p>Preparacion</p></div>
          <div class="receta-preparacion-contenido">
            <?php echo "<img src='" .  $actual->imagen . "' alt=''>" ?>
            <p>
            <?php echo $actual->texto ?>

          </p>
          </div>
        </div>
      </section>
      <section class="recomendaciones caja">
        <div class="encabezado"><i class="icono icono-suggest"></i><p>Sugerencias</p></div>
        <div class="recomendaciones-sugerencias">
          <ul id="sugerencias">

          </ul>
      </div>
      </section>
      <section class="comentarios caja">
        <div class="encabezado"><i class="icono icono-chat"></i><p>Comentarios</p></div>
        <?php  echo "<form action='receta.php?id=" . $id . "' method='Post' class='comentarios-nuevo' id='form-coment'>"?>
          <div data-comentarios="datos">
            <input type="text" placeholder="Usuario" name="usuario" id="comentario-usuario">
            <input type="email" placeholder="Correo electrónico" name="mail">
          </div>
          <textarea rows="10" cols="50" placeholder="Introduce un nuevo comentario" name="texto" id="comentario-text"></textarea>
          <input type="submit" data-texto="Enviar" class="button-hover" name="submit" placeholder="Enviar">
        </form>
        <ul>

            <?php
            $comentario = "";
              foreach ($actual->comentarios->comentario as $value) {
                $comentario .= "<li class='comentario'>";
                $comentario .= "<p>" . htmlentities($value->texto) . "</p>";
                $comentario .= "<span class='comentario-bottom'>";
                $comentario .= $value->fecha . " @ ". htmlentities($value->usuario) . "</span></li>";
              }
              echo $comentario;

             ?>


        </ul>
      </section>

    </main>
    <!-- BUSCADOR -->
    <section class="buscador">
      <form action="" class="buscador-formulario">
        <span class="buscador-close">X</span>

        <input type="search" class="buscador-txt buscador-titulo" id="campo-buscador" name="buscador-titulo" placeholder="Buscar la receta por titulo aquí...">
        <select name="tipo" id="buscador-select" class="buscador-select">
          <option value="titulo">Titulo</option>
          <option value="autor">Autor</option>


        </select>
        <div id="actualizar"></div>
      </form>
    </section>

    <!-- MODAL -->
    <main class="modal-nueva">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6115667685103314"
     crossorigin="anonymous"></script>
      <form action="print.php" method="post" class="caja nueva" id="formulario">
        <h1 class="nueva-titulo">ueva receta</h1>
        <div class="row">
          <label for="newNombre"></label><input type="text" placeholder="Titulo de la receta" id="newNombre" class="nueva-nombre" name="titulo">
        </div>
        <div class="row">
          <label for="newUsuario" data-tam="mediano" class="label label-newUser"></label>
          <input type="text" placeholder="Nombre de usuario" id="newUsuario" name="usuario">

          <label for="newCorreo" data-tam="mediano" class="label label-newCorreo"></label>
          <input type="mail" placeholder="Correo electronico" id="newCorreo" name="mail">

          <label for="newDescripcion" data-tam="mediano" class="label label-newDesc"></label>
          <input type="text" placeholder="Introduce una breve descripcion" id="newDescripcion" maxlength="50" class="newDescripcion" name="descripcion">

        </div>
        <div class="nueva-tipo">
              <input type="radio" value="1" id="radioOne" name="tipo" checked/>
              <label for="radioOne" class="radio" chec>Primero</label>
              <input type="radio" value="2" id="radioTwo" name="tipo" />
              <label for="radioTwo" class="radio">Segundo</label>
              <input type="radio" value="3" id="radioThree" name="tipo"/>
              <label for="radioThree" class="radio" chec>Postre</label>
              <input type="radio" value="4" id="radioFour" name="tipo" />
              <label for="radioFour" class="radio">Bebida</label>
      </div>

      <div class="receta-ingredientes nueva-ingredientes">
      <ul id="lista-ingredientes">

      </ul>

      <div class="row">
        <label for="newIngrediente" class="label-newIngred"> + </label>
        <input type="text" placeholder="Introduce un nuevo ingrediente" id="newIngrediente" class="newIngrediente">
        <input type="number" placeholder="gr" class="newCantidad" id="newCantidadgr">
      </div>
      </div>

      <div class="row">
      <label for="newTiempo" data-tam="mediano" class="label label-newTiempo"></label>
      <input type="number" id="newTiempo" name="tiempo" placeholder="Tiempo medio">
      <label for="newCal" data-tam="mediano" class="label label-newCal"></label>
      <input type="number" id="newCal" name="calorias" placeholder="Calorias">
      <label for="newPersonas" data-tam="mediano" class="label label-newPersonas"></label>
      <input type="number" id="newPersonas" name="personas" placeholder="Numero de personas">
      <label for="newPersonas" data-tam="mediano" class="label label-newImg"></label>
      <input type="url" id="newImg" name="img" placeholder="url de la imagen">
      </div>
      <div class="nueva-preparacion">
      <div class="nueva-preparacion-titulo">Preparacion de la receta</div>
      <textarea name="nueva-preparacion-pasos" id="nueva-preparacion" cols="30" rows="10" placeholder="Introduce aquí los pasos a seguir"></textarea>
      </div>

      <input type="submit" id="submit" name="submit" data-texto="Enviar" class="button-hover nuevaEnviar" placeholder="Enviar" >
      </form>
    </main>

    <footer>
      SAR 2015-2016
    </footer>
    <!-- SCRIPTS -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6115667685103314"
    crossorigin="anonymous"></script>
    <script type="text/javascript" src="./js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="./js/app.js"></script>
    </body>
    </html>
