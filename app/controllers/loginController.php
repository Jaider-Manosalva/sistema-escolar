<?php 

class loginController extends Controller {
  function __construct()
  {
    if (Auth::validate()) {
      Flasher::new('Ya hay una sesión abierta.');
      Redirect::to('home/flash');
    }
  }

  function index()
  {
    $data =
    [
      'title'   => 'Ingresar a tu cuenta',
      'padding' => '0px'
    ];

    View::render('index', $data);
  }
  
  function activate()
  {
    try {
      
      if(!check_get_data(['email','hash'], $_GET)){
        throw new Exception('error en el check.');
      }

      //Data pasada en URL
      $email  = clean($_GET['email']);
      $hash   = clean($_GET['hash']);

      //verificar que exista el usuario con ese email
      if(!$user = usuarioModel::by_email($email)){
        throw new Exception('el email no existe.');
      }

      $id        = $user['id'];
      $nombre    = $user['nombre']; 
      $estado    = $user['estado'];
      $db_hash   = $user['hash'];

      //verificar el hash del usuario
      if($hash !== $db_hash){
        throw new Exception('error en el hash.');
      }

      //verificar el estado
      if($estado !== 'pendiente'){
        throw new Exception('La cuenta ya esta activa.');
      }

      //activar cuenta
      if(usuarioModel::update(usuarioModel::$t1,['id'=> $id],['estado' => 'activo']) === false){
        Flasher::new('Error de activacion consulta');
      }

      Flasher::new(sprintf('Tu correo electronico ha sido activado con exito <b>%s</b>,ya puedes iniciar sesion.',$nombre),'success');
      Redirect::to('login');
      
    }catch(Exception $e){
      Flasher::new($e->getMessage(),'danger');
      Redirect::to('login');
    }catch(PDOException $e){
      Flasher::new($e->getMessage(),'danger');
      Redirect::to('login');
    }
  }
  
  function post_login(){
    try{

      if (!Csrf::validate($_POST['csrf']) || !check_posted_data(['email','csrf','password'], $_POST)) {
         Flasher::new('Acceso no autorizado. problema en el crf', 'danger');
         Redirect::back();
       }

       // Data pasada del formulario
       $email    = clean($_POST['email']);
       $password = clean($_POST['password']);

       //verificar si el email es valido
       if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
          throw new Exception('El correo electronico no es valido.');
       }
 
       //verificar si el email es valido
       if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
          throw new Exception('El correo electronico no es valido.');
       }

       //verificar que exista el usuario
       if(!$user = usuarioModel::by_email($email)){
         throw new Exception('las credenciales no son correctas');
       }

       // Información del usuario loggeado, simplemente se puede reemplazar aquí con un query a la base de datos
       // para cargar la información del usuario si es existente
   
       if (!password_verify($password.AUTH_SALT, $user['password'])) {
         throw new Exception('las credenciales no son correctas');
       }

       //validar el status del usuario
       if($user['estado'] === 'pendiente'){
         mail_confirmar_cuenta($user['id']);
         Flasher::new("Confirma tu correo electronico para continuar.",'danger');
         Redirect::back();
       }

       // Loggear al usuario
       Auth::login($user['id'], $user);
       Redirect::to('dashboard');
  
     } catch (Exception $e) {
         Flasher::new($e->getMessage(),'danger');
         Redirect::back();
     }catch (Exception $e) {
         Flasher::new($e->getMessage(),'danger');
         Redirect::back();
     }
  }
  
}