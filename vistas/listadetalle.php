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
  <body>
    <div class="container bg-white" style="height:100vh">
      <?php include_once 'vistas/includes/header.html'; ?>
      <div class="tituloentrada mt-3">
        <h2 class="text-center mx-auto border border-3 border-dark p-2" style="width:fit-content"><?= $parametros["datos"]["titulo"] ?></h2>
        <h3 class="text-center">Autor: <?= $parametros["datos"]["nick"] ?></h3><h3 class="text-center">Categoría: <?= $parametros["datos"]["nombreCategoria"] ?></h3>
      </div>
      <div class="imagen">
        <?php if ($parametros["datos"]["imagen"] !== NULL) : ?>
        <img class="mx-auto d-flex justify-content-center mt-3" style="max-width: 350px;" src="fotos/<?= $parametros["datos"]['imagen'] ?>" />
        <?php else : ?>
        <p class="text-center">----</p>
        <?php endif; ?>
      </div>
      <div class="contenidoentrada mt-3 mx-auto text-center" style="max-width: 500px;">
        <p><?= $parametros["datos"]["descripcion"] ?></p>
      </div>
      <div class="fecha mt-3">
        <h4 class="text-center mt-1">Fecha: <?= $parametros["datos"]["fecha"] ?></h4>
      </div>
    </div>
  </body>
</html>