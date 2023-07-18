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
    // Validación de sesión de usuario, descomentar si requerida
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
    if (!$temario = temarioModel::by_id($id)) {
      Flasher::new('No existe el temario.', 'danger');
      Redirect::to('home');
    }

    $data = 
    [
      'title' => sprintf('Temario #%s', $temario['numero']),
      't'     => $temario
    ];
    
    View::render('ver', $data);
  }

  function agregar()
  {
    try {
      
      $data =
      [
        'numero'      => rand(111111, 999999),
        'titulo'      => '',
        'descripcion' => '',
        'status'      => 'borrador',
        'creado'      => now(),
        'actualizado' => now()
      ];

      if (!$id = temarioModel::add(temarioModel::$t1, $data)) {
        throw new PDOException('Hubo un problema al guardar el registro.');
      }

      Redirect::to(sprintf('temarios/ver/%s', $id));

    } catch (PDOException $e) {
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
    try {
      if (!$temario = temarioModel::by_id($id)) {
        throw new Exception('No existe el temario seleccionado.');
      }

      $sql = 'DELETE t1, t2
      FROM temarios t1
      LEFT JOIN lecciones t2 ON t1.id = t2.id_temario
      WHERE t1.id = :id';

      if (!temarioModel::query($sql, ['id' => $id])) {
        throw new Exception('No pudimos borrar el temario.');
      }

      Flasher::new(sprintf('Temario <b>#%s</b> borrado con éxito.', $temario['numero']), 'success');
      Redirect::back();

    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }

  function descargar($id)
  {
    try {
      if (!$temario = temarioModel::by_id($id)) {
        throw new Exception('No existe el temario seleccionado.');
      }

      // Generar un nuevo archivo de forma dinámica
      $file = sprintf('Export_temario_%s.txt', time());

      // Abrir el archivo
      if (!$txt = fopen($file, 'w')) {
        throw new Exception('No pudimos descargar el archivo.');
      }

      $content = '';

      // Agregamos información al archivo
      $content .= sprintf("Título: %s\n", $temario['titulo']);
      $content .= sprintf("Descripción: %s\n", $temario['descripcion']);
      $content .= sprintf("Creado: %s\n\n", format_date($temario['creado']));

      // Agregamos las lecciones
      if (empty($temario['lecciones'])) {
        $content .= "No hay lecciones en el temario.\n";
      } else {
        foreach ($temario['lecciones'] as $l) {
          $content .= sprintf("- (%s) %s\n Contenido:\n %s\n", $l['tipo'], $l['titulo'], empty($l['contenido']) ? 'No hay contenido.' : $l['contenido']);
        }
      }

      // Guardamos los cambios
      fwrite($txt, $content);
      fclose($txt);

      // Descargamos el archivo
      header('Content-Description: File Transfer');
      header('Content-Disposition: attachment; filename='.basename($file));
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: '.filesize($file));
      header('Content-Type: text/plain');
      readfile($file);

      // Borramos el archivo temporal
      if (is_file($file)) {
        unlink($file);
      }

    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }
}