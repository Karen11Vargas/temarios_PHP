<?php 

class homeController extends Controller {
  function __construct()
  {
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }
  }

  function index()
  {
   
    
    $data =
    [
      'title' => 'Temarios',
      'temarios'=>temarioModel::all_paginated()
    ];

    View::render('index', $data);
  }


}