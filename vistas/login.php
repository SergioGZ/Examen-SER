<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi pequeño Blog</title>
  <!--Bootstrap-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-secondary">
  <div class="container">
    <?php
    if (isset($_GET['accion'])) {
      if ($_GET['accion'] == "loginincorrecto") {
        echo '<div class="alert alert-danger mt-3">' . "Tu nombre de usuario y/o tu contraseña no son correctos<br/>" . '</div>';
      } elseif ($_GET['accion'] == "fuera") {
        echo '<div class="alert alert-danger" style="margin-top:5px;">' . "No puede acceder  directamente en esta página, debe iniciar sesión <br/>" . '</div>';
      }
    }
    ?>

  <div class="row mt-3">
      <div class="col-12">
          <h2 class="mt-3 text-white">Iniciar sesión</h2>
      </div>
  </div>

    <form method="post" action="./index.php?accion=login" class="row g-3 mt-3 border border-1 p-3 needs-validation bg-white" novalidate>
        <div class="col-6">
            <label for="nick" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required pattern="[A-Za-z]{1,12}">
            <div class="invalid-feedback">El nick debe contener entre 1 y 12 caracteres</div>
        </div>

        <div class="col-6">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
            <div class="invalid-feedback">Campo requerido</div>
        </div>

        <div class="col-12 mt-3">
            <button type="submit" name="submit" class="btn btn-primary">Iniciar sesión</button>
        </div>
    </form>
  </div>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>

</html>