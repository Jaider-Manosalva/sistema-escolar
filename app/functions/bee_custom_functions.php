<?php 

/**
 * Regresa el rol del usuario
 * 
 * @return mixed
 */
function get_user_role() {
  return $rol = get_user('rol');
}

function get_default_roles(){
  return ['root','admin'];
}

function is_root($rol){
  return in_array($rol,['root']);
}

function is_admin($rol){
  return in_array($rol,['admin','root']);
}

function is_profesor($rol){
  return in_array($rol,['profesor','admin','root']);
}

function is_alumno($rol){
  return in_array($rol,['alumno','admin','root']);
}

function is_user($rol, $roles_aceptados){
  $default = get_default_roles();

  if(!is_array($roles_aceptados)){
    array_push($default, $roles_aceptados);
    return in_array($rol, $default);
  }

  return in_array($rol, array_merge($default, $roles_aceptados));
}

/**
 * 0 Acceso no autorizado
 * 1 Acceso no autorizada
 * 
 * @param integer $index
 * @return mixed
 */
function get_notification($index = 0){

  $notificaciones = 
  [
    'Acceso no autorizado',
    'Acceso no autorizada',
    'Agregado con exito'
  ];

  return isset($notificaciones[$index]) ? $notificaciones[$index] : $notificaciones[0];
}

function format_status_user($status){
   
   $placeholder = '<div class="badge %s"><i class="%s"> %s</i></div>';
   $class = "";
   $icon = "";
   $text = "";

  switch($status){
   case 'pendiente': 
      $class = 'badge-warning text-dark';
      $icon = 'fas fa-clock';
      $text = 'Pendiente';
    break;
   case 'activo': 
      $class = 'badge-success';
      $icon = 'fas fa-check';
      $text = 'Activo';
    break;
   case 'suspendido': 
      $class = 'badge-danger';
      $icon = 'fas fa-times';
      $text = 'Suspendido';
    break;
   default: 
      $class = 'badge-danger';
      $icon = 'fas fa-question';
      $text = 'Desconocido';
   break;
  }
  return sprintf($placeholder,$class,$icon,$text);
}

function mail_confirmar_cuenta($id_usuario)
{
  if (!$usuario = usuarioModel::by_id($id_usuario)) return false;

  $nombre = $usuario['nombre'];
  $hash   = $usuario['hash'];
  $email  = $usuario['email'];
  $estado = $usuario['estado'];

  //si el estado no es pendiente no requiere activarse
  if($estado !== 'pendiente') return false;

  $subjet = sprintf('Confirma tu correo electronico por favor %s', $nombre);
  $alt     = sprintf('Debes confirmar tu correo electronico para poder ingresar a %s.', get_sitename());
  $url     = buildURL(URL.'login/activate',['email' => $email, 'hash' => $hash], false, false );
  $text    = '!Hola %s!<br>Para ingresar al sistema de <b>%s</b> primero debes confirmar tu direccion de correo electronico dando clic en el siguiente enlace seguro: <a href="%s">%s</a>';
  $body    = sprintf($text, $nombre, get_sitename(), $url, $url);

  if(send_email_plus(get_siteemail(), $email, $subjet, $body, $alt) !== true) return false;

  return true;
}