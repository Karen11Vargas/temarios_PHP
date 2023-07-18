<?php if(!empty($d->lecciones)){?>
    <div id="accordion">
        <?php foreach($d->lecciones as $l){ ?>
            <div class="group">
                <h3><?php echo sprintf('%s %s', format_tipo_leccion($l->tipo), $l->titulo);  ?></h3>
                <div>
                    <?php echo empty($l->contenido) ? '<span class="text-muted">Sin Contenido</span>': $l->contenido; ?>
                    <div class="mt-3">
                        <div class="btn-group">
                            <button class="btn btn-danger btn-sm delete_leccion" data-id="<?php echo $l->id; ?>"><i class="fas fa-trash"></i></button>
                        </div>
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