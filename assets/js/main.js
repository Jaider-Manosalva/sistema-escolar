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
          toastr.error('No hay materias disponibles para el profesor.');
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

  get_materias_disponibles_profesor();


});