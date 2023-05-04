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
    form.waitMe('hide');
  })
}

// const eliminar = document.querySelector(".quitarmateriaprofesor");
// eliminar.addEventListener("onClick", quitar_materia_profesor);

// //$('body').on('click', '.quitarmateriaprofesor',quitar_materia_profesor);

// function quitar_materia_profesor(e) {
//   e.preventDefault();
//   var btn         = $(this),
//       wrapper     = $('.wrapper-materias-profesor'),
//       csrf        = Bee.csrf,
//       id_materia  = btn.data('id'),
//       id_profesor = wrapper.data('id'),
//       li          = btn.closest('li'),
//       action      = 'delete',
//       hook        = 'bee_hook';

//       console.log('entro a la funcion');
//       if(!confirm('Estas seguro?')) return false;

//   // AJAX
//   $.ajax({
//     url: 'ajax/quitar_materia_profesor',
//     type: 'post',
//     dataType: 'json',
//     cache: false,
//     data : {
//        csrf,
//        id_materia,
//        id_profesor,
//        action,
//        hook
//     },
//     beforeSend: function(){
//       li.waitMe();
//     }

//   }).done(function(res) {
//     if(res.status === 200) {
//       toastr.success(res.msg, '¡Bien!');
//       li.fadeOut();
//       get_materias_disponibles_profesor();
//       //get_materias_profesor();
//     } else {
//       toastr.error(res.msg, '¡Upss!');
//     }
//   }).fail(function(err) {
//     toastr.error('Hubo un error en la petición', '¡Upss!');
//   }).always(function() {
//     form.waitMe('hide');
//   })
// }

// // Agregar un movimiento
// $('.bee_add_movement').on('submit', bee_add_movement);
// function bee_add_movement(event) {
//   event.preventDefault();
//   toastr.error('entro', '¡Upss!');
//   var form    = $('.bee_add_movement'),
//   hook        = 'bee_hook',
//   action      = 'add',
//   data        = new FormData(form.get(0)),
//   type        = $('#type').val(),
//   description = $('#description').val(),
//   amount      = $('#amount').val();
//   data.append('hook', hook);
//   data.append('action', action);

//   // Validar que este seleccionada una opción type
//   if(type === 'none') {
//     toastr.error('Selecciona un tipo de movimiento válido', '¡Upss!');
//     return;
//   }

//   // Validar description
//   if(description === '' || description.length < 5) {
//     toastr.error('Ingresa una descripción válida', '¡Upss!');
//     return;
//   }

//   // Validar amount
//   if(amount === '' || amount <= 0) {
//     toastr.error('Ingresa un monto válido', '¡Upss!');
//     return;
//   }

//   // AJAX
//   $.ajax({
//     url: 'ajax/bee_add_movement',
//     type: 'post',
//     dataType: 'json',
//     contentType: false,
//     processData: false,
//     cache: false,
//     data : data,
//     beforeSend: function() {
//       form.waitMe();
//     }
//   }).done(function(res) {
//     if(res.status === 201) {
//       toastr.success(res.msg, '¡Bien!');
//       form.trigger('reset');
//       bee_get_movements();
//     } else {
//       toastr.error(res.msg, '¡Upss!');
//     }
//   }).fail(function(err) {
//     toastr.error('Hubo un error en la petición', '¡Upss!');
//   }).always(function() {
//     form.waitMe('hide');
//   })
// }

// // Cargar movimientos
// bee_get_movements();
// function bee_get_movements() {
//   var wrapper = $('.bee_wrapper_movements'),
//   hook        = 'bee_hook',
//   action      = 'load';

//   if (wrapper.length === 0) {
//     return;
//   }

//   $.ajax({
//     url: 'ajax/bee_get_movements',
//     type: 'POST',
//     dataType: 'json',
//     cache: false,
//     data: {
//       hook, action
//     },
//     beforeSend: function() {
//       wrapper.waitMe();
//     }
//   }).done(function(res) {
//     if(res.status === 200) {
//       wrapper.html(res.data);
//     } else {
//       toastr.error(res.msg, '¡Upss!');
//       wrapper.html('');
//     }
//   }).fail(function(err) {
//     toastr.error('Hubo un error en la petición', '¡Upss!');
//     wrapper.html('');
//   }).always(function() {
//     wrapper.waitMe('hide');
//   })
// }

// // Actualizar un movimiento
// $('body').on('dblclick', '.bee_movement', bee_update_movement);
// function bee_update_movement(event) {
//   var li              = $(this),
//   id                  = li.data('id'),
//   hook                = 'bee_hook',
//   action              = 'get',
//   add_form            = $('.bee_add_movement'),
//   wrapper_update_form = $('.bee_wrapper_update_form');

//   // AJAX
//   $.ajax({
//     url: 'ajax/bee_update_movement',
//     type: 'POST',
//     dataType: 'json',
//     cache: false,
//     data: {
//       hook, action, id
//     },
//     beforeSend: function() {
//       wrapper_update_form.waitMe();
//     }
//   }).done(function(res) {
//     if(res.status === 200) {
//       wrapper_update_form.html(res.data);
//       add_form.hide();
//     } else {
//       toastr.error(res.msg, '¡Upss!');
//     }
//   }).fail(function(err) {
//     toastr.error('Hubo un error en la petición', '¡Upss!');
//   }).always(function() {
//     wrapper_update_form.waitMe('hide');
//   })
// }

// $('body').on('submit', '.bee_save_movement', bee_save_movement);
// function bee_save_movement(event) {
//   event.preventDefault();

//   var form    = $('.bee_save_movement'),
//   hook        = 'bee_hook',
//   action      = 'update',
//   data        = new FormData(form.get(0)),
//   type        = $('select[name="type"]', form).val(),
//   description = $('input[name="description"]', form).val(),
//   amount      = $('input[name="amount"]', form).val(),
//   add_form            = $('.bee_add_movement');
//   data.append('hook', hook);
//   data.append('action', action);

//   // Validar que este seleccionada una opción type
//   if(type === 'none') {
//     toastr.error('Selecciona un tipo de movimiento válido', '¡Upss!');
//     return;
//   }

//   // Validar description
//   if(description === '' || description.length < 5) {
//     toastr.error('Ingresa una descripción válida', '¡Upss!');
//     return;
//   }

//   // Validar amount
//   if(amount === '' || amount <= 0) {
//     toastr.error('Ingresa un monto válido', '¡Upss!');
//     return;
//   }

//   // AJAX
//   $.ajax({
//     url: 'ajax/bee_save_movement',
//     type: 'post',
//     dataType: 'json',
//     contentType: false,
//     processData: false,
//     cache: false,
//     data : data,
//     beforeSend: function() {
//       form.waitMe();
//     }
//   }).done(function(res) {
//     if(res.status === 200) {
//       toastr.success(res.msg, '¡Bien!');
//       form.trigger('reset');
//       form.remove();
//       add_form.show();
//       bee_get_movements();
//     } else {
//       toastr.error(res.msg, '¡Upss!');
//     }
//   }).fail(function(err) {
//     toastr.error('Hubo un error en la petición', '¡Upss!');
//   }).always(function() {
//     form.waitMe('hide');
//   })
// }

// // Borrar un movimiento
// $('body').on('click', '.bee_delete_movement', bee_delete_movement);
// function bee_delete_movement(event) {
//   var boton   = $(this),
//   id          = boton.data('id'),
//   hook        = 'bee_hook',
//   action      = 'delete',
//   wrapper     = $('.bee_wrapper_movements');

//   if(!confirm('¿Estás seguro?')) return false;

//   $.ajax({
//     url: 'ajax/bee_delete_movement',
//     type: 'POST',
//     dataType: 'json',
//     cache: false,
//     data: {
//       hook, action, id
//     },
//     beforeSend: function() {
//       wrapper.waitMe();
//     }
//   }).done(function(res) {
//     if(res.status === 200) {
//       toastr.success(res.msg, 'Bien!');
//       bee_get_movements();
//     } else {
//       toastr.error(res.msg, '¡Upss!');
//     }
//   }).fail(function(err) {
//     toastr.error('Hubo un error en la petición', '¡Upss!');
//   }).always(function() {
//     wrapper.waitMe('hide');
//   })
// }

// // Guardar o actualizar opciones
// $('.bee_save_options').on('submit', bee_save_options);
// function bee_save_options(event) {
//   event.preventDefault();

//   var form = $('.bee_save_options'),
//   data     = new FormData(form.get(0)),
//   hook     = 'bee_hook',
//   action   = 'add';
//   data.append('hook', hook);
//   data.append('action', action);

//   // AJAX
//   $.ajax({
//     url: 'ajax/bee_save_options',
//     type: 'post',
//     dataType: 'json',
//     contentType: false,
//     processData: false,
//     cache: false,
//     data : data,
//     beforeSend: function() {
//       form.waitMe();
//     }
//   }).done(function(res) {
//     if(res.status === 200 || res.status === 201) {
//       toastr.success(res.msg, '¡Bien!');
//       bee_get_movements();
//     } else {
//       toastr.error(res.msg, '¡Upss!');
//     }
//   }).fail(function(err) {
//     toastr.error('Hubo un error en la petición', '¡Upss!');
//   }).always(function() {
//     form.waitMe('hide');
//   })
// }

});