const btn = document.getElementById('btn-assinar')
const containerAssinaruta = document.getElementById('assinatura')
const ass_base64 = document.getElementById('ass_code_64')
const ftPerfil = document.getElementById('base64_perfil')
const ftAssinatura = document.getElementById('base64_assinatura')
let video = null
let isFrontal = true

btn.addEventListener('click', e => {
    abrirAssinatura()
})

function abrirAssinatura() {
    divAssinatura = criarCanvas()
    divAssinatura.style.width = '99vw'
    divAssinatura.style.height = '89vh'
    divAssinatura.style.position = 'fixed'
    divAssinatura.style.top = '9vh'
    divAssinatura.style.left = '1px'

    containerAssinaruta.append(divAssinatura)

    $(".assinatura").signature({
        guideline: true
    })

    $("#assinatura").removeClass('d-none')
}

function criarCanvas() {
    const divVelha = document.querySelector('.assinatura')
    const divNova = document.createElement('div')
    divNova.classList.add('assinatura')
    if (divVelha) {
        $('.assinatura').signature('destroy');
        divVelha.remove()
    }
    return divNova
}

$("#limpar").click(e => {
    $(".assinatura").signature('clear')
})

$("#btn-foto").click(e => {
    $("#selfie").removeClass('d-none')
    loadCamera(true)
})

$("#salvar").click(e => {
    const img_base64_assinatura = $('.assinatura').signature('toDataURL', 'image/png')
    mostrarAssinatura(img_base64_assinatura)
    ass_base64.value = img_base64_assinatura
    ftAssinatura.value = img_base64_assinatura
    $("#assinatura").addClass('d-none')
    verificaDados()
})

function mostrarAssinatura(codigo) {
    const img_assinatura = document.createElement('img')
    img_assinatura.src = codigo
    img_assinatura.style.height = '180px'
    img_assinatura.style.width = '60vw'
    img_assinatura.id = 'assinatura_img'
    img_assinatura.classList.add('img-fluid')
    if ($("#assinatura_img")) {
        $("#assinatura_img").remove()
    }
    $("#container_img").append(img_assinatura)
    $("#container_img").removeClass('d-none')
}

window.addEventListener('resize', e => {
    if (screen.width >= screen.height) {
        if (ass_base64.value) return
        abrirAssinatura()
    }
}, false)

$(".btn-fechar").click(e => {
    e.preventDefault()
    fechaModalSelfie()
    $("#assinatura").addClass('d-none')
})

$(".btn-alterar-camera").click(e => {
    pararCamera()
    loadCamera(isFrontal = !isFrontal)
})

function loadCamera(isFrontal) {

    camera = isFrontal ? 'user' : 'environment'
    //Captura elemento de vídeo
    video = document.querySelector("#captura_foto");
    //As opções abaixo são necessárias para o funcionamento correto no iOS
    video.setAttribute('autoplay', '');
    video.setAttribute('muted', '');
    video.setAttribute('playsinline', '');
    //--

    //Verifica se o navegador pode capturar mídia
    if (navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
                audio: false,
                video: {
                    facingMode: camera
                }
            })
            .then(function (stream) {
                //Definir o elemento vídeo a carregar o capturado pela webcam
                video.srcObject = stream;
            })
            .catch(function (error) {
                alert("Oooopps... Falhou :'(");
            });
    }
}

function takeSnapShot() {
    //Criando um canvas que vai guardar a imagem temporariamente
    var canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    var ctx = canvas.getContext('2d');

    //Desenhando e convertendo as dimensões
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    //Criando o JPG
    var dataURI = canvas.toDataURL('image/jpg');
    document.querySelector(".perfil").src = dataURI;

    ftPerfil.value = dataURI
    fechaModalSelfie()
    pararCamera()
}

$(".btn-foto").click(function (e) {
    e.preventDefault();
    takeSnapShot()
    verificaDados()
});

function pararCamera() {
    video.srcObject.getTracks().forEach((track) => {
        track.stop();
    });
}

function fechaModalSelfie() {
    $("#selfie").addClass('d-none')
}

function verificaDados() {
    if (ftPerfil.value && ftAssinatura.value) {
        $("#container_email").removeClass('d-none')
    }
}

$("#enviar").click(e => {
    e.preventDefault()
    $("#salvando").removeClass('d-none')
    $.ajax({
        type: "POST",
        url: `${URLSISTEMA}/assinaturas/salvar-contrato`,
        data: {
            ftPerfil: $("#base64_perfil").val(),
            ftAssinatura: $("#base64_assinatura").val(),
            idAssinatura: $("#id_assinatura").val(),
            email: $("#txt_email").val()
        },
        dataType: "json",
        success: data => {
            $("#salvando").addClass('d-none')
            alertaSucesso(data)
        },
        error: erro => {
            if (erro.erro = 'email') {
                exibeSpanEmail()
            }
            console.log(erro);
        }
    });
})

function exibeSpanEmail() {
    $("#assinatura_erro_email").removeClass('d-none')
    $("#assinatura_erro_email").addClass('d-block')
}

function alertaSucesso(data) {
    Swal.fire(
        'Obrigado!',
        'Assinatura salva com sucesso',
        'success'
    )
    setTimeout(() => {
        window.close()
        console.log(data);
    }, 3000);
}