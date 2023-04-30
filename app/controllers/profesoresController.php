<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de profesores 
 */
class profesoresController extends Controller {
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
      Redirect::back();
    }
    $data = 
    [
      'title'      => 'Todos los Profesores',
      'slug'       => 'profesores',
      'button'     => ['url' => buildURL('profesores/agregar'),'text' => '<i class="fas fa-plus""></i> Agregar Profesor'],
      'profesores' => profesorModel::all_paginate()
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function ver($documento)
  {
    
    if(!$profesor = profesorModel::by_documento($documento)){
      Flasher::new('No existe el profesor en la base de datos.','danger');
      Redirect::to('profesores');
    }
    
    $data = 
    [
      'title'    => sprintf('Profesor #%s',$profesor['documento']),
      'slug'     => 'profesores',
      'button'   => ['url' => 'profesores','text' => '<i class="fas fa-table"></i> Profesores'],
      'p' => $profesor
    ];

    View::render('ver',$data);
  }

  function agregar()
  {
    try
    {
       if(!check_get_data(['_t'], $_GET) || !Csrf::validate($_GET['_t'])){
         Flasher::new(get_notification(0),'danger');
         Redirect::to('materias');
       }

       //VALIDAR ROL
       if(!is_admin(get_user_role())){
         Flasher::new(get_notification(0),'danger');
         Redirect::to(URL);
       }

       $documento = rand(111111,999999);
       
       $data = 
       [
        'documento' => $documento,
        'nombre' => null,
        'apellido' => null,
        'email' => null,
        'telefono' =>null,
        'password' => null,
        'hash' => generate_token(),
        'rol' => 'profesor',
        'estado' => 'pendiente',
        'fecha_Creacion' => now()
       ];

       if(!$id = profesorModel::add(profesorModel::$t1, $data)){
         Flasher::new(get_notification(2));
         Redirect::to('profesor/agregar');
       }

       Flasher::new(sprintf('Nuevo profesor <b>%s</b> agregado con exito.',$data['documento']),'success');
       Redirect::to(sprintf('profesores/ver/%s',$documento));

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
       if(!check_posted_data(['csrf','id','nombre','apellido','email','telefono','password'], $_POST) || !Csrf::validate($_POST['csrf'])){
         Flasher::new(get_notification(0),'danger');
         Redirect::to('materias');
       }

       //VALIDAR ROL
       if(!is_admin(get_user_role())){
         Flasher::new(get_notification(0),'danger');
         Redirect::to(URL);
       }

       $id = clean($_POST["id"]);

       if(!$profesor = profesorModel::by_id($id)){
        throw new Exception('No existe papa');
       }

       $nombre = clean($_POST['nombre']);
       $apellido = clean($_POST['apellido']);
       $email = clean($_POST['email']);
       $telefono = clean($_POST['telefono']);
       $password = clean($_POST['password']);

       if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
         throw new Exception('Ingresa un correo electronico valido');
       }
       
       $data = 
       [
        'nombre'   => $nombre,
        'apellido' => $apellido,
        'email'    => $email,
        'telefono' => $telefono
       ];

       //en caso de que se cambie el correo electronico
        if($profesor["email"] !== $email && !in_array($profesor['estado'],['pendiente','desconocido'])){
          $data['estado'] = 'pendiente';
        }
       //en caso que se cambie la contraseña
        if(!empty($password) && password_verify($password.AUTH_SALT,$profesor['password'])){
          $data['password'] = password_hash($password.AUTH_SALT, PASSWORD_BCRYPT); 
        }
       //insertar datos en la base de datos
       if(!profesorModel::update(profesorModel::$t1,['id' => $id], $data)){
         Flasher::new(get_notification(2));
         Redirect::to('profesor');
       }

       //volver a cargar la informacion del profesor
       $profesor = profesorModel::by_id($id);
       
       Flasher::new(sprintf('profesor <b>%s</b> actualizado con exito.',$profesor['nombre']),'success');
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
         Flasher::new(get_notification(0),'danger');
         Redirect::to('materias');
       }

       //VALIDAR ROL
       if(!is_admin(get_user_role())){
         Flasher::new(get_notification(0),'danger');
         Redirect::to(URL);
       }
       
       //Exista el profesor
       if(!$profesor = profesorModel::by_id($id)){
        throw new Exception('No existe el profesor');
       }

       //Borrarmos el registro
       if(!profesorModel::eliminar($profesor['id'])){
         throw new Exception(get_notification(0),'danger');
       }

       Flasher::new(sprintf('Profesor <b>%s</b> borrado con exito ya.',$profesor['nombre'],' ', $profesor['apellido']),'success');
       Redirect::to('profesores');

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