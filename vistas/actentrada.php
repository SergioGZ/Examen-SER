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
        <p><h2><img src="images/user.png" width="50px"/> Actualizar Entrada</h2> </p>
      </div>
      <?php
            foreach ($parametros["mensajes"] as $mensaje) : ?> 
             <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
      <?php endforeach; ?>
      <form action="index.php?accion=actentrada" method="post" enctype="multipart/form-data" class="mb-3">

        <label for="titulo">Titulo
          <input type="text" class="form-control" name="titulo" value="<?= $parametros["datos"]["titulo"] ?>" required></label>
        <br/>

        <label for="descripcion" class="mt-2">Descripción
          <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= $parametros["datos"]["descripcion"] ?></textarea></label>
          <script>CKEDITOR.replace('descripcion');</script>
        <br/>

        <label for="fecha">Fecha
          <input type="date" class="form-control" name="fecha" value="<?= $parametros["datos"]["fecha"] ?>"></label>
        <br/>

        <label for="categoria_id" class="mt-2">Categoría
            <select class="form-select" name="categoria_id" required>
              <option value="">Selecciona una</option>
              <?php foreach ($categorias["datos"] as $categoria) : ?>
                <option value="<?= $categoria['id'] ?>" <?= $parametros["datos"]["categoria_id"] == $categoria['id'] ? 'selected' : '' ?>><?= $categoria['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <br/>

        <?php if ($parametros["datos"]["imagen"] != null && $parametros["datos"]["imagen"] != "") { ?>
          </br>Imagen actual: <img src="fotos/<?= $parametros["datos"]["imagen"] ?>" width="60" /></br>
        <?php } ?>
        </br>
        <label for="imagen">Imagen nueva:
          <input type="file" name="imagen" class="form-control" value="<?= $parametros["datos"]["imagen"] ?>" /></label>
        </br>
        <!--Creamos un campo oculto para mantener el valor del id que deseamos modificar cuando pulsemos el botón actualizar-->  
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <br/>
        <input type="submit" value="Actualizar" name="submit" class="btn btn-success">
      </form>
    </div>
  </body>
</html>