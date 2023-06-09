<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de profesor
 */
class profesorModel extends Model
{
  public static $t1 = 'tb_usuarios'; // Nombre de la tabla en la base de datos;

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
    $sql = 'SELECT * FROM tb_usuarios WHERE rol = "profesor" ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function all_paginate()
  {
    // Todos los registros
    $sql = 'SELECT * FROM tb_usuarios WHERE rol = "profesor" ORDER BY id DESC';
    return PaginationHandler::paginate($sql);
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM tb_usuarios WHERE rol = "profesor" AND id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }
  static function by_documento($documento)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM tb_usuarios WHERE rol = "profesor" AND documento = :documento LIMIT 1';
    return ($rows = parent::query($sql, ['documento' => $documento])) ? $rows[0] : [];
  }

  static function asignar_materia($id_profesor, $id_materia)
  {
    $data =
      [
        'id_materia'  => $id_materia,
        'id_profesor' => $id_profesor
      ];

    if (!$id = self::add('tb_materias_profesores', $data)) return false;

    return $id;
  }

  static function quitar_materia($id_profesor, $id_materia)
  {
    $data =
      [
        'id_materia'  => $id_materia,
        'id_profesor' => $id_profesor
      ];

    return (self::remove('tb_materias_profesores', $data)) ? true :  false;
  }

  static function eliminar($id_profesor)
  {
    $sql = 'DELETE u, mp FROM tb_usuarios u JOIN tb_materias_profesores mp ON mp.Id_Profesor = u.id WHERE u.id = :id AND u.rol = "profesor"';
    return (parent::query($sql, ['id' => $id_profesor])) ? true : false; 
  }
}