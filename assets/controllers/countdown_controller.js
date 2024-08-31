import {Controller} from '@hotwired/stimulus';


export default class extends Controller {

    static targets = ['count', 'id', 'message', 'time-container'];
    static values = {date: Number, timer: Number, message: String, type: String}

    async fetchDuration() {

        const id = this.idTarget.dataset.salonId;
        const r = await fetch(`/salon/get-duration/${id}`, {
            method: 'GET',
            headers: {
                "Accept": "application/json",
                "Content-type": "application/json"
            }
        })
        if (r.ok === true) {
            return r.json();

        } else {
            throw new Error('Impossible de contacter le serveur')
        }

    }

    time() {

        let now = new Date().getTime();

        if (now < this._date) {

            let diff = this._date - now;
            let totalhours = Math.floor(diff / (1000 * 60 * 60));
            let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((diff % (1000 * 60)) / 1000);

            let days = Math.floor(totalhours/24);
            let hours = totalhours % 24;

            days === 0 ? (this.countTarget.innerHTML = ` ${hours} h ${minutes} m ${seconds} s`)
                : (this.countTarget.innerHTML = ` ${days} j ${hours} h ${minutes} m ${seconds} s`)


        } else {
            return false
        }


    }

    connect() {

        this.fetchDuration().then(r => {

                this._date = new Date(r.time.date).getTime();
                this.messageTarget.innerHTML = r.time_message;
                this._type = r.type;
                this.idTarget.classList.add(r.type);


            }
        ).catch(e => {
                throw new Error('Impossible de contacter le serveur')

            }
        )


        if (this.time) {


            this._timer = setInterval(() => {
                this.time()
            }, 1000);
            this.time();
        } else if (this._type !== "results") {

            this.fetchDuration().then(r => {

                    this._date = new Date(r.time.date).getTime();
                    this.messageTarget.innerHTML = r.time_message
                    this._type = r.type;


                }
            ).catch(e => {
                    throw new Error('Impossible de contacter le serveur')

                }
            )


            this._timer = setInterval(() => {
                this.time()
            }, 1000);
            this.time();


        } else {
            clearInterval(this._timer)
            this.messageTarget.innerHTML = r.time_message;
        }


    }


}
// function fetchNewDuration(callback) {
//
//     // Récupérer l'élément HTML
//     const salonContainer = document.getElementById('salon-container');
//
//     // Récupérer l'ID du salon à partir de l'attribut
//     const salonId = salonContainer.dataset.salonId;
//
//     fetch(`/salon/get-duration/${salonId}`)
//         .then(response => response.json())
//         .then(data => {
//
//             callback(null, data.duration);
//
//
//         })
//         .catch(error => {
//             callback(error);
//         });
//
//// }
//
//
//
//
//
// async function countdown() {
//     try {
//
//        const duration = fetchNewDuration();
//
//     }
//         fetchNewDuration()
//             .then(duration => {
//                 // Ici, vous pouvez utiliser la valeur de duration pour votre compte à rebours
//                 // ... votre code de compte à rebours ...
//
//                 console.log(duration);
//                     let timeleft = convertStringToSeconds(duration.time);
//
//                     if (timeleft !== null) {
//
//                         const intervalId = setInterval(() => {
//                             const days = Math.floor(timeleft / 86400); // 86400 secondes dans une journée
//                             const hours = Math.floor((timeleft % 86400) / 3600);
//                             const minutes = Math.floor((timeleft % 3600) / 60);
//                             const remainingSeconds = timeleft % 60;
//
//                             countdownElement.textContent = `${hours}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')} (Jours restants: ${days})`;
//
//                             timeleft--;
//
//                             if (timeleft <= 0) {
//                                 clearInterval(intervalId);
//                                 fetchNewDuration();
//
//
//                             }
//
//                         }, 1000);
//
//                 }
//
//             })
//             .catch(error => {
//                 console.error('Erreur:', error);
//             });
//     }
//
//
// }
//
//
// document.addEventListener("DOMContentLoaded", function () {
//
//     countdown();
//
// });

// async function fetchNewDuration(salonId) {
//    const r = await fetch(`/salon/get-duration/${salonId}`)
//
//     if(r.ok === true) {
//
//             const data = await r.json();
//             return data.duration;
//         }
//         throw new Error('Impossible de contacter le serveur');
// }
//
//     function
//
//     convertStringToSeconds(timeString) {
//         // ... votre logique de conversion améliorée ...
//         // Par exemple, utiliser une regex pour extraire les heures, minutes et secondes
//
//         //Si pas de string, les votes sont clôturés, on affiche les résultats
//         if (!timeString) {
//
//             return null;
//         } else {
//
//             // Supprimer la partie "(Jours restants: JJ)" pour ne conserver que l'heure
//             const timePart = timeString.split(' (')[0];
//
//             // Séparer les heures, minutes et secondes
//             const parts = timePart.split(':');
//
//             const hours = parseInt(parts[0]);
//
//             const minutes = parseInt(parts[1]);
//
//             const seconds = parseInt(parts[2]);
//
//             // Calculer le nombre total de secondes
//             return hours * 3600 + minutes * 60 + seconds;
//         }
//
//
//     }
//
//     async function
//
//     fetchDuration(id) {
//
//         fetch(`/salon/get-duration/${id}`)
//             .then((response) => response.json())
//             .then((data) => {
//
//
//                 return data.duration;
//
//             })
//
//     }
//
//     function
//
//     countdown(duration) {
//
//
//         const message = document.getElementById('time_message');
//         const countdownElement = document.getElementById("countdown");
//
//
//         if (duration.time !== null) {
//
//             let _sec = 1000;
//             let _min = _sec * 60;
//             let _hour = _min * 60;
//             let _day = _hour * 24;
//             let timer;
//
//             let now = new Date();
//             let end = duration.time;
//             let distance = end - now;
//
//             if (distance < 0) {
//
//                 clearInterval(timer);
//                 countdown();
//
//             }
//
//             let days = Math.floor(distance / _day);
//             let hours = Math.floor((distance % _day) / _hour);
//             let minutes = Math.floor((distance % _hour) / _min);
//             let seconds = Math.floor((distance % _min) / _sec);
//
//             countdownElement.innerHTML = `Jours: ${days} ${hours}:${minutes}:${seconds}`
//
//         }
//         let timer = setInterval(countdown, 1000);
//     }
//
//
//     document
// .
//
//     addEventListener(
//
//     "DOMContentLoaded"
// ,
//
//     function() {
//         const id = document.getElementById('salon-container').dataset.salonId;
//         console.log(id);
//         let duration = fetchDuration(id)
//         // countdown(duration);
//         console.log(duration);
//
//
//     }
//
// )
//     ;
//
// }