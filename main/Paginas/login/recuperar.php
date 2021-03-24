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
                            Digite o e-mail para recuperação da senha.
                            <form class="pt-3" method="POST" action="<?= URLSISTEMA ?>/login/recuperar-senha">
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-lg" name="email" placeholder="E-mail" required>
                                    <?php if (isset($dados['erro'])) : ?>
                                        <small class="text-danger"><?= $dados['erro'] ?></small>
                                    <?php endif ?>
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