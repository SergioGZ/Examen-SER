<?php
/**
 * Incluimos el modelo para poder acceder a su clase y a los métodos que implementa
 */
require_once 'modelos/modelo.php';

/**
 * Clase controlador que será la encargada de obtener, para cada tarea, los datos
 * necesarios de la base de datos, y posteriormente, tras su proceso de elaboración,
 * enviarlos a la vista para su visualización
 */
class controlador {

  // El el atributo $modelo es de la 'clase modelo' y será a través del que podremos 
  // acceder a los datos y las operaciones de la base de datos desde el controlador
  private $modelo;
  //$mensajes se utiliza para almacenar los mensajes generados en las tareas, 
  //que serán posteriormente transmitidos a la vista para su visualización
  private $mensajes;

  /**
   * Constructor que crea automáticamente un objeto modelo en el controlador e
   * inicializa los mensajes a vacío
   */
  public function __construct() {
    $this->modelo = new modelo();
    $this->mensajes = [];
  }

  /**
   * Método que envía al usuario a la página de inicio del sitio y le asigna 
   * el título de manera dinámica
   */
  public function index() {
    $parametros = [
        "tituloventana" => "Base de Datos con PHP y PDO"
    ];
    //Mostramos la página de inicio 
    include_once 'vistas/login.php';
  }

  public function login($user, $pass)
    {
        // Obtenemos los datos del usuario
        $datoUsuario = $this->modelo->login($user);
        if ($datoUsuario['correcto']) {
            if (
                $datoUsuario['datos']['nick'] == $user && $datoUsuario['datos']['password'] == $pass
            ) {
                session_start();
                $_SESSION['user'] = $user;
                $_SESSION['nick'] = $datoUsuario['datos']['nick'];
                $_SESSION['iduser'] = $datoUsuario['datos']['id'];
                $_SESSION['rol'] = $datoUsuario['datos']['rol'];
                $_SESSION['avatar'] = $datoUsuario['datos']['imagen-avatar'];
            } else {
                header("Location: index.php?accion=loginincorrecto");
            }
            $this->userLoginOk();
        } else {
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "Error. No se pudieron obtener los datos del usuario<br/> ({$datoUsuario["error"]})",
            ];
        }
    }

    public function userLoginOk()
    {
        // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
        $parametros = [
            "tituloventana" => "Mi Blog - " . $_SESSION['user'] . " (" . $_SESSION['rol'] . ")",
            "datos" => null,
            "mensajes" => [],
        ];

        // Realizamos la consulta y almacenamos los resultados en la variable $resultModelo
        if (isset($_SESSION['user']) && $_SESSION['rol'] == "admin") {
            $resultModelo = $this->modelo->listadoTareas();
        }

        if ($resultModelo["correcto"]) {
            $parametros["datos"] = $resultModelo["datos"];
            $this->mensajes[] = [
                "tipo" => "success",
                "mensaje" => "La consulta se realizó correctamente",
            ];
        } else {
            // Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "Error. La consulta no pudo realizarse correctamente<br/>({$resultModelo["error"]})",
            ];
        }
        $parametros['mensajes'] = $this->mensajes;

        // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
        if (isset($_SESSION['user'])) {
            include_once './vistas/listado.php';
        }
    }

    public function listadoTareas() {
    if (!isset($_SESSION)) {
      session_start();
    }
      $parametros = [
          "tituloventana" => "Base de Datos con PHP y PDO",
          "datos" => NULL,
          "mensajes" => []
      ];

      $resultModelo = $this->modelo->listadoTareas();

      if ($resultModelo["correcto"]) :
        $parametros["datos"] = $resultModelo["datos"];
        $this->mensajes[] = [
            "tipo" => "success",
            "mensaje" => "El listado se realizó correctamente"
        ];
      else :
        $this->mensajes[] = [
            "tipo" => "danger",
            "mensaje" => "El listado no pudo realizarse correctamente<br/>({$resultModelo["error"]})"
        ];
      endif;

      $parametros["mensajes"] = $this->mensajes;
      include_once 'vistas/listado.php';
    }

    public function addtarea() {
      if (!isset($_SESSION)) {
        session_start();
      }
      
      // Array asociativo que almacenará los mensajes de error que se generen por cada campo
      $errores = array();
        // Si se ha pulsado el botón guardar...
      if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) {
        $categoria_id = $_POST['categoria_id'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $lugar = $_POST['lugar'];
        $prioridad = $_POST['prioridad'];

        $imagen = NULL;

        if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) {
          // Verificamos la carga de la imagen
          // Comprobamos si existe el directorio fotos, y si no, lo creamos
          if (!is_dir("fotos")) {
            $dir = mkdir("fotos", 0777, true);
          } else {
            $dir = true;
          }
          // Ya verificado que la carpeta uploads existe movemos el fichero seleccionado a dicha carpeta
          if ($dir) {
            //Para asegurarnos que el nombre va a ser único, le añadimos al nombre del fichero la fecha y hora actual
            $nombrefichimg = time() . "-" . $_FILES["imagen"]["name"];
            // Movemos el fichero de la carpeta temportal a la nuestra
            $movfichimg = move_uploaded_file($_FILES["imagen"]["tmp_name"], "fotos/" . $nombrefichimg);
            $imagen = $nombrefichimg;
            // Verficamos que la carga se ha realizado correctamente
            if ($movfichimg) {
              $imagencargada = true;
            } else {
              $imagencargada = false;
              $this->mensajes[] = [
                  "tipo" => "danger",
                  "mensaje" => "Error: La imagen no se cargó correctamente"
              ];
              $errores["imagen"] = "Error: La imagen no se cargó correctamente";
            }
          }
        }
        // Si no se han producido errores realizamos el registro del usuario
        if (count($errores) == 0) {
          $resultModelo = $this->modelo->addtarea([
              'categoria_id' => $categoria_id,
              'titulo' => $titulo,
              'descripcion' => $descripcion,
              'fecha' => $fecha,
              'imagen' => $imagen,
              'hora' => $hora,
              'lugar' => $lugar,
              'prioridad' => $prioridad
          ]);
          if ($resultModelo["correcto"]) :
            $this->mensajes[] = [
                "tipo" => "success",
                "mensaje" => "La tarea se registró correctamente"
            ];
          else :
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "La tarea no pudo registrarse<br/>({$resultModelo["error"]})"
            ];
          endif;
        } else {
          $this->mensajes[] = [
              "tipo" => "danger",
              "mensaje" => "Datos de registro de tarea erróneos"
          ];
        }
      }

      $parametros = [
          "tituloventana" => "Base de Datos con PHP y PDO",
          "datos" => [
              "categoria_id" => isset($categoria_id) ? $categoria_id : "",
              "titulo" => isset($titulo) ? $titulo : "",
              "descripcion" => isset($descripcion) ? $descripcion : "",
              "fecha" => isset($fecha) ? $fecha : "",
              "imagen" => isset($imagen) ? $imagen : "",
              "hora" => isset($hora) ? $hora : "",
              "lugar" => isset($lugar) ? $lugar : "",
              "prioridad" => isset($prioridad) ? $prioridad : ""
          ],
          "mensajes" => $this->mensajes
      ];

      $categorias = $this->modelo->listadocategorias();

      // Visualizamos la vista asociada al registro de usuarios
      include_once 'vistas/addtarea.php';
    }
    
        public function acttarea() {
          if (!isset($_SESSION)) {
            session_start();
          }

          // Array asociativo que almacenará los mensajes de error que se generen por cada campo
          $errores = array();
          // Inicializamos valores de los campos de texto
          $valtitulo = "";
          $valdescripcion = "";
          $valfecha = "";
          $valcategoria_id = "";
          $valimagen = "";
          $valhora = "";
          $vallugar = "";
          $valprioridad = "";
      
          // Si se ha pulsado el botón actualizar
          if (isset($_POST['submit'])) {
            $id = $_POST['id']; //Lo recibimos por el campo oculto
            $nuevotitulo = $_POST['titulo'];
            $nuevadescripcion  = $_POST['descripcion'];
            $nuevafecha = $_POST['fecha'];
            $nuevacategoria_id = $_POST['categoria_id'];
            $nuevaimagen = "";
            $nuevahora = $_POST['hora'];
            $nuevalugar = $_POST['lugar'];
            $nuevaprioridad = $_POST['prioridad'];
      
            // Definimos la variable $imagen que almacenará el nombre de imagen 
            $imagen = NULL;
      
            if (isset($_FILES["imagen"]) && (!empty($_FILES["imagen"]["tmp_name"]))) {
              // Verificamos la carga de la imagen
              // Comprobamos si existe el directorio fotos, y si no, lo creamos
              if (!is_dir("fotos")) {
                $dir = mkdir("fotos", 0777, true);
              } else {
                $dir = true;
              }
              if ($dir) {
                
                $nombrefichimg = time() . "-" . $_FILES["imagen"]["name"];                    
                $movfichimg = move_uploaded_file($_FILES["imagen"]["tmp_name"], "fotos/" . $nombrefichimg);
                $imagen = $nombrefichimg;
                
                if ($movfichimg) {
                  $imagencargada = true;
                } else {
                  $imagencargada = false;
                  $errores["imagen"] = "Error: La imagen no se cargó correctamente! :(";
                  $this->mensajes[] = [
                      "tipo" => "danger",
                      "mensaje" => "Error: La imagen no se cargó correctamente! :("
                  ];
                }
              }
            }
            $nuevaimagen = $imagen;
      
            if (count($errores) == 0) {
              
              $resultModelo = $this->modelo->acttarea([
                  'id' => $id,
                  'titulo' => $nuevotitulo,
                  'descripcion' => $nuevadescripcion,
                  'fecha' => $nuevafecha,
                  'categoria_id' => $nuevacategoria_id,
                  'imagen' => $nuevaimagen,
                  'hora' => $nuevahora,
                  'lugar' => $nuevalugar,
                  'prioridad' => $nuevaprioridad
              ]);

              if ($resultModelo["correcto"]) :
                $this->mensajes[] = [
                    "tipo" => "success",
                    "mensaje" => "La tarea se actualizó correctamente!! :)"
                ];
              else :
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "La tarea no pudo actualizarse!! :( <br/>({$resultModelo["error"]})"
                ];
              endif;
            } else {
              $this->mensajes[] = [
                  "tipo" => "danger",
                  "mensaje" => "Datos de registro de usuario erróneos!! :("
              ];
            }
      
            // Obtenemos los valores para mostrarlos en los campos del formulario
            $valtitulo = $nuevotitulo;
            $valdescripcion  = $nuevadescripcion;
            $valfecha = $nuevafecha;
            $valcategoria_id = $nuevacategoria_id;
            $valimagen = $nuevaimagen;
            $valhora = $nuevahora;
            $vallugar = $nuevalugar;
            $valprioridad = $nuevaprioridad;
          } else { // Obtenemos los valores del usuario a través de su id
            if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
              $id = $_GET['id'];
              //Ejecutamos la consulta para obtener los datos del usuario #id
              $resultModelo = $this->modelo->listartarea($id);
              if ($resultModelo["correcto"]) :
                $this->mensajes[] = [
                    "tipo" => "success",
                    "mensaje" => "Los datos de la tarea se obtuvieron correctamente"
                ];
                $valtitulo = $resultModelo["datos"]["titulo"];
                $valdescripcion  = $resultModelo["datos"]["descripcion"];
                $valfecha = $resultModelo["datos"]["fecha"];
                $valcategoria_id = $resultModelo["datos"]["categoria_id"];
                $valimagen = $resultModelo["datos"]["imagen"];
                $valhora = $resultModelo["datos"]["hora"];
                $vallugar = $resultModelo["datos"]["lugar"];
                $valprioridad = $resultModelo["datos"]["prioridad"];
              else :
                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "No se pudieron obtener los datos de tarea<br/>({$resultModelo["error"]})"
                ];
              endif;
            }
          }
          $parametros = [
              "tituloventana" => "Base de Datos con PHP y PDO",
              "datos" => [
                  "titulo" => $valtitulo,
                  "descripcion"  => $valdescripcion,
                  "fecha" => $valfecha,
                  "categoria_id" => $valcategoria_id,
                  "imagen"    => $valimagen,
                  "hora" => $valhora,
                  "lugar" => $vallugar,
                  "prioridad" => $valprioridad
              ],
              "mensajes" => $this->mensajes
          ];

          $categorias = $this->modelo->listadocategorias();
          // Mostramos la vista acttarea
          include_once 'vistas/acttarea.php';
        }

  // Método que elimina un usuario de la base de datos
  public function deltarea() {

    // Verificamos que hemos recibido los parámetros desde la vista de listado 
    if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
      $id = $_GET["id"];

      if(!isset($_SESSION)) {
        session_start();
      }

      // Realizamos la operación de suprimir el usuario con el id=$id
      $this->modelo->deltarea($id);

      // Redirigimos a la página de listado de usuarios
      header("Location: index.php?accion=listadoTareas");
    }
      
  }

  public function logout() {
    session_start();
    session_destroy();
    header("Location: ./index.php");
  }

  public function listadetalle() {
    if (!isset($_SESSION)) {
      session_start();
    }
    $parametros = [
        "tituloventana" => "Base de Datos con PHP y PDO",
        "datos" => NULL,
        "mensajes" => []
    ];

    $id = $_GET['id'];
    $resultModelo = $this->modelo->listartarea($id);

    if ($resultModelo["correcto"]) :
      $parametros["datos"] = $resultModelo["datos"];
      $this->mensajes[] = [
          "tipo" => "success",
          "mensaje" => "El listado se realizó correctamente"
      ];
    else :
      $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "El listado no pudo realizarse correctamente{$resultModelo["error"]})"
      ];
    endif;

    $parametros["mensajes"] = $this->mensajes;
    include_once 'vistas/listadetalle.php';
  }

  public function listadotitulo() {
    if (!isset($_SESSION)) {
      session_start();
    }
    $parametros = [
        "tituloventana" => "Base de Datos con PHP y PDO",
        "datos" => NULL,
        "mensajes" => []
    ];
    if (isset($_GET['titulo'])) {
      $titulo = $_GET['titulo'];
      $resultModelo = $this->modelo->listadotitulo($titulo);
    } else {
      $resultModelo = $this->modelo->listadoTareas();
    }

    if ($resultModelo["correcto"]) :
      $parametros["datos"] = $resultModelo["datos"];
      $this->mensajes[] = [
          "tipo" => "success",
          "mensaje" => "El listado se realizó correctamente"
      ];
    else :
      $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "El listado no pudo realizarse correctamente{$resultModelo["error"]})"
      ];
    endif;

    $parametros["mensajes"] = $this->mensajes;
    include_once 'vistas/listadotitulo.php';
  }

  public function listadofecha() {
    if (!isset($_SESSION)) {
      session_start();
    }
    $parametros = [
        "tituloventana" => "Base de Datos con PHP y PDO",
        "datos" => NULL,
        "mensajes" => []
    ];
    if (isset($_GET['fecha'])) {
      $fecha = $_GET['fecha'];
      $resultModelo = $this->modelo->listadofecha($fecha);
    } else {
      $resultModelo = $this->modelo->listadoTareas();
    }

    if ($resultModelo["correcto"]) :
      $parametros["datos"] = $resultModelo["datos"];
      $this->mensajes[] = [
          "tipo" => "success",
          "mensaje" => "El listado se realizó correctamente"
      ];
    else :
      $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "El listado no pudo realizarse correctamente{$resultModelo["error"]})"
      ];
    endif;

    $parametros["mensajes"] = $this->mensajes;
    include_once 'vistas/listadofecha.php';
  }

}
