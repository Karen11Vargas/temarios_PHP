<?php 

class ajaxController extends Controller {
  
  private $accepted_actions = ['add', 'get', 'load', 'update', 'delete', 'post', 'put'];
  private $required_params  = ['hook', 'action'];

  function __construct()
  {
    foreach ($this->required_params as $param) {
      if(!isset($_POST[$param])) {
        json_output(json_build(403));
      }
    }

    if(!in_array($_POST['action'], $this->accepted_actions)) {
      json_output(json_build(403));
    }
  }

  function index()
  {
    /**
    200 OK
    201 Created
    300 Multiple Choices
    301 Moved Permanently
    302 Found
    304 Not Modified
    307 Temporary Redirect
    400 Bad Request
    401 Unauthorized
    403 Forbidden
    404 Not Found
    410 Gone
    500 Internal Server Error
    501 Not Implemented
    503 Service Unavailable
    550 Permission denied
    */
    json_output(json_build(403));
  }

  function temario_form()
  {
    try {
      $id          = (int) $_POST['id'];
      $titulo      = clean($_POST['titulo']);
      $descripcion = clean($_POST['descripcion']);

      $data =
      [
        'titulo'      => $titulo,
        'descripcion' => $descripcion
      ];

      if(!temarioModel::update(temarioModel::$t1, ['id' => $id], $data)) {
        json_output(json_build(400, null, 'Hubo un error al actualizar el temario.'));
      }
  
      // se guardó con éxito
      $temario = temarioModel::by_id($id);
      json_output(json_build(200, $temario, 'Temario actualizado con éxito.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function add_leccion_form()
  {
    try {
      $id_temario  = (int) $_POST['id_temario'];
      $titulo      = clean($_POST['titulo']);
      $contenido   = clean($_POST['contenido']);
      $tipo        = clean($_POST['tipo']);
      $orden       = 0;

      if (strlen($titulo) < 5) {
        json_output(json_build(400, null, 'El título es demasiado corto.'));
      }

      if (!$temario = temarioModel::by_id($id_temario)) {
        json_output(json_build(400, null, 'El temario no existe en la base de datos.'));
      }

      // Validar las lecciones
      // Si no existen lecciones, orden es 0
      // Si existen lecciones obtener la última y sumar 1 al orden
      if (!empty($temario['lecciones'])) {
        $ultima_leccion = end($temario['lecciones']);
        $ultimo_orden   = $ultima_leccion['orden'];
        $orden          = $ultimo_orden + 1;
      }

      $data =
      [
        'id_temario'  => $id_temario,
        'titulo'      => $titulo,
        'contenido'   => $contenido,
        'tipo'        => $tipo,
        'status'      => 'pendiente',
        'orden'       => $orden,
        'creado'      => now(),
        'actualizado' => now()
      ];

      if(!$id_leccion = leccionModel::add(leccionModel::$t1, $data)) {
        json_output(json_build(400, null, 'Hubo un error al agregar la lección.'));
      }
  
      // se guardó con éxito
      $temario = temarioModel::by_id($id_temario);
      json_output(json_build(201, $temario, 'Lección agregada con éxito.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function get_lecciones()
  {
    try {
      $id   = clean($_POST['id']);
      $data = get_module('listaLecciones', temarioModel::by_id($id));
      json_output(json_build(200, $data));

    } catch(Exception $e) {
      json_output(json_build(400, $e->getMessage()));
    }

  }

  function delete_leccion()
  {
    try {
      $id_leccion = clean($_POST['id']);

      // Validar que exista la lección
      if (!$leccion = leccionModel::by_id($id_leccion)) {
        throw new Exception('No existe la lección en la base de datos.');
      }

      if(!leccionModel::remove(leccionModel::$t1, ['id' => $id_leccion], 1)) {
        json_output(json_build(400, null, 'Hubo un error al borrar la lección.'));
      }

      // Checar el status del temario
      check_temario_status($leccion['id_temario']);

      json_output(json_build(200, null, 'Lección borrada con éxito.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function bee_update_movement()
  {
    try {
      $movement     = new movementModel;
      $movement->id = $_POST['id'];
      $mov          = $movement->one();

      if(!$mov) {
        json_output(json_build(400, null, 'No existe el movimiento'));
      }

      $data = get_module('updateForm', $mov);
      json_output(json_build(200, $data));
    } catch(Exception $e) {
      json_output(json_build(400, $e->getMessage()));
    }
  }

  function bee_save_movement()
  {
    try {
      $mov              = new movementModel();
      $mov->id          = $_POST['id'];
      $mov->type        = $_POST['type'];
      $mov->description = $_POST['description'];
      $mov->amount      = (float) $_POST['amount'];
      if(!$mov->update()) {
        json_output(json_build(400, null, 'Hubo error al guardar los cambios'));
      }
  
      // se guardó con éxito
      json_output(json_build(200, $mov->one(), 'Movimiento actualizado con éxito'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function bee_save_options()
  {
    $options =
    [
      'use_taxes' => $_POST['use_taxes'],
      'taxes'     => (float) $_POST['taxes'],
      'coin'      => $_POST['coin']
    ];

    foreach ($options as $k => $option) {
      try {
        if(!$id = optionModel::save($k, $option)) {
          json_output(json_build(400, null, sprintf('Hubo error al guardar la opción %s', $k)));
        }
    
        
      } catch (Exception $e) {
        json_output(json_build(400, null, $e->getMessage()));
      }
    }

    // se guardó con éxito
    json_output(json_build(200, null, 'Opciones actualizadas con éxito'));
  }

  function open_update_leccion_form()
  {
    try {
      $id = clean($_POST['id']);
      if (!$leccion = leccionModel::by_id($id)) {
        throw new PDOException('La lección no existe en la base de datos.');
      }

      json_output(json_build(200, $leccion));


    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function update_leccion_form()
  {
    try {
      $id          = (int) $_POST['id']; // id lección
      $titulo      = clean($_POST['titulo']);
      $tipo        = clean($_POST['tipo']);
      $contenido   = clean($_POST['contenido']);

      $data =
      [
        'titulo'      => $titulo,
        'tipo'        => $tipo,
        'contenido'   => $contenido
      ];

      if(!leccionModel::update(leccionModel::$t1, ['id' => $id], $data)) {
        json_output(json_build(400, null, 'Hubo un error al actualizar la lección.'));
      }
  
      // se guardó con éxito
      $leccion = leccionModel::by_id($id);
      json_output(json_build(200, $leccion, 'Lección actualizada con éxito.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function save_new_order()
  {
    try {
      if (!isset($_POST['lecciones'])) {
        throw new Exception('Hubo un error en la petición.');
      }

      if (empty($_POST['lecciones'])) {
        throw new Exception('No hay lecciones para actualizar.');
      }

      // Iteramos sobre todas las lecciones
      foreach ($_POST['lecciones'] as $l) {
        if(!leccionModel::update(leccionModel::$t1, ['id' => $l['id']], ['orden' => $l['index']])) {
          continue;
        }
      }

      json_output(json_build(200, null, 'Lecciones actualizadas con éxito.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function update_leccion_status()
  {
    try {
      $id     = clean($_POST['id']);

      // Validar que exista la lección
      if (!$leccion = leccionModel::by_id($id)) {
        throw new PDOException('No existe la lección seleccionada.');
      }
      
      $status = $leccion['status'];
      if ($status === 'lista') {
        $status = 'pendiente';
      } else {
        $status = 'lista';
      }

      // Actualizamos la lección y su status
      if (!leccionModel::update(leccionModel::$t1, ['id' => $id], ['status' => $status])) {
        throw new PDOException('Hubo un error al actualizar el status de la lección.');
      }

      // Checar el status del temario
      check_temario_status($leccion['id_temario']);

      $leccion = leccionModel::by_id($id);
      json_output(json_build(200, $leccion, 'Lección actualizada con éxito.'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    } catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }
}