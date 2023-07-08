<?php require_once INCLUDES.'inc_header.php'; ?>
<?php require_once INCLUDES.'inc_navbar.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-12 py-3">
            <h1 class="my-3 float-start"><?php echo $d->title; ?></h1>
            <a href="home" class="btn btn-danger float-end">Regresar</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">Detalles del Temario</div>
                <div class="card-body">
                    <form id="temario_form">
                        <input type="hidden" name="id" value="<?php echo $d->t->id; ?>" required>
                        <?php echo insert_inputs();?>

                        <div class="mb-3">
                            <label for="titulo">Titulo</label>
                            <input type="text" class="form-control" name="titulo" id="titulo" value="<?php echo $d->t->titulo; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="descripcion">Descripcion</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" cols="5" rows="3"><?php echo $d->t->descripcion; ?></textarea>
                        </div>

                        <button class="btn btn-success" type="submit">Guardar Cambios</button>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Agregar Leccion</div>
                <div class="card-body">
                    <form id="add_leccion_form">
                        <input type="hidden" name="id_temario" value="<?php echo $d->t->id; ?>" required>
                        <?php echo insert_inputs();?>

                        <div class="mb-3">
                            <label for="l_titulo">Titulo</label>
                            <input type="text" class="form-control" name="titulo" id="l_titulo" required>
                        </div>

                        <div class="mb-3">
                            <label for="l_tipo">Tipo de Leccion</label>
                            <select name="tipo" id="l_tipo" class="form-select">
                                <?php foreach(get_tipo_lecciones() as $tipo): ?>
                                    <?php echo sprintf('<option value="%s">%s</option>', $tipo[0], $tipo[1]); ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="l_contenido">Contenido</label>
                            <textarea class="form-control" name="contenido" id="l_contenido" cols="5" rows="3"></textarea>
                        </div>

                        <button class="btn btn-success" type="submit">Agregar</button>
                    </form>
                </div>
            </div>

            <div class="card mb-3" style="display : none;">
                <div class="card-header">Actualizar Leccion</div>
                <div class="card-body">
                    <form id="update_leccion_form">
                        <input type="hidden" name="id" value="" required>

                        <div class="mb-3">
                            <label for="ul_titulo">Titulo</label>
                            <input type="text" class="form-control" name="titulo" id="ul_titulo" required>
                        </div>

                        <div class="mb-3">
                            <label for="ul_tipo">Tipo de Leccion</label>
                            <select name="tipo" id="ul_tipo" class="form-select">
                                <?php foreach(get_tipo_lecciones() as $tipo): ?>
                                    <?php echo sprintf('<option value="%s">%s</option>', $tipo[0], $tipo[1]); ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="ul_contenido">Contenido</label>
                            <textarea class="form-control" name="contenido" id="ul_contenido" cols="5" rows="3"></textarea>
                        </div>

                        <button class="btn btn-success" type="submit">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="wrapper_lecciones" data-id="<?php echo $d->t->id; ?>">
              <!-- Se carga con AJAX -->
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>