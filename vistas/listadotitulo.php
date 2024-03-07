<?php
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
      <a class="m-4 pt-1 btn btn-warning" href="index.php?accion=addtarea"><i class="bi bi-door-open-fill"></i> Crear Tarea</a>

      <!-- Buscador de tareas por título -->
      <br/>
      <div>
        <span><strong>Buscar tarea por título: </strong></span> <input type="text" id="buscartitulo" class="form-control w-25 d-inline">
        <script>
          function enviarTitulo() {
            var titulo = document.getElementById('buscartitulo').value;
            var url = "index.php?accion=listadotitulo&titulo=" + titulo;
            window.location.href = url;
          }
        </script>
        <button class="btn btn-primary ms-3" onclick="enviarTitulo()">Mostrar tareas</button>
      </div>

      <!-- Buscador de tarea por fecha -->
      <br/>
      <div class="mb-3">
        <span><strong>Buscar tarea por fecha: </strong></span> <input type="date" id="buscarfecha" class="form-control w-25 d-inline">
        <script>
          function enviarFecha() {
            var fecha = document.getElementById('buscarfecha').value;
            var url = "index.php?accion=listadofecha&fecha=" + fecha;
            window.location.href = url;
          }
        </script>
        <button class="btn btn-primary ms-3" onclick="enviarFecha()">Mostrar tareas</button>
      </div>

      <?php foreach ($parametros["datos"] as $d) : ?>
      <!--Mostramos los mensajes que se hayan generado al realizar el listado-->
      <?php foreach ($parametros["mensajes"] as $mensaje) : ?> 
      <!-- <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>-->
      <?php endforeach; ?>

      <div class="row bg-danger border border-5 border-dark">
        <div class="col-12" id="titulotarea">
          <h4 class="text-center mt-1">Fecha: <?= $d["fecha"] ?> - Hora: <?= date('H:i:s', strtotime($d["hora"]))?></h4>
          <h4 class="text-center">Importancia: <?= $d["prioridad"] ?></h4>
        </div>

        <div class="col-12 bg-white border border-5 border-start-0 border-end-0 border-dark text-center" id="contenidoentrada">
        <h2 class="text-center"><?= $d["titulo"] ?></h2>  
        <?php if ($d["imagen"] !== NULL) : ?>
          <img class="mx-auto d-flex justify-content-center mt-3" src="fotos/<?= $d['imagen'] ?>" width="70" />
          <?php else : ?>
          <p class="text-center">----</p>
          <?php endif; ?>
          <p><?= $d["descripcion"] ?></p>
        </div>

        <div class="col-12">
          <h4 class="text-center mt-1">Categoría: <?= $d["nombreCategoria"] ?></h4>
        </div>
      </div>
      <div class="mt-1 mb-5">
        <a class="btn btn-warning" href="index.php?accion=listadetalle&id=<?= $d['ID'] ?>">Detalles</a>
        <a class="btn btn-warning" href="index.php?accion=acttarea&id=<?= $d['ID'] ?>">Editar</a> 
        <a class="btn btn-danger" href="index.php?accion=deltarea&id=<?= $d['ID'] ?>" onclick="return confirmarEliminar()">Eliminar</a>
      </div>
      <?php endforeach; ?>
    </div>
    
    <script>
      // Función para mostrar el mensaje de confirmación de eliminación de una tarea
      function confirmarEliminar() {
        var x = confirm("¿Estás seguro de que quieres eliminar esta tarea?");
        if (x) {
          return true;
        } else {
          return false;
        }
      }
    </script>
  </body>
</html>