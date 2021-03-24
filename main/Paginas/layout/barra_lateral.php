<?php

use ODC\Filtros\Autenticacao;
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item menu">
      <a class="nav-link" href="<?= URLSISTEMA ?>" id="principal">
        <i class="mdi mdi-home text-primary menu-icon"></i>
        <span class="menu-title">Principal</span>
      </a>
    </li>
    <li class="nav-item menu" id="paciente">
      <a class="nav-link" data-toggle="collapse" href="#pacientes" aria-expanded="false" aria-controls="ui-basic">
        <i class="mdi mdi-account-card-details text-primary menu-icon"></i>
        <span class="menu-title">Testes</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="pacientes">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="<?= URLSISTEMA ?>/cadastro-teste/cadastro">
              Exibir saída em método
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= URLSISTEMA ?>/cadastro-teste/mostrar-pagina-em-layout">
              Página dentro do layout
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= URLSISTEMA ?>/cadastro-teste/mostrar-pagina-fora-layout">
              Página fora do layout
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= URLSISTEMA ?>/cadastro-teste/retorna-json">
              Recebendo um json
            </a>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</nav>