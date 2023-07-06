<?php require_once INCLUDES.'inc_header.php'; ?>
<?php require_once INCLUDES.'inc_navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-12 py-3">
            <h1 class="my-3 float-start">Temarios Creados</h1>
            <a href="temarios/agregar" class="btn btn-success float-end">Agregar Temario</a>
        </div>
        <div class="col-lg-12 col-12">
            <?php if (empty(($d->temarios->rows))) { ?>
                <div class="text-center py-5">
                    <img src="<?php echo IMAGES.'yumi_empty.png'; ?>" alt="No hay registros" style="width: 120px;" class="img-fluis">
                    <p class="text-muted">No hay temarios en la base de datos</p>
                </div>
            <?php }else{ ?> 
    
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Titulo</th>
                            <th>Estado</th>
                            <th>Creado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($d->temarios->rows as $t){ ?>
                            <tr>
                                <td><?php echo sprintf('<a href="temarios/ver/%s">%s</a>',$t->id, $t->numero);?></td>
                                <td><?php echo add_ellipsis($t->titulo, 50); ?></td>
                                <td><?php echo formatTemarioEstado($t->estado); ?></td>
                                <td><?php echo add_ellipsis($t->creado); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo sprintf('temarios/ver/%s', $t->id); ?>" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                        <a href="<?php echo buildURL(sprintf('temarios/borrar/%s', $t->id)); ?>" class="btn btn-danger btn-sm confirmar"><i class="fas fa-trash"></i></a>

                                    </div>
                                </td>

                            </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php echo $d->temarios->pagination; ?>
            <?php } ?>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>