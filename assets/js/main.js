$(document).ready(function() {

  // Toast para notificaciones
  //toastr.warning('My name is Inigo Montoya. You killed my father, prepare to die!');

  // Waitme
  //$('body').waitMe({effect : 'orbit'});
  
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
  if ($('.summernote').length !== 0) {
    $('.summernote').summernote({
      placeholder: 'Escribe en este campo...',
      tabsize: 2,
      height: 300
    });
  }

  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  ///////// NO REQUERIDOS, SOLO PARA EL PROYECTO DEMO DE GASTOS E INGRESOS
  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  
  // Agregar un movimiento
  $('.bee_add_movement').on('submit', bee_add_movement);
  function bee_add_movement(event) {
    event.preventDefault();

    var form    = $('.bee_add_movement'),
    hook        = 'bee_hook',
    action      = 'add',
    data        = new FormData(form.get(0)),
    type        = $('#type').val(),
    description = $('#description').val(),
    amount      = $('#amount').val();
    data.append('hook', hook);
    data.append('action', action);

    // Validar que este seleccionada una opción type
    if(type === 'none') {
      toastr.error('Selecciona un tipo de movimiento válido', '¡Upss!');
      return;
    }

    // Validar description
    if(description === '' || description.length < 5) {
      toastr.error('Ingresa una descripción válida', '¡Upss!');
      return;
    }

    // Validar amount
    if(amount === '' || amount <= 0) {
      toastr.error('Ingresa un monto válido', '¡Upss!');
      return;
    }

    // AJAX
    $.ajax({
      url: 'ajax/bee_add_movement',
      type: 'post',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 201) {
        toastr.success(res.msg, '¡Bien!');
        form.trigger('reset');
        bee_get_movements();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  // Cargar movimientos
  bee_get_movements();
  function bee_get_movements() {
    var wrapper = $('.bee_wrapper_movements'),
    hook        = 'bee_hook',
    action      = 'load';

    if (wrapper.length === 0) {
      return;
    }

    $.ajax({
      url: 'ajax/bee_get_movements',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action
      },
      beforeSend: function() {
        wrapper.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        wrapper.html(res.data);
      } else {
        toastr.error(res.msg, '¡Upss!');
        wrapper.html('');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
      wrapper.html('');
    }).always(function() {
      wrapper.waitMe('hide');
    })
  }

  // Actualizar un movimiento
  $('body').on('dblclick', '.bee_movement', bee_update_movement);
  function bee_update_movement(event) {
    var li              = $(this),
    id                  = li.data('id'),
    hook                = 'bee_hook',
    action              = 'get',
    add_form            = $('.bee_add_movement'),
    wrapper_update_form = $('.bee_wrapper_update_form');

    // AJAX
    $.ajax({
      url: 'ajax/bee_update_movement',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        wrapper_update_form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        wrapper_update_form.html(res.data);
        add_form.hide();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      wrapper_update_form.waitMe('hide');
    })
  }

  $('body').on('submit', '.bee_save_movement', bee_save_movement);
  function bee_save_movement(event) {
    event.preventDefault();

    var form    = $('.bee_save_movement'),
    hook        = 'bee_hook',
    action      = 'update',
    data        = new FormData(form.get(0)),
    type        = $('select[name="type"]', form).val(),
    description = $('input[name="description"]', form).val(),
    amount      = $('input[name="amount"]', form).val(),
    add_form            = $('.bee_add_movement');
    data.append('hook', hook);
    data.append('action', action);

    // Validar que este seleccionada una opción type
    if(type === 'none') {
      toastr.error('Selecciona un tipo de movimiento válido', '¡Upss!');
      return;
    }

    // Validar description
    if(description === '' || description.length < 5) {
      toastr.error('Ingresa una descripción válida', '¡Upss!');
      return;
    }

    // Validar amount
    if(amount === '' || amount <= 0) {
      toastr.error('Ingresa un monto válido', '¡Upss!');
      return;
    }

    // AJAX
    $.ajax({
      url: 'ajax/bee_save_movement',
      type: 'post',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, '¡Bien!');
        form.trigger('reset');
        form.remove();
        add_form.show();
        bee_get_movements();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  // Borrar un movimiento
  $('body').on('click', '.bee_delete_movement', bee_delete_movement);
  function bee_delete_movement(event) {
    var boton   = $(this),
    id          = boton.data('id'),
    hook        = 'bee_hook',
    action      = 'delete',
    wrapper     = $('.bee_wrapper_movements');

    if(!confirm('¿Estás seguro?')) return false;

    $.ajax({
      url: 'ajax/bee_delete_movement',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        wrapper.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, 'Bien!');
        bee_get_movements();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      wrapper.waitMe('hide');
    })
  }

  // Guardar o actualizar opciones
  $('.bee_save_options').on('submit', bee_save_options);
  function bee_save_options(event) {
    event.preventDefault();

    var form = $('.bee_save_options'),
    data     = new FormData(form.get(0)),
    hook     = 'bee_hook',
    action   = 'add';
    data.append('hook', hook);
    data.append('action', action);

    // AJAX
    $.ajax({
      url: 'ajax/bee_save_options',
      type: 'post',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200 || res.status === 201) {
        toastr.success(res.msg, '¡Bien!');
        bee_get_movements();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  //////////////////////////////////////////////////////////////////
  // Actualización de temario
  $('#temario_form').on('submit', temario_form);
  function temario_form(e) {
    e.preventDefault();

    var form    = $(this),
    data        = new FormData(form.get(0));
    
    // AJAX
    $.ajax({
      url: 'ajax/temario_form',
      type: 'post',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, '¡Bien!');
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  /**
   * Agragando una nueva lección
   */
  $('#add_leccion_form').on('submit', add_leccion_form);
  function add_leccion_form(e) {
    e.preventDefault();

    var form = $(this),
    data     = new FormData(form.get(0));
    
    // AJAX
    $.ajax({
      url: 'ajax/add_leccion_form',
      type: 'post',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 201) {
        toastr.success(res.msg, '¡Bien!');
        form.trigger('reset');

        // Vamos a llamar a la función para recargar el listado de lecciones
        get_lecciones();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  // Cargar lista de lecciones
  get_lecciones();
  function get_lecciones() {
    var wrapper = $('.wrapper_lecciones'),
    id          = wrapper.data('id'),
    hook        = 'bee_hook',
    action      = 'get';

    if (wrapper.length === 0) {
      return;
    }

    $.ajax({
      url: 'ajax/get_lecciones',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        wrapper.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        wrapper.html(res.data);
        init_lecciones_acordion();
        re_enumerar();

      } else {
        toastr.error(res.msg, '¡Upss!');
        wrapper.html(res.msg);
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
      wrapper.html('Hubo un error al cargar las lecciones, intenta más tarde.');
    }).always(function() {
      wrapper.waitMe('hide');
    })
  }

  // Inicializa el plugin o widget del acordeon
  function init_lecciones_acordion() {
    $( "#accordion" )
    .accordion({
      header: "> div > h3",
      collapsible: true
    })
    .sortable({
      axis: "y",
      handle: "h3",
      stop: function( event, ui ) {
        // IE doesn't register the blur when sorting
        // so trigger focusout handlers to remove .ui-state-focus
        ui.item.children( "h3" ).triggerHandler( "focusout" );
        
        // Refresh accordion to handle new order
        $( this ).accordion( "refresh" );
        save_new_order();
      }
    });
  }

  

  // Guarda el nuevo orden de las lecciones
  function save_new_order() {
    var acordion = $('#accordion'),
    divs         = $('.group', acordion),
    lecciones    = [],
    action       = 'put',
    hook         = 'bee_hook',
    wrapper      = $('.wrapper_lecciones');

    // Mapear el nuevo array
    divs.map(function(i, leccion) {
      var leccion = {'index': i, 'id': leccion.getAttribute('data-id')};
      lecciones.push(leccion);
    });

    // Petición http
    $.ajax({
      url: 'ajax/save_new_order',
      type: 'post',
      dataType: 'json',
      cache: false,
      data : {action, hook, lecciones},
      beforeSend: function() {
        wrapper.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, '¡Bien!');
        re_enumerar();

      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      wrapper.waitMe('hide');
    })
  }

  function re_enumerar() {
    var acordion = $('#accordion'),
    divs         = $('.group', acordion);

    divs.each(function(i, leccion) {
      var h3 = $('h3', leccion);
      
      $('span.numeracion', h3).html('#' + (i + 1) + ' ');
    })
  }

  // Borrar una lección
  $('body').on('click', '.delete_leccion', delete_leccion);
  function delete_leccion(e) {
    var boton   = $(this),
    id          = boton.data('id'),
    hook        = 'bee_hook',
    action      = 'delete';

    if(!confirm('¿Estás seguro?')) return false;

    $.ajax({
      url: 'ajax/delete_leccion',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        $('body').waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, '¡Bien!');
        get_lecciones();
      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      $('body').waitMe('hide');
    })
  }

  // Cargar información de lección
  $('body').on('click', '.open_update_leccion_form', open_update_leccion_form);
  function open_update_leccion_form(e) {
    e.preventDefault();

    var button = $(this),
    id         = button.data('id'),
    action     = 'get',
    hook       = 'bee_hook',
    form_a     = $('#add_leccion_form'),
    form_e     = $('#update_leccion_form');

    // Cargar la información del registro de la lección
    $.ajax({
      url: 'ajax/open_update_leccion_form',
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        hook, action, id
      },
      beforeSend: function() {
        $('body').waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        $('[name="id"]', form_e).val(res.data.id);
        $('[name="titulo"]', form_e).val(res.data.titulo);
        $('[name="contenido"]', form_e).val(res.data.contenido);
        $('[name="tipo"]', form_e).val(res.data.tipo);

        form_a.closest('.card').fadeOut();
        form_e.closest('.card').fadeIn();

      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      $('body').waitMe('hide');
    })
  }

  // Cancelar edición de lección
  $('.cancelar_update_leccion').on('click', cancelar_update_leccion);
  function cancelar_update_leccion(e) {
    e.preventDefault();

    var button = $(this),
    form_a     = $('#add_leccion_form'),
    form_e     = $('#update_leccion_form');

    form_e.trigger('reset');
    form_e.closest('.card').fadeOut();
    form_a.closest('.card').fadeIn();
    return true;
  }

  // Guardar cambios de lección
  $('#update_leccion_form').on('submit', update_leccion_form);
  function update_leccion_form(e) {
    e.preventDefault();

    var form = $(this),
    form_a   = $('#add_leccion_form'),
    data     = new FormData(form.get(0));

    if (!confirm('¿Estás seguro?')) return;
    
    // AJAX
    $.ajax({
      url: 'ajax/update_leccion_form',
      type: 'post',
      dataType: 'json',
      contentType: false,
      processData: false,
      cache: false,
      data : data,
      beforeSend: function() {
        form.waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, '¡Bien!');
        get_lecciones();
        form.closest('.card').fadeOut();
        form.trigger('reset');
        form_a.closest('.card').fadeIn();

      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      form.waitMe('hide');
    })
  }

  
});