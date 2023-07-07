<?php if(!empty($d->lecciones)){?>
    <div id="accordion">
        <?php foreach($d->lecciones as $l){ ?>
            <div class="group">
                <h3><?php echo sprintf('%s %s', format_tipo_leccion($l->tipo), $l->titulo);  ?></h3>
                <div>
                    <?php echo empty($l->contenido) ? '<span class="text-muted">Sin Contenido</span>': $l->contenido; ?>
                    <div class="mt-3">
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm open_update_leccion_form" data-id="<?php echo $l->id; ?>"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm delete_leccion" data-id="<?php echo $l->id; ?>"><i class="fas fa-trash"></i></button>
                        </div>
                        <button class="btn btn-sm update_leccion_status <?php echo $l->estado==='pendiente' ? 'btn-warning text-dark': 'btn-success';?>" data-id="<?php echo $l->id; ?>"><i class="fas fa-check-square"></i> Lista</button>
                    </div>
                </div>
            </div>
        <?php }?>
    </div>
<?php }else{?>
    <div class="text-center py-5">
        <img src="<?php echo IMAGES.'yumi_empty.png'; ?>" alt="No hay lecciones" style="width: 120px;" class="img-fluis">
        <p class="text-muted">No hay lecciones disponibles</p>
    </div>
<?php }?>