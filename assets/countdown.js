function fetchNewDuration() {

    // Récupérer l'élément HTML
    const salonContainer = document.getElementById('salon-container');

    // Récupérer l'ID du salon à partir de l'attribut
    const salonId = salonContainer.dataset.salonId;

    let newDuration = null;
    fetch(`/salon/get-duration/${salonId}`)
        .then(response => response.json())
        .then(data => {
            console.log(newDuration);
            newDuration = data.duration;

        })
        .catch(error => {
            console.error('Erreur lors de la récupération de la nouvelle durée:', error);
        });

        return newDuration;
}


function convertStringToSeconds(time) {

    //Si pas de string, les votes sont clôturés, on affiche les résultats
    if (time.innerText === "") {

        return null;
    } else {

        const str = time.innerText;

        // Supprimer la partie "(Jours restants: JJ)" pour ne conserver que l'heure
        const timePart = str.split(' (')[0];

        // Séparer les heures, minutes et secondes
        const parts = timePart.split(':');

        const hours = parseInt(parts[0]);

        const minutes = parseInt(parts[1]);

        const seconds = parseInt(parts[2]);

        // Calculer le nombre total de secondes
        return hours * 3600 + minutes * 60 + seconds;
    }

}


function countdown() {

    const countdownElement = document.getElementById("countdown");

    const newDuration = fetchNewDuration();
    console.log(newDuration);
    let timeleft = convertStringToSeconds(newDuration.time);

    if (timeleft !== null) {

        const intervalId = setInterval(() => {
            const days = Math.floor(timeleft / 86400); // 86400 secondes dans une journée
            const hours = Math.floor((timeleft % 86400) / 3600);
            const minutes = Math.floor((timeleft % 3600) / 60);
            const remainingSeconds = timeleft % 60;

            countdownElement.textContent = `${hours}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')} (Jours restants: ${days})`;

            timeleft--;

            if (timeleft <= 0) {
                clearInterval(intervalId);
                fetchNewDuration();


            }

        }, 1000);
    }


}


document.addEventListener("DOMContentLoaded", function () {



    countdown();


});