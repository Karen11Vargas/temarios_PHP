<?php 

/**
 * La primera función de pruebas del curso de creando el framework MVC
 *
 * @return void
 */
function en_custom() {
  return 'ESTOY DENTRO DE CUSTOM_FUNCTIONS.';
}

/**
 * Carga las diferentes divisas soporatadas en el proyecto de pruebas
 *
 * @return void
 */
function get_coins() {
  return 
  [
    'MXN',
    'USD',
    'CAD',
    'EUR',
    'ARS',
    'AUD',
    'JPY'
  ];
}

function get_temario_estados()
{
  return
  [
    ['borrador', 'Borrador'],
    ['realizado', 'Realizado']
  ];
}

function format_temario_estado($estado)
{
  $text        = '';
  $classes     = '';
  $icon        = '';
  $placeholder = '<span class="%s"><i class="%s"></i> %s</span>';

  switch ($estado) {
    case 'borrador':
      $text    = 'Borrador';
      $classes = 'badge bg-warning text-dark';
      $icon    = 'fas fa-eraser';
      break;
    case 'realizado':
      $text    = 'Realizado';
      $classes = 'badge bg-success';
      $icon    = 'fas fa-check';
      break;
    default:
      $text    = 'Desconocido';
      $classes = 'badge bg-danger';
      $icon    = 'fas fa-question-circle';
  }

  return sprintf($placeholder, $classes, $icon, $text);
}

function get_tipo_lecciones()
{
  return
  [
    ['texto'          , 'Texto'],
    ['video'          , 'Video'],
    ['descarga'       , 'Descarga'],
    ['recurso_externo', 'Recurso Externo']
  ];
}

function format_tipo_leccion($tipo_leccion)
{
  $placeholder = '<i class="%s"></i>';
  $icon        = '';

  switch ($tipo_leccion) {
    case 'texto':
      $icon = 'fas fa-file-alt';
      break;

    case 'video':
      $icon = 'fas fa-video';
      break;

    case 'descarga':
      $icon = 'fas fa-download';
      break;

    case 'recurso_externo':
      $icon = 'fas fa-external-link-alt';
      break;
    
    default:
      $icon = 'fas fa-question-circle';
      break;
  }

  return sprintf($placeholder, $icon);
}

function check_temario_status($id_temario)
{
  if (!$temario = temarioModel::by_id($id_temario)) return false;

  // Validar lecciones
  if (empty($temario['lecciones'])) {
    temarioModel::update(temarioModel::$t1, ['id' => $id_temario], ['status' => 'borrador']);

    return true;
  }

  // Iterar todas las lecciones
  $lecciones       = $temario['lecciones'];
  $status          = $temario['status']; // borrador o realizado
  $total_lecciones = count($lecciones);
  $listas          = 0;
  $pendientes      = 0;

  foreach ($lecciones as $l) {
    if ($l['status'] === 'pendiente') {
      $pendientes++;
    } else {
      $listas++;
    }
  }

  // Actualizando el status del temario
  if ($total_lecciones == $listas && $status === 'borrador') {
    temarioModel::update(temarioModel::$t1, ['id' => $id_temario], ['status' => 'realizado']);
  } else if ($total_lecciones != $listas && $status === 'realizado') {
    temarioModel::update(temarioModel::$t1, ['id' => $id_temario], ['status' => 'borrador']);
  }

  return true;
}