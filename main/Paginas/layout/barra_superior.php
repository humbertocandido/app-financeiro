<nav class="navbar col-md-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="navbar-brand-wrapper d-flex justify-content-center">
    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
      <a class="navbar-brand brand-logo" href="<?= URLSISTEMA ?>/login/trocaClinica">
        <button disabled type="button" class="btn btn-primary">
          <?= $dados['empresa'] ?>
        </button>
      </a>
      <a class="navbar-brand brand-logo-mini" href="<?= URLSISTEMA ?>"><img src="<?= URLASSESTS ?>/images/favicon.png" alt="logo" /></a>
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-sort-variant"></span>
      </button>
    </div>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <ul class="navbar-nav mr-lg-4 w-100">
      <li class="nav-item nav-search d-none d-block w-100">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="search">
              <i class="mdi mdi-magnify"></i>
            </span>
          </div>
          <input type="text" class="form-control" id="busca_geral" placeholder="Buscar" aria-label="search" aria-describedby="search">
        </div>
      </li>
    </ul>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
          <img src="<?= URLASSESTS ?>/images/faces/odontoface.png" alt="profile" />
          <?php

          use ODC\Filtros\Autenticacao;

          if (isset($dados['usuario'])) : ?>
            <span class="nav-profile-name"> <?= strpos($dados['usuario']['nome'], ' ') > 0 ? strstr($dados['usuario']['nome'], ' ', true) : $dados['usuario']['nome'] ?></span>
          <?php endif ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="<?= URLSISTEMA ?>/login/logout">
            <i class="mdi mdi-logout text-primary"></i>
            Sair
          </a>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>