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
              <h4>Olá! Vamos começar.</h4>
              <h6 class="font-weight-light">Logue para continuar.</h6>
              <form class="pt-3" method="POST" action="<?= URLSISTEMA ?>/login/logar">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" required name="email" placeholder="E-mail">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" required name="senha" placeholder="Senha">
                </div>
                <?php if (!empty($dados) and isset($dados['erro'])) : ?>
                  <div class="alert alert-danger" role="alert">
                    Usuário e/ou senha inválidos!
                  </div>
                <?php endif ?>
                <div class="mt-3">
                  <input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" name="metodo" value="Logar">
                </div>
                <div class="row d-flex justify-content-between mt-2">
                  <div class="">
                    <a href="<?= URLSISTEMA ?>/login/lembrar-senha">Esqueceu a senha?</a>
                  </div>
                  <div class="">
                    <a href="<?= URLSISTEMA ?>/login/registro">Registre-se</a>
                  </div>
                </div>
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

  <!-- Modal aviso de cadastro com sucesso -->
  <div class="modal" tabindex="-1" id="modalSucesso" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title text-light">Cadastrado</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>
            Seu cadastro foi realizado com sucesso! <br>
            Aguarde o administrador ativar para acessar o sistema!
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Fim do modal de aviso de cadastro com sucesso -->

  <!-- plugins:js -->
  <script src="<?= URLASSESTS ?>/vendors/base/vendor.bundle.base.js"></script>

  <script src="<?= URLASSESTS ?>/js/off-canvas.js"></script>
  <script src="<?= URLASSESTS ?>/js/hoverable-collapse.js"></script>
  <script src="<?= URLASSESTS ?>/js/template.js"></script>
  <script>
    <?php if (isset($_GET['salvo']) && $_GET['salvo'] == 'sucesso') : ?>
      $('#modalSucesso').modal('show')
      setTimeout(() => {
        $('#modalSucesso').modal('hide')
      }, 10000);
    <?php endif ?>
  </script>
</body>

</html>