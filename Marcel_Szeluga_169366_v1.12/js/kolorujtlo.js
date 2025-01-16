// Flagi kontrolujące obliczenia i liczby dziesiętne
var computed = false;
var decimal = 0;

/**
 * Przelicza wartość między różnymi jednostkami.
 * @param {HTMLFormElement} entryform - Formularz zawierający dane wejściowe.
 * @param {HTMLSelectElement} from - Lista rozwijana z jednostką wejściową.
 * @param {HTMLSelectElement} to - Lista rozwijana z jednostką docelową.
 */
function convert(entryform, from, to) {
    const convertFromIndex = from.selectedIndex; // Wybrana jednostka wejściowa
    const convertToIndex = to.selectedIndex; // Wybrana jednostka wyjściowa

    // Obliczenie wyniku i przypisanie go do pola wyjściowego
    entryform.display.value = 
        (entryform.input.value * from[convertFromIndex].value) / to[convertToIndex].value;
}

/**
 * Dodaje znak do pola wejściowego i wykonuje przeliczenie jednostek.
 * @param {HTMLInputElement} input - Pole wejściowe.
 * @param {string} character - Znak do dodania.
 */
function addChar(input, character) {
    if ((character === "." && decimal === 0) || character !== ".") {
        if (input.value === "" || input.value === "0") {
            input.value = character; // Zastąpienie początkowej wartości nowym znakiem
        } else {
            input.value += character; // Dodanie nowego znaku do istniejącej wartości
        }

        // Automatyczne przeliczenie jednostek
        convert(input.form, input.form.measure1, input.form.measure2);

        computed = true; // Ustawienie flagi, że obliczenia zostały wykonane
        if (character === ".") {
            decimal = 1; // Oznaczenie użycia liczby dziesiętnej
        }
    }
}

/**
 * Otwiera nowe okno przeglądarki.
 */
function openVothcom() {
    window.open("", "display window", "toolbar=no,directories=no,menubar=no");
}

/**
 * Czyści pola formularza i resetuje flagi.
 * @param {HTMLFormElement} form - Formularz do wyczyszczenia.
 */
function clear(form) {
    form.input.value = 0; // Resetowanie pola wejściowego
    form.display.value = 0; // Resetowanie pola wyjściowego
    decimal = 0; // Resetowanie flagi dziesiętnej
}

/**
 * Zmienia kolor tła strony.
 * @param {string} hexNumber - Kolor w formacie heksadecymalnym.
 */
function changeBackground(hexNumber) {
    document.body.style.backgroundColor = hexNumber; // Ustawienie nowego koloru tła
}
