<?php
  //var_dump($_SESSION['iduser']);
  //var_dump($parametros["datos"]);
  
  if (!isset($_SESSION['iduser'])) {
    header('Location: index.php');
  }
?>

<html>
  <head>
    <?php require_once 'includes/head.php'; ?>
  </head>
  <body class="bg-secondary">
    <div class="container">
      <?php include_once 'vistas/includes/header.html'; ?>
      <a class="m-4 pt-1 btn btn-warning" href="index.php?accion=addentrada&id=<?php echo $_SESSION['iduser']; ?>"><i class="bi bi-door-open-fill"></i> Crear Entrada</a>
      <!--Mostramos los mensajes que se hayan generado al realizar el listado-->
      <?php foreach ($parametros["datos"] as $d) : ?>
      <?php foreach ($parametros["mensajes"] as $mensaje) : ?> 
      <!-- <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>-->
      <?php endforeach; ?>

      <div class="row bg-success border border-5 border-dark">
        <div class="col-12" id="tituloentrada">
        <h2 class="text-center"><?= $d["titulo"] ?></h2>
        <h3 class="text-center">Autor: <?= $d["nick"] ?></h3>
        </div>

        <div class="col-12 bg-white border border-5 border-start-0 border-end-0 border-dark text-center" id="contenidoentrada">
          <?php if ($d["imagen"] !== NULL) : ?>
          <img class="mx-auto d-flex justify-content-center mt-3" src="fotos/<?= $d['imagen'] ?>" width="70" />
          <?php else : ?>
          <p class="text-center">----</p>
          <?php endif; ?>
          <p class="d-flex justify-content-center"><?= $d["descripcion"] ?></p>
        </div>

        <div class="col-12">
          <h4 class="text-center mt-1">Fecha: <?= $d["fecha"] ?></h4>
        </div>
      </div>
      <div class="mt-1 mb-5">
        <a class="btn btn-warning" href="index.php?accion=listadetalle&id=<?= $d['ID'] ?>">Detalles</a>
        <a class="btn btn-warning" href="index.php?accion=actentrada&id=<?= $d['ID'] ?>">Editar</a> 
        <a class="btn btn-warning" href="index.php?accion=delentrada&id=<?= $d['ID'] ?>">Eliminar</a>  
      </div>
      <?php endforeach; ?>
    </div>
  </body>
</html>