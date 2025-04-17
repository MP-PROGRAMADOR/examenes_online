// assets/js/modal_alerta.js

window.onload = function () {
    const alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
        keyboard: false,
        backdrop: 'static'
    });

    // Mostrar el modal si hay alerta desde PHP
    if (document.body.dataset.alerta === "true") {
        alertModal.show();
        setTimeout(function () {
            alertModal.hide();
        }, 10000); // Oculta el modal a los 10 segundos
    }
};
