<?php 
//var_dump($_SESSION['iduser']); ?>


<!DOCTYPE html>
<html>
  <head>
    <?php require_once 'includes/head.php'; ?>
    <!--enlaceckeditor-->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
  </head>
  <body>	
    <div class="container">
      <?php include_once 'vistas/includes/header.html'; ?>
      <div>	
        <p><h2><img src="images/user.png" width="50px"/> Crear Entrada</h2> </p>
      </div>
        <?php foreach ($parametros["mensajes"] as $mensaje) : ?> 
          <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
        <?php endforeach; ?>
        <form action="index.php?accion=addentrada" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo $_SESSION['iduser'] ?>">
          <label for="titulo" class="mt-2 w-25">Titulo
            <input type="text" class="form-control" name="titulo" required value="<?= $parametros["datos"]["titulo"] ?>"></label>
          <br/>

          <label for="descripcion" class="mt-2">Descripción
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= $parametros["datos"]["descripcion"] ?></textarea></label>
            <script>CKEDITOR.replace('descripcion');</script>
          <br/>

          <label for="categoria_id" class="mt-2">Categoría
            <select class="form-select" name="categoria_id" required>
              <option value="">Selecciona una</option>
              <?php foreach ($categorias["datos"] as $categoria) : ?>
                <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <br/>

          <label for="fecha" class="mt-2">Fecha
            <input type="date" class="form-control" name="fecha" required value="<?= $parametros["datos"]["fecha"] ?>"></label>
          <br/>

          <label for="imagen" class="mt-2">Imagen <input type="file" name="imagen" class="form-control" value="<?= $parametros["datos"]["imagen"] ?>" /></label>
          </br>

          <input type="submit" value="Guardar" name="submit" class="btn btn-success mt-2">
        </form>
      </div>
  </body>
</html>