<?php 

class ajaxController extends Controller {
  
  private $accepted_actions = ['get', 'post', 'put', 'delete', 'options', 'add', 'load'];
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
      $id = (int) $_POST['id'];
      $titulo = clean($_POST['titulo']);
      $descripcion = clean($_POST['descripcion']);

      $data =
      [
        'titulo'=>$titulo, 
        'descripcion'=>$descripcion
      ];

      if(!temarioModel::update(temarioModel::$t1, ['id'=>$id], $data)) {
        json_output(json_build(400, null, 'Hubo error al actualizar el temario'));
      }
  
      // se guardó con éxito
      $temario = temarioModel::by_id($id);
      json_output(json_build(200, $temario, 'Temario actualizado con éxito'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function add_leccion_form()
  {
    try {
      $id_temario = (int) $_POST['id_temario'];
      $titulo = clean($_POST['titulo']);
      $contenido = clean($_POST['contenido']);
      $tipo = clean($_POST['tipo']);
      $orden = 0;


      if(strlen($titulo) < 5){
        json_output(json_build(400, null, 'El titulo es demasiado corto'));
      }

      if(!$temario = temarioModel::by_id($id_temario)){
        json_output(json_build(400, null, 'El temario no existe'));
      }

      //Validar lecciones
      if(!empty($temario['lecciones'])){
        $ultima_leccion= end($temario['lecciones']);
        $ultimo_orden= $ultima_leccion['orden'];
        $orden= $ultimo_orden + 1;
      }

      $data =
      [
        'id_temario'=>$id_temario, 
        'titulo'=>$titulo, 
        'contenido'=>$contenido,
        'tipo'=>$tipo,
        'estado'=>'pendiente',
        'orden'=>$orden,
        'creado'=>now(),
        'actualizado'=>now() 
      ];

      if(!$id_leccion = leccionModel::add(leccionModel::$t1, $data)) {
        json_output(json_build(400, null, 'Hubo error al agregar la lección'));
      } 
  
      // se guardó con éxito
      $temario = temarioModel::by_id($id_temario);
      json_output(json_build(201, $temario, 'Leccion agregada con éxito'));
      
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }catch (PDOException $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function get_lecciones()
  {
    try {
      $id = clean($_POST['id']);
      $data = get_module('listaLecciones', temarioModel::by_id($id));
      json_output(json_build(200, $data));
    } catch(Exception $e) {
      json_output(json_build(400, $e->getMessage()));
    }

  }

  function bee_delete_movement()
  {
    try {
      $mov     = new movementModel();
      $mov->id = $_POST['id'];

      if(!$mov->delete()) {
        json_output(json_build(400, null, 'Hubo error al borrar el registro'));
      }

      json_output(json_build(200, null, 'Movimiento borrado con éxito'));
      
    } catch (Exception $e) {
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
}