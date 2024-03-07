<?php
  //var_dump($parametros["datos"]);
  if (!isset($_SESSION['iduser'])) {
    header('Location: index.php');
  }
?>

<html>
  <head>
    <?php require_once 'includes/head.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </head>
  <body>
    <div class="container bg-white pb-3" id="pdf" style="min-height:100vh; height:auto;">
      <?php include_once 'vistas/includes/header.html'; ?>
      <div class="tituloentrada mt-3">
        <h2 class="text-center mx-auto border border-3 border-dark p-2" style="width:fit-content"><?= $parametros["datos"]["titulo"] ?></h2>
        <h3 class="text-center">Lugar: <?= $parametros["datos"]["lugar"] ?> - Fecha: <?= $parametros["datos"]["fecha"] ?> - Hora: <?= date('H:i:s', strtotime($parametros["datos"]["hora"])) ?></h3>
        <h3 class="text-center">Prioridad: <?= $parametros["datos"]["prioridad"] ?></h3>
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
        <h4 class="text-center mt-1">Categoría: <?= $parametros["datos"]["nombreCategoria"] ?></h4>
      </div>
      <button id="exportarPDF" class="btn btn-primary ms-5 mt-5">Generar PDF</button>
    </div>

    <script>
      let htmlPDF = document.getElementById("pdf");
      let exportarPDF = document.getElementById("exportarPDF");
      exportarPDF.onclick = (e) => {
          e.preventDefault();
          html2pdf().from(htmlPDF).save();
      };
    </script>
  </body>
</html>