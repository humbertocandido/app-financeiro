var URLSISTEMA = '/app-financeiro'

function deslogar(error) {
    if (error.status === 401) {
        window.location.href = error.responseJSON.redireciona
    }
}

function validarPaciente(id) {
    $.getJSON(`${URLSISTEMA}/paciente/validar-dados/${id}`, data => {
        if (data.atualizar) {
            atualizarPaciente(id)
        }
    })
}

function atualizarPaciente() {
    window.open(`${URLSISTEMA}/paciente/cadastro/${id}`)
}