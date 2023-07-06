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

function getTemarioEstados(){
  return
  [
    ['borrador', 'Borrador'],
    ['realizado', 'Realizado']
  ];
}

function formatTemarioEstado($estado){
 $text = '';
 $classes = '';
 $icon = '';
 $output = '';
 $placeholder = '<span class="%s"><i class="%s"></i>%s</span>';

 
 switch ($estado) {
  case 'borrador':
    $text = 'Borrador';
    $classes = 'badge bg-warning text-dark';
    $icon = 'fas fa-eraser';
    $output = sprintf($placeholder, $classes, $icon, $text);
    break;
  case 'realizado':
    $text = 'Realizado';
    $classes = 'badge bg-success';
    $icon = 'fas fa-check';
    $output = sprintf($placeholder, $classes, $icon, $text);
    break;
  default:
    $text = 'Desconocido';
    $classes = 'badge bg-danger';
    $icon = 'fas fa-question-circle';
    $output = sprintf($placeholder, $classes, $icon, $text);
    
    break;
 }

 return $output;

}

function get_tipo_lecciones(){
  return[
    ['texto', 'Texto'],
    ['video', 'Video'],
    ['descarga', 'Descarga'],
    ['recurso_externo', 'Recurso Externo']
  ];
}

function format_tipo_leccion($tipo_leccion){

  $icon='';
  $placeholder='<i class="%s"></i>';

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