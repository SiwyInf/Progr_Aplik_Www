/**
 * Pobiera bieżącą datę i aktualizuje element na stronie.
 */
function getTheDate() {
    const today = new Date();
    const formattedDate = 
        (today.getMonth() + 1) + "/" + 
        today.getDate() + "/" + 
        (today.getFullYear() % 100); // Format MM/DD/YY

    document.getElementById("data").innerHTML = formattedDate;
}

// Zmienne kontrolujące działanie zegara
let timerID = null;
let timerRunning = false;

/**
 * Zatrzymuje zegar, jeśli działa.
 */
function stopClock() {
    if (timerRunning) {
        clearTimeout(timerID);
        timerRunning = false;
    }
}

/**
 * Uruchamia zegar i aktualizuje datę.
 */
function startClock() {
    stopClock(); // Zatrzymaj poprzedni zegar (jeśli istnieje)
    getTheDate(); // Aktualizuj datę
    showTime(); // Uruchom zegar
}

/**
 * Wyświetla bieżący czas w formacie 12-godzinnym i aktualizuje go co sekundę.
 */
function showTime() {
    const now = new Date();
    let hours = now.getHours();
    const minutes = now.getMinutes();
    const seconds = now.getSeconds();

    // Formatuj czas w systemie 12-godzinnym
    const ampm = hours >= 12 ? "P.M." : "A.M.";
    hours = (hours > 12) ? hours - 12 : (hours === 0 ? 12 : hours); // Konwersja 24h na 12h
    const formattedTime = 
        hours + 
        (minutes < 10 ? ":0" : ":") + minutes + 
        (seconds < 10 ? ":0" : ":") + seconds + 
        " " + ampm;

    document.getElementById("zegarek").innerHTML = formattedTime;

    // Ustawienie funkcji showTime na ponowne wywołanie po 1 sekundzie
    timerID = setTimeout(showTime, 1000);
    timerRunning = true;
}
