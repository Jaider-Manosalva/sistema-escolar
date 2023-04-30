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