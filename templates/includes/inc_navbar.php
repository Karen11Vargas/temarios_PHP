<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
      <a href="<?php echo URL;?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <span class="fs-4"><?php echo get_sitename(); ?></span>
      </a>

      <ul class="nav nav-pills">
        <li class="nav-item"><a href="#" class="nav-link active" aria-current="page">Inicio</a></li>
        <?php if (!Auth::validate()){ ?>
            <li class="nav-item"><a href="login" class="nav-link active" aria-current="page">Ingresar</a></li>

        <?php }else{ ?>
            <li class="nav-item"><a href="logout" class="nav-link " aria-current="page">Salir</a></li>

        <?php } ?>

    </header>
  </div>