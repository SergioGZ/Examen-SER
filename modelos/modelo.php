<?php

/**
 *   Clase 'modelo' que implementa el modelo de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la base de datos
 * en una capa especializada
 */
class modelo {

  //Atributo que contendrá la referencia a la base de datos 
  private $conexion;
  // Parámetros de conexión a la base de datos 
  private $host = "localhost";
  private $user = "root";
  private $pass = "";
  private $db = "bdblog";

  /**
   * Constructor de la clase que ejecutará directamente el método 'conectar()'
   */
  public function __construct() {
    $this->conectar();
  }

  /**
   * Método que realiza la conexión a la base de datos de usuarios mediante PDO.
   * Devuelve TRUE si se realizó correctamente y FALSE en caso contrario.
   * @return boolean
   */
  public function conectar() {
    try {
      $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
      $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return TRUE;
    } catch (PDOException $ex) {
      return $ex->getMessage();
    }
  }

  /**
   * Función que nos permite conocer si estamos conectados o no a la base de datos.
   * Devuelve TRUE si se realizó correctamente y FALSE en caso contrario.
   * @return boolean
   */
  public function estaConectado() {
    if ($this->conexion) :
      return TRUE;
    else :
      return FALSE;
    endif;
  }

  // Método que inicializa la sesión
  public function login($user)
    {
        $resultModelo = [
            "correcto" => false,
            "datos" => null,
            "error" => null,
        ];
        // Realizamos la consulta
        try { // Preparamos la consulta
            $sql = "SELECT * FROM usuarios where nick = '$user'";            
            $resultsquery = $this->conexion->query($sql);
            //Si la consulta se realizó correctamente
            if ($resultsquery) {
                $resultModelo["correcto"] = true;
                $resultModelo["datos"] = $resultsquery->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $ex) {
            $resultModelo["error"] = $ex->getMessage();
        }

        return $resultModelo;
    }

    // Método que lista todas las entradas de la base de datos
    public function listadoTareas() {
      $resultmodelo = [
          "correcto" => FALSE,
          "datos" => NULL,
          "error" => NULL
      ];

      try { 
        $sql = "SELECT tareas.*, categorias.* FROM tareas INNER JOIN categorias ON tareas.categoria_id = categorias.id WHERE DATE(tareas.fecha) = CURDATE() ORDER BY hora ASC";
        $resultsquery = $this->conexion->query($sql);
        if ($resultsquery) :
          $resultmodelo["correcto"] = TRUE;
          $resultmodelo["datos"] = $resultsquery->fetchAll(PDO::FETCH_ASSOC);
        endif;
      } catch (PDOException $ex) {
        $resultmodelo["error"] = $ex->getMessage();
      }
  
      return $resultmodelo;
    }

    // Método que añade una entrada a la base de datos
    public function addtarea($datos) {
      $return = [
          "correcto" => FALSE,
          "error" => NULL
      ];
  
      try {
        $this->conexion->beginTransaction();
        $sql = "INSERT INTO tareas(categoria_id,titulo,imagen,descripcion,fecha,hora,prioridad,lugar)
                           VALUES (:categoria_id,:titulo,:imagen,:descripcion,:fecha,:hora,:prioridad,:lugar)";
        $query = $this->conexion->prepare($sql);
        $query->execute([
            'categoria_id' => $datos["categoria_id"],
            'titulo' => $datos["titulo"],
            'imagen' => $datos["imagen"],
            'descripcion' => $datos["descripcion"],
            'fecha' => $datos["fecha"],
            'hora' => $datos["hora"],
            'prioridad' => $datos["prioridad"],
            'lugar' => $datos["lugar"]
        ]);
        if ($query) {
          $this->conexion->commit();
          $return["correcto"] = TRUE;
        }// o no :(
      } catch (PDOException $ex) {
        $this->conexion->rollback(); // rollback() se revierten los cambios realizados durante la transacción
        $return["error"] = $ex->getMessage();
        //die();
      }
  
      return $return;
    }

    // Método que lista una entrada de la base de datos
    public function listartarea($id) {
      $return = [
          "correcto" => FALSE,
          "datos" => NULL,
          "error" => NULL
      ];
  
      if ($id && is_numeric($id)) {
        try {
          $sql = "SELECT tareas.*, categorias.nombre AS nombreCategoria FROM tareas INNER JOIN categorias ON tareas.categoria_id = categorias.id WHERE tareas.id=:id";
          $query = $this->conexion->prepare($sql);
          $query->execute(['id' => $id]);
          // Supervisamos que la consulta se realizó correctamente
          if ($query) {
            $return["correcto"] = TRUE;
            $return["datos"] = $query->fetch(PDO::FETCH_ASSOC);
          }// o no :(
        } catch (PDOException $ex) {
          $return["error"] = $ex->getMessage();
          //die();
        }
      }
  
      return $return;
    }

    // Método que actualiza una entrada de la base de datos
    public function acttarea($datos) {
      $return = [
          "correcto" => FALSE,
          "error" => NULL
      ];
  
      try {
        $this->conexion->beginTransaction();
        $sql = "UPDATE tareas SET titulo= :titulo, descripcion= :descripcion, categoria_id= :categoria_id, fecha= :fecha, imagen= :imagen, hora= :hora, prioridad= :prioridad, lugar= :lugar WHERE id=:id";
        $query = $this->conexion->prepare($sql);
        $query->execute([
            'id' => $datos["id"],
            'titulo' => $datos["titulo"],
            'descripcion' => $datos["descripcion"],
            'categoria_id' => $datos["categoria_id"],
            'fecha' => $datos["fecha"],
            'imagen' => $datos["imagen"],
            'hora' => $datos["hora"],
            'prioridad' => $datos["prioridad"],
            'lugar' => $datos["lugar"]
        ]);

        if ($query) {
          $this->conexion->commit();  // commit() confirma los cambios realizados durante la transacción
          $return["correcto"] = TRUE;
        }// o no :(
      } catch (PDOException $ex) {
        $this->conexion->rollback(); // rollback() se revierten los cambios realizados durante la transacción
        $return["error"] = $ex->getMessage();
        //die();
      }
  
      return $return;
    }

  // Método que elimina una entrada de la base de datos
  public function deltarea($id) {
    $return = [
        "correcto" => FALSE,
        "error" => NULL
    ];
    if ($id && is_numeric($id)) {
      try {
        //Inicializamos la transacción
        $this->conexion->beginTransaction();
        //Definimos la instrucción SQL parametrizada 
        $sql = "DELETE FROM tareas WHERE id=:id";
        $query = $this->conexion->prepare($sql);
        $query->execute(['id' => $id]);
  
        if ($query) {
          $this->conexion->commit();  // commit() confirma los cambios realizados durante la transacción
          $return["correcto"] = TRUE;
        }// o no :(
      } catch (PDOException $ex) {
        $this->conexion->rollback(); // rollback() se revierten los cambios realizados durante la transacción
        $return["error"] = $ex->getMessage();
      }
    } else {
      $return["correcto"] = FALSE;
    }

    return $return;
  }

  // Método que lista las categorías de la base de datos
  public function listadocategorias() {
    $return = [
        "correcto" => FALSE,
        "datos" => NULL,
        "error" => NULL
    ];
    //Realizamos la consulta...
    try {  //Definimos la instrucción SQL  
      $sql = "SELECT * FROM categorias";
      // Hacemos directamente la consulta al no tener parámetros
      $resultsquery = $this->conexion->query($sql);
      //Supervisamos si la inserción se realizó correctamente... 
      if ($resultsquery) {
        $return["correcto"] = TRUE;
        $return["datos"] = $resultsquery->fetchAll(PDO::FETCH_ASSOC);
      }// o no :(
    } catch (PDOException $ex) {
      $return["error"] = $ex->getMessage();
    }

    return $return;
  }

  public function listadotitulo($titulo) {
    $return = [
        "correcto" => FALSE,
        "datos" => NULL,
        "error" => NULL
    ];

    try { 
      $sql = "SELECT tareas.*, categorias.nombre AS nombreCategoria FROM tareas INNER JOIN categorias ON tareas.categoria_id = categorias.id WHERE tareas.titulo LIKE '%$titulo%'";
      $resultsquery = $this->conexion->query($sql);
      if ($resultsquery) {
        $return["correcto"] = TRUE;
        $return["datos"] = $resultsquery->fetchAll(PDO::FETCH_ASSOC);
      }
    } catch (PDOException $ex) {
      $return["error"] = $ex->getMessage();
    }

    return $return;
  }

  public function listadofecha($fecha) {
    $return = [
        "correcto" => FALSE,
        "datos" => NULL,
        "error" => NULL
    ];

    try { 
      $sql = "SELECT tareas.*, categorias.nombre AS nombreCategoria FROM tareas INNER JOIN categorias ON tareas.categoria_id = categorias.id WHERE DATE(tareas.fecha) = '$fecha'";
      $resultsquery = $this->conexion->query($sql);
      if ($resultsquery) {
        $return["correcto"] = TRUE;
        $return["datos"] = $resultsquery->fetchAll(PDO::FETCH_ASSOC);
      }
    } catch (PDOException $ex) {
      $return["error"] = $ex->getMessage();
    }

    return $return;
  }

}
