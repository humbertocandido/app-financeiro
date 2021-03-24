<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Odonto</title>
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
              <h4>Novo aqui?</h4>
              <h6 class="font-weight-light">Registre-se e aguarde a aprovação do administrador para começar a utilizar o sistema.</h6>
              <form class="pt-3" method="POST" action="<?= URLSISTEMA ?>/login/cadastrar" id="formRegistrar">
                <div class="form-group">
                  <input type="text" required class="form-control form-control-lg" name="nome" placeholder="Nome">
                </div>
                <div class="form-group">
                  <input type="email" required class="form-control form-control-lg" id="email" name="email" placeholder="E-mail">
                  <small id="e_repetido" class="text-danger d-none">* E-mail já foi cadastrado</small>
                </div>
                <div class="form-group">
                  <input type="password" required min="6" id="senha" class="form-control form-control-lg" name="senha" placeholder="Senha">
                </div>
                <div class="form-group">
                  <input type="password" required id="confirmar" class="form-control form-control-lg" placeholder="Confirmar senha">
                  <small id="erroSenha" class="text-danger d-none">* As senhas são diferentes</small>
                  <input type="hidden" id="existe" value="1">
                </div>
                <div class="mt-3">
                  <input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" value="Registrar">
                </div>
                <div class="mt-3 text-center">
                  Já tem uma conta ? <a href="<?= URLSISTEMA ?>/login">Logar</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="m_sucesso" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Cadastrado com sucesso</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?php if (isset($dados['usuario'])) : ?>
            <strong><?= $dados['usuario']['nome'] ?></strong>
            <p>
              Seu cadastro foi realizado com sucesso.
              <br>
              seja bem vindo ao sistema OdontoCompany.
              <br>
              Agora é só aguardar a liberação do administrador para fazer seu o acesso.
              <br>
            </p>
            <a class="" href="<?= URLSISTEMA ?>/login">Página de login</a>
          <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script src="<?= URLASSESTS ?>/vendors/base/vendor.bundle.base.js"></script>
  <script src="<?= URLASSESTS ?>/js/off-canvas.js"></script>
  <script src="<?= URLASSESTS ?>/js/hoverable-collapse.js"></script>
  <script src="<?= URLASSESTS ?>/js/template.js"></script>
  <script src="<?= URLASSESTS ?>/js/logout.js"></script>
  <script>
    $((e) => {
      $('#formRegistrar').submit((e) => {
        let senha = $('#senha').val()
        let confirmar = $('#confirmar').val()
        let existe = $("#existe").val()

        if (existe == 1) {
          alert("Já existe um usuário com esse e-mail.")
          e.preventDefault()
        }

        if (senha !== confirmar) {
          $('#erroSenha').removeClass('d-none')
          setTimeout(() => {
            $('#erroSenha').addClass('d-none')
          }, 3000);
          e.preventDefault()
        }
      })

      $("#email").blur(e => {
        email = $("#email").val()
        $.ajax({
          url: `${URLSISTEMA}/login/checaEmail`,
          method: 'POST',
          dataType: 'json',
          data: {
            email
          },
          success: data => {
            if (data) {
              $("#e_repetido").removeClass('d-none')
              $("#existe").val(1)
            } else {
              $("#e_repetido").addClass('d-none')
              $("#existe").val(0)
            }
          },
          error: erro => {
            console.log(erro);
          }
        })
      })

    })
  </script>
  <?php if (isset($dados['cadastrado'])) : ?>
    <script>
      $("#m_sucesso").modal('show')
    </script>
  <?php endif; ?>
</body>

</html>