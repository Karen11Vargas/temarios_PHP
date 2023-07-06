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
      'temarios'=>[]
    ];

    View::render('index', $data);
  }


  function flash()
  {
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }

    View::render('flash', ['title' => 'Flash', 'user' => User::profile()]);
  }

}