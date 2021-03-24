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
                    <div class="col-lg-8 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            Recuperar Senha
                            <form class="pt-3" id="form_atualiza_senha" method="POST" action="<?= URLSISTEMA ?>/login/troca-senha">
                                <div class="form-group">
                                    <input type="hidden" name="id_recuperacao" value="<?= $dados['id_recupera'] ?>">
                                    <label for="">Nova senha</label>
                                    <input type="password" required class="form-control" name="senha" id="senha">
                                </div>
                                <div class="form-group">
                                    <label for="">Repita a senha</label>
                                    <input type="password" required class="form-control" id="nova_senha">
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">Recuperar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= URLASSESTS ?>/vendors/base/vendor.bundle.base.js"></script>
    <script src="<?= URLASSESTS ?>/js/off-canvas.js"></script>
    <script src="<?= URLASSESTS ?>/js/hoverable-collapse.js"></script>
    <script src="<?= URLASSESTS ?>/js/template.js"></script>
    <script>

    </script>
</body>

</html>