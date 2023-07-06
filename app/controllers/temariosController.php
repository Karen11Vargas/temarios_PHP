<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de temarios
 */
class temariosController extends Controller {
  function __construct()
  {
   
  }
  
  function index()
  {
    Redirect::to('home');
  }

  function ver($id)
  {

    $data = 
    [
      'title' => 'Reemplazar título',
    ];
    View::render('ver', $data);
  }

  function agregar()
  {
  
    View::render('agregar');
  }

  function post_agregar()
  {

  }

  function editar($id)
  {
    View::render('editar');
  }

  function post_editar()
  {

  }

  function borrar($id)
  {
    // Proceso de borrado
  }
}