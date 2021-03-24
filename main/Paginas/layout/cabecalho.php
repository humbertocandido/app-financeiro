<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Odonto</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?= URLASSESTS ?>/css/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?= URLASSESTS ?>/css/base/vendor.bundle.base.css">
  <link rel="stylesheet" href="<?= URLASSESTS ?>/css/jquery-ui.min.css">

  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="<?= URLASSESTS ?>/css/style.css">
  <link rel="shortcut icon" href="<?= URLASSESTS ?>/images/favicon.ico" />

  <?php foreach ($css as $caminhoCss) : ?>
    <link rel="stylesheet" href="<?= $caminhoCss ?>">
  <?php endforeach; ?>

  <style>
    .ui-front {
      z-index: 1051;
    }

    .ui-autocomplete {
      max-height: 150px;
      overflow-y: auto;
      /* prevent horizontal scrollbar */
      overflow-x: hidden;
    }

    /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
    * html .ui-autocomplete {
      height: 150px;
    }

    .main-panel .content-wrapper {
      padding: 0.5rem 0.5rem !important;
    }

    .tb_agenda {
      max-height: 400px !important;
    }
  </style>
</head>

<body>
  <div class="container-scroller">