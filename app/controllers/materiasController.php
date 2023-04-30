<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de materias
 */

class materiasController extends Controller {
  function __construct()
  {
    // Validación de sesión de usuario, descomentar si requerida
    /**
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }
    */
  }
  
  function index()
  {

    if(!is_profesor(get_user_role())){
      Flasher::new(get_notification(),'danger');
      Redirect::to('dashboard');
    } 

    $data = 
    [
      'title'    => 'Todas las materias',
      'slug'     => 'materias',
      'button'   => ['url' => 'materias/agregar','text' => '<i class="fas fa-plus""></i> Agregar Materia'],
      'materias' => materiaModel::all_paginate()
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function ver($id)
  {
    if(!is_profesor(get_user_role())){
      Flasher::new(get_notification(),'danger');
      Redirect::to('dashboard');
    } 

    if(!$materia = materiaModel::by_id($id)){
      Flasher::new('No existe la materia en la base de datos.','danger');
      Redirect::to('materias');
    }
    $data = 
    [
      'title'    => sprintf('Viendo %s',$materia['nombre']),
      'slug'     => 'materias',
      'button'   => ['url' => 'materias','text' => '<i class="fas fa-table"></i> Materias'],
      'm' => $materia
    ];

    View::render('ver',$data);
  }

  function agregar()
  {
    $data =
    [
       'title' => 'Agregar Materia',
       'slug'  => 'materias'
    ];

    View::render('agregar', $data);
  }

  function post_agregar()
  {
     try
     {
        if(!check_posted_data(['csrf','nombre','descripcion'], $_POST) || !Csrf::validate($_POST['csrf'])){
          Flasher::new(get_notification(0),'danger');
          Redirect::to('materias');
        }

        //VALIDAR ROL
        if(!is_admin(get_user_role())){
          Flasher::new(get_notification(0),'danger');
          Redirect::to(URL);
        }

        $nombre = clean($_POST["nombre"]);
        $descripcion = clean($_POST["descripcion"]);

        if(strlen($nombre) < 5){
          Flasher::new("El nombre de la materia es demasiado corto.");
          Redirect::to('materias/agregar');
        }

        //VALIDAR QUE EL NOMBRE DE LA MATERIA NO EXISTA EN LA BASE DE DATOS
        $sql = 'SELECT * FROM tb_materias WHERE nombre = :nombre LIMIT 1';

        if(materiaModel::query($sql, ['nombre'=> $nombre])){
          Flasher::new(sprintf('Ya existe la materia <b>%s</b> en la base de datos.',$nombre));
          Redirect::to('materias/agregar');
        }

        $data = 
        [
          'nombre'          => $nombre,
          'descripcion'     => $descripcion,
          'fecha_Creado'    => now()
        ];

        if(!$id = materiaModel::add(materiaModel::$t1, $data)){
          Flasher::new('Hubo un error al guardar el registro.','danger');
          Redirect::to('materias/agregar');
        }

        Flasher::new(sprintf('Materia <b>%s</b> agregada con exito.',$nombre),'success');
        Redirect::back();

     } catch(PDOException $e)
     {
        Flasher::new($e -> getMessage(),'danger');
        Redirect::back();
     } catch(Exception $e)
     {
        Flasher::new($e -> getMessage(),'danger');
        Redirect::back();
     }
  }

  function post_editar()
  {
    try
    {
       if(!check_posted_data(['csrf','id','nombre','descripcion'], $_POST) || !Csrf::validate($_POST['csrf'])){
        Flasher::new('Acceso no Autorizado.','danger');
        Redirect::to('materias');
       }

       //VALIDAR ROL
       if(!is_admin(get_user_role())){
        Flasher::new(get_notification(0),'danger');
        Redirect::to(URL);
      }
        
       $id = clean($_POST["id"]);
       $nombre = clean($_POST["nombre"]);
       $descripcion = clean($_POST["descripcion"]);

      // VALIDAR QUE EXISTA LA MATERIA EN LA BASE DE DATOS
       if(!$materia = materiaModel::by_id($id)){
        Flasher::new('No existe la materia en la bases de datos.','danger');
        Redirect::to('materias');
       }

       // VALIDAR LA LONGITUD DEL NOMBRE
       if(strlen($nombre) < 5){
        Flasher::new('El nombre de la materia es demasiado corto.','danger');
        Redirect::to('materias/ver/'.$id);
       }

       //VALIDAR QUE EL NOMBRE DE LA MATERIA NO EXISTA EN LA BASE DE DATOS
       $sql = 'SELECT * FROM tb_materias WHERE id != :id AND nombre = :nombre LIMIT 1';

       if(materiaModel::query($sql, ['id' => $id,'nombre'=> $nombre])){
         Flasher::new(sprintf('Ya existe la materia <b>%s</b> en la base de datos.',$nombre));
         Redirect::to('materias/ver/'.$id);
       }

       $data = 
       [ 
         'nombre'      => $nombre,
         'descripcion' => $descripcion
       ];

       if(!materiaModel::update(materiaModel::$t1,['id' =>$id], $data)){
         Flasher::new('Hubo un error al Actualizar el registro.','danger');
         Redirect::to('materias/ver/'.$id);
       }

       Flasher::new(sprintf('Materia <b>%s</b> Actualizada con exito.',$nombre),'success');
       Redirect::back();

    } catch(PDOException $e)
    {
       Flasher::new($e -> getMessage(),'danger');
       Redirect::back();
    } catch(Exception $e)
    {
       Flasher::new($e -> getMessage(),'danger');
       Redirect::back();
    }
  }

  function borrar($id)
  {
    try
    {
       if(!check_get_data(['_t'], $_GET) || !Csrf::validate($_GET['_t'])){
        Flasher::new("erro en check",'danger');
        Redirect::to('materias');
       }
        //VALIDAR ROL
        if(!is_admin(get_user_role())){
          Flasher::new("error en no eres admin",'danger');
          Redirect::to(URL);
        }

       // VALIDAR QUE EXISTA LA MATERIA EN LA BASE DE DATOS
       if(!$materia = materiaModel::by_id($id)){
        Flasher::new('No existe la materia en la bases de datos.','danger');
        Redirect::to('materias');
       }
       
       //Eliminiar un registro a la base de datos
       if(!materiaModel::remove(materiaModel::$t1,['id' =>$id],1)){
         Flasher::new('Hubo un error al Eliminar el registro.','danger');
         Redirect::to('materias');
       }

       Flasher::new(sprintf('Materia <b>%s</b> borrada con exito.',$materia['nombre']),'success');
       Redirect::back();

    } catch(PDOException $e)
    {
       Flasher::new($e -> getMessage(),'danger');
       Redirect::back();
    } catch(Exception $e)
    {
       Flasher::new($e -> getMessage(),'danger');
       Redirect::back();
    }
  }
}