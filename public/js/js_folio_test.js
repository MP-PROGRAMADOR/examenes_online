

const timerElement = document.getElementById('timer').querySelector('span');
const questionsRemainingElement = document.getElementById('questions-remaining').querySelector('span');
const questionElement = document.getElementById('question-text');
const submitButton = document.getElementById('submit-button');
let timeLeft = 600; // 10 minutos en segundos
let questionsRemaining = 2; // Cantidad total de preguntas

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    timeLeft--;
    if (timeLeft < 0) {
        clearInterval(timerInterval);
        alert('Tiempo agotado.');
    }
}

function updateQuestionsRemaining() {
    questionsRemainingElement.textContent = questionsRemaining;
}

const timerInterval = setInterval(updateTimer, 1000);
updateQuestionsRemaining();

submitButton.addEventListener('click', () => {
    // Lógica para evaluar la respuesta aquí
    questionsRemaining--;
    updateQuestionsRemaining();
    if (questionsRemaining === 0) {
        alert('Test completado.');
        clearInterval(timerInterval);
    } else {
        // Lógica para cargar la siguiente pregunta aquí
    }
});