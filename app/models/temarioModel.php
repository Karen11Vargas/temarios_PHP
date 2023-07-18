<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de temario
 */
class temarioModel extends Model {
  public static $t1   = 'temarios'; // Nombre de la tabla en la base de datos;
  
  // Nombre de tabla 2 que talvez tenga conexión con registros
  //public static $t2 = '__tabla 2___'; 
  //public static $t3 = '__tabla 3___'; 

  function __construct()
  {
    // Constructor general
  }
  
  static function all() {
    // Todos los registros
    $sql = 'SELECT * FROM temarios ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function all_paginated() {
    // Todos los registros
    $sql = 'SELECT t.*,
    (SELECT COUNT(l.id) FROM lecciones l WHERE l.id_temario = t.id) AS total_lecciones
    FROM temarios t
    ORDER BY t.id DESC';
    return PaginationHandler::paginate($sql);
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql  = 'SELECT * FROM temarios WHERE id = :id LIMIT 1';
    $rows = parent::query($sql, ['id' => $id]);

    if (!$rows) return [];

    // Si si existe el registro cargar las lecciones disponibles
    $rows = $rows[0];
    $rows['lecciones'] = leccionModel::by_temario($id);

    return $rows;
  }
}

