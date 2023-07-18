<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de leccion
 */
class leccionModel extends Model {
  public static $t1   = 'lecciones'; // Nombre de la tabla en la base de datos;
  
  // Nombre de tabla 2 que talvez tenga conexión con registros
  //public static $t2 = '__tabla 2___'; 
  //public static $t3 = '__tabla 3___'; 

  function __construct()
  {
    // Constructor general
  }
  
  static function all() 
  {
    // Todos los registros
    $sql = 'SELECT * FROM lecciones ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM lecciones WHERE id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }

  static function by_temario($id_temario)
  {
    $sql = 'SELECT * FROM lecciones WHERE id_temario = :id_temario ORDER BY orden ASC';
    return ($rows = parent::query($sql, ['id_temario' => $id_temario])) ? $rows : [];
  }
}

