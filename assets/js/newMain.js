$(document).ready(function() {

  // Toast para notificaciones
  //toastr.warning('My name is Inigo Montoya. You killed my father, prepare to die!');

  // Waitme
  //$('body').waitMe({effect : 'orbit'});
  console.log('////////// Bienvenido a Bee Framework Versión ' + Bee.bee_version + ' //////////');
  console.log('//////////////////// www.joystick.com.mx ////////////////////');
  console.log(Bee);
 
  /**
   * Prueba de peticiones ajax al backend en versión 1.1.3
   */
  function test_ajax() {
    var body = $('body'),
    hook     = 'bee_hook',
    action   = 'post',
    csrf     = Bee.csrf;

    if ($('#test_ajax').length == 0) return;

    $.ajax({
      url: 'ajax/test',
      type: 'post',
      dataType: 'json',
      data : { hook , action , csrf },
      beforeSend: function() {
        body.waitMe();
      }
    }).done(function(res) {
      toastr.success(res.msg);
      console.log(res);
    }).fail(function(err) {
      toastr.error('Prueba AJAX fallida.', '¡Upss!');
    }).always(function() {
      body.waitMe('hide');
    })
  }
  
  /**
   * Alerta para confirmar una acción establecida en un link o ruta específica
   */
  $('body').on('click', '.confirmar', function(e) {
    e.preventDefault();

    let url = $(this).attr('href'),
    ok      = confirm('¿Estás seguro?');

    // Redirección a la URL del enlace
    if (ok) {
      window.location = url;
      return true;
    }
    
    console.log('Acción cancelada.');
    return true;
  });

  /**
   * Inicializa summernote el editor de texto avanzado para textareas
   */
  function init_summernote() {
    if ($('.summernote').length == 0) return;

    $('.summernote').summernote({
      placeholder: 'Escribe en este campo...',
      tabsize: 2,
      height: 300
    });
  }

  /**
   * Inicializa tooltips en todo el sitio
   */
  function init_tooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  }

// Inicialización de elementos
init_summernote();
init_tooltips();
test_ajax();


$('#dataTable').DataTable({
language:{
  search: "Buscar&nbsp;:",
  lengthMenu: "Mostrar _MENU_ registros",
  info:       "Mostrando _START_ a _END_ de _TOTAL_ registros.",
  infoEmpy:   "Mostrando 0 registros.",
  infoFiltered: "(Filtrando de _MAX_ registros en total.)",
  infoPosFix: "",
  zeroRecords: "",
  emptyTable: "No hay informacion.",
  paginate: {
    first: "Primera",
    previous: "Anterior",
    next: "Siguiente",
    last: "Ultima"
  }
},
paging: false,
searching: false,
aaSorting:[]
});

//funcion para cargar las materias disponibles
function get_materias_disponibles_profesor(){

var form = $('#profesor_asig_materia'),
    select = $('select',form),
    id_profesor = $('input[name="id"]', form).val(),
    wrapper     = $('#profesor_materia'),
    opciones = '',
    action = 'get',
    hook   = 'bee_hook';

if(form.length == 0) return;

//limpiar las opciones al cargar
select.html('');

//AJAX
$.ajax({

  url: 'ajax/get_materias_disponibles_profesor',
  type: 'get',
  dataType: 'json',
  data: {
    '_t' : Bee.csrf,
    id_profesor,
    action,
    hook
  },
  beforeSend: function(){
    wrapper.waitMe();
  }
}).done(function(res){
  if(res.status === 200){
    if(res.data.length === 0){
      select.html('<option disabled selected>No hay opciones disponibles.</option>')
      $('button', form).attr('disabled',true);
      return;
    }

    $.each(res.data, function(i,m){
      opciones += '<option value ="'+m.id+'">'+m.nombre+'</option>';
    });

    select.html(opciones);
    $('button', form).attr('disabled',false);
  
  }else{
    $('button', form).attr('disabled',true);
    toastr.error(res.msg, '!Upss!');
  }
}).fail(function(err){
  toastr.error('Hubo un error en la peticion','¡Upss!');
}).always(function(){
  wrapper.waitMe('hide');
})
}

//funcion para cargar las materias del profesor
function get_materias_profesor(){

var wrapper = $('.wrapper-materias-profesor'),
    id_profesor = wrapper.data('id'),
    action = 'get',
    hook   = 'bee_hook';

if(wrapper.length == 0) return;

//AJAX
$.ajax({

  url: 'ajax/get_materias_profesor',
  type: 'get',
  dataType: 'json',
  data: {
    '_t' : Bee.csrf,
    id_profesor,
    action,
    hook
  },
  beforeSend: function(){
    wrapper.waitMe();
  }
}).done(function(res){
  if(res.status === 200){
    wrapper.html(res.data);
  }else{
    wrapper.html(res.msg);
    toastr.error(res.msg, '!Upss!');
  }
}).fail(function(err){
  toastr.error('Hubo un error en la peticion','¡Upss!');
}).always(function(){
  wrapper.waitMe('hide');
})
}

//inicializar las funciones
get_materias_disponibles_profesor();
get_materias_profesor();

$('#profesor_asig_materia').on('submit', add_materia_profesor);
function add_materia_profesor(e) {
e.preventDefault();

var form        = $('#profesor_asig_materia'),
    select      = $('select',form),
    id_materia  = select.val(),
    id_profesor = $('input[name="id"]', form).val(),
    csrf        = $('input[name="csrf"]', form).val(),
    action      = 'post',
    hook        = 'bee_hook';

if(id_materia === undefined || id_materia === '') {
  toastr.error('Selecciona una materia válida.', '¡Upss!');
  return;
}

// AJAX
$.ajax({
  url: 'ajax/add_materia_profesor',
  type: 'post',
  dataType: 'json',
  data : {
     csrf,
     id_materia,
     id_profesor,
     action,
     hook
  },
  beforeSend: function(){
    form.waitMe();
  }

}).done(function(res) {
  if(res.status === 201) {
    toastr.success(res.msg, '¡Bien!');
    get_materias_disponibles_profesor();
    get_materias_profesor();
    
  } else {
    toastr.error(res.msg, '¡Upss!');
  }
}).fail(function(err) {
  toastr.error('Hubo un error en la petición', '¡Upss!');
}).always(function() {
  form.waitMe('hide');
})
}

$('body').on('click', '.eliminar_materia_profesor',quitar_materia_profesor);
function quitar_materia_profesor(e) {
e.preventDefault();
var btn         = $(this),
    wrapper     = $('.wrapper-materias-profesor'),
    csrf        = Bee.csrf,
    id_materia  = btn.data('id'),
    id_profesor = wrapper.data('id'),
    li          = btn.closest('li'),
    action      = 'delete',
    hook        = 'bee_hook';

// AJAX
$.ajax({
  url: 'ajax/quitar_materia_profesor',
  type: 'post',
  dataType: 'json',
  cache: false,
  data : {
     csrf,
     id_materia,
     id_profesor,
     action,
     hook
  },
  beforeSend: function(){
    li.waitMe();
  }

}).done(function(res) {
  if(res.status === 200) {
    toastr.success(res.msg, '¡Bien!');
    li.fadeOut();
    get_materias_disponibles_profesor();
    //get_materias_profesor();
  } else {
    toastr.error(res.msg, '¡Upss!');
  }
}).fail(function(err) {
  toastr.error('Hubo un error en la petición', '¡Upss!');
}).always(function() {
  li.waitMe('hide');
})
}

});