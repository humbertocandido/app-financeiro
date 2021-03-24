<?php
require_once __DIR__ . "/../layout/cabecalho.php";
?>
<!-- partial:partials/_navbar.html -->
<?php
require_once __DIR__ . "/../layout/barra_superior.php";
?>
<!-- partial -->
<div class="container-fluid page-body-wrapper">
  <!-- partial:partials/_sidebar.html -->
  <?php
  require_once __DIR__ . "/../layout/barra_lateral.php";
  ?>
  <!-- partial -->
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-md-12 stretch-card">
          <div class="card">
            <div class="card-body" id="container_impressao">
              <?php
              if (file_exists(__DIR__ . "/../" . $caminho . ".php")) {
                require_once __DIR__ . "/../" . $caminho . ".php";
              } else {
                throw new Exception("Página não criada", 1);
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <?php
    require_once __DIR__ . "/../layout/rodape.php";
    ?>
    <!--  -->
  </div>
  <!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<?php foreach ($scripts as $value) : ?>
  <script src="<?= $value ?>"></script>
<?php endforeach; ?>
</body>

</html>