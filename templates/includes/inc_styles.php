<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

<!-- Todo plugin debe ir debajo de está línea -->
<!-- Toastr css -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Waitme css -->
<link rel="stylesheet" href="<?php echo PLUGINS.'waitme/waitMe.min.css'; ?>">

<!-- Lightbox -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"/>

<!--Jquery UI -->
<link rel="stylesheet" href="<?php echo CSS.'jquery.ui.base.css';?>">

<!-- Estilos registrados manualmente -->
<?php echo load_styles(); ?>

<!-- Estilos personalizados deben ir en main.css o abajo de esta línea -->
<link rel="stylesheet" href="<?php echo CSS.'main.css?v='.get_version(); ?>">