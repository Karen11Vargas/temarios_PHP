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
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }
   
  }
  
  function index()
  {
    Redirect::to('home');
  }

  function ver($id)
  {
    if(!$temario = temarioModel::by_id($id)){
      Flasher::new('No existe el temario.', 'danger');
      Redirect::to('home');
    }

    $data = 
    [
      'title' => sprintf('Temario #%s', $temario['numero']),
      't'=> $temario
    ];
    View::render('ver', $data);
  }

  function agregar()
  {
    try {
      $data=
      [
        'numero' => rand(111111, 999999),
        'titulo' => '',
        'descripcion'=>'',
        'estado'=>'borrador',
        'creado'=>now(),
        'actualizado'=>now()
      ];
      if (!$id =temarioModel::add(temarioModel::$t1, $data)){
        throw new PDOExeption('Hubo un error al guardar el registro');
      }

      Redirect::to(sprintf('temarios/ver/%s', $id));
  
    } catch (PDOExeption $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::to('home');
    }
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