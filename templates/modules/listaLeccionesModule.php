<?php if (!empty($d->lecciones)): ?>
  <div id="accordion">
    <?php foreach ($d->lecciones as $l): ?>
      <div class="group" data-id="<?php echo $l->id; ?>">
        <h3 class="clearfix">
          <span class="numeracion"></span>
          <?php echo sprintf('%s %s', format_tipo_leccion($l->tipo), $l->titulo); ?>
        </h3>
        <div>
          <?php echo empty($l->contenido) ? '<span class="text-muted">Sin contenido.</span>' : $l->contenido; ?>
          <div class="mt-3">
            <div class="btn-group">
              <button class="btn btn-success btn-sm open_update_leccion_form" data-id="<?php echo $l->id; ?>"><i class="fas fa-edit"></i></button>
              <button class="btn btn-danger btn-sm delete_leccion" data-id="<?php echo $l->id; ?>"><i class="fas fa-trash"></i></button>
              <button class="btn btn-sm update_leccion_status <?php echo $l->status === 'pendiente' ? 'btn-warning text-dark' : 'btn-success' ; ?>" data-id="<?php echo $l->id; ?>" data-status="<?php echo $l->status; ?>"><i class="fas fa-check"></i> Lista</button>

            </div>
            
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="text-center py-5">
    <img src="<?php echo IMAGES.'yumi_empty.png'; ?>" alt="No hay lecciones" class="img-fluid" style="width: 120px;">
    <p class="text-muted">No hay lecciones disponibles.</p>
  </div>
<?php endif; ?>

<script>
    $('body').on('click', '.update_leccion_status', update_leccion_status);
  function update_leccion_status(e) {
    e.preventDefault();

    var button = $(this),
    id         = button.data('id'),
    action     = 'put',
    hook       = 'bee_hook';

    if (!confirm('¿Estás seguro?')) return;
    
    // AJAX
    $.ajax({
      url: 'ajax/update_leccion_status',
      type: 'post',
      dataType: 'json',
      cache: false,
      data : {action, hook, id},
      beforeSend: function() {
        button.closest('.group').waitMe();
      }
    }).done(function(res) {
      if(res.status === 200) {
        toastr.success(res.msg, '¡Bien!');

        if (res.data.status === 'pendiente') {
          button.removeClass('btn-success').addClass('btn-warning text-dark');
        } else {
          button.removeClass('btn-warning text-dark').addClass('btn-success');
        }

      } else {
        toastr.error(res.msg, '¡Upss!');
      }
    }).fail(function(err) {
      toastr.error('Hubo un error en la petición', '¡Upss!');
    }).always(function() {
      button.closest('.group').waitMe('hide');
    })
  }
</script>