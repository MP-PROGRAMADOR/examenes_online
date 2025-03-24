// script.js
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// script.js
document.addEventListener('DOMContentLoaded', function() {
    document.body.style.opacity = 0;

    window.addEventListener('load', function() {
        document.body.style.transition = 'opacity 1s ease-in-out';
        document.body.style.opacity = 1;
    });
});

// script.js  función agrega una animación sutil al botón "Comenzar Ahora" cuando el usuario pasa el ratón por encima.
document.addEventListener('DOMContentLoaded', function() {
    const comenzarBtn = document.querySelector('.btn-primary');

    comenzarBtn.addEventListener('mouseover', function() {
        this.style.transform = 'scale(1.05)';
        this.style.transition = 'transform 0.3s ease-in-out';
    });

    comenzarBtn.addEventListener('mouseout', function() {
        this.style.transform = 'scale(1)';
    });
});

// script.js función cambia el texto del jumbotron cada cierto tiempo para mantener el contenido dinámico.
document.addEventListener('DOMContentLoaded', function() {
    const jumbotronHeading = document.querySelector('.jumbotron h1');
    const jumbotronText = document.querySelector('.jumbotron p');

    const textos = [
        { heading: '¡Domina el Examen de Conducir!', text: 'Practica con nuestros exámenes y simulacros.' },
        { heading: 'Aprende a tu Propio Ritmo', text: 'Accede a material de estudio y exámenes en línea.' },
        { heading: 'Resultados al Instante', text: 'Obtén retroalimentación inmediata y mejora tu aprendizaje.' }
    ];

    let index = 0;

    setInterval(function() {
        jumbotronHeading.textContent = textos[index].heading;
        jumbotronText.textContent = textos[index].text;

        index = (index + 1) % textos.length;
    }, 5000); // Cambia el texto cada 5 segundos
});