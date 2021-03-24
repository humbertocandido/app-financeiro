<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Odonto</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?= URLASSESTS ?>/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?= URLASSESTS ?>/vendors/base/vendor.bundle.base.css">
  <link rel="stylesheet" href="<?= URLASSESTS ?>/css/style.css">
  <link rel="shortcut icon" href="<?= URLASSESTS ?>/images/favicon.ico" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="<?= URLASSESTS ?>/images/logo2.png" alt="logo">
              </div>
              <h4>Selecione a clínica para começar.</h4>
              <form action="" id="frm_clinicas">
                <select class="form-control" name="banco" id="clinica">
                  <option value="">Selecione...</option>
                  <?php foreach ($dados['empresas'] as $key) : ?>
                    <option value="<?= $key['bd'] ?>"><?= $key['nome_fantasia'] ?></option>
                  <?php endforeach; ?>
                </select>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="<?= URLASSESTS ?>/vendors/base/vendor.bundle.base.js"></script>

  <script src="<?= URLASSESTS ?>/js/off-canvas.js"></script>
  <script src="<?= URLASSESTS ?>/js/hoverable-collapse.js"></script>
  <script src="<?= URLASSESTS ?>/js/template.js"></script>
  <script src="<?= URLASSESTS ?>/js/logout.js"></script>
  <script>
    $(() => {
      $("#clinica").change(e => {
        let select = $("#clinica").val()
        let dados = $("#frm_clinicas").serialize()
        if (select != "") {
          $.ajax({
            url: `${URLSISTEMA}/login/selecionaClinica`,
            dataType: 'json',
            method: 'POST',
            data: dados,
            success: data => {
              window.location.href = data
            },
            error: erro => {
              console.log(erro);
            }
          })
        }
      })
    })
  </script>
</body>

</html>