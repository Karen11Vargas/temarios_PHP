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