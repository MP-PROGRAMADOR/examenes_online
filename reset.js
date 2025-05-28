 
document.getElementById('reiniciar-examen').addEventListener('click', () => {
    const examenId = document.getElementById('reiniciar-examen').dataset.examenId;

    if (!confirm('¿Estás seguro de que deseas reiniciar este examen? Se perderán todas las respuestas.')) {
        return;
    }

    const formData = new FormData();
    formData.append('examen_id', examenId);

    fetch('reset.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Opcional: recargar la página o actualizar la UI
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        alert("Error inesperado: " + error.message);
    });
});
 
