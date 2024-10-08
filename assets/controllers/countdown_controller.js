import {Controller} from '@hotwired/stimulus';


export default class extends Controller {

    static targets = ['count', 'id', 'message', 'time-container'];
    static values = {
        date: Number, timer: Number, message: String, type: {type: String, default: ""},
        diff: Number
    }

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
        this._diff = this._date - now;

            let totalhours = Math.floor(this._diff / (1000 * 60 * 60));
            let minutes = Math.floor((this._diff % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((this._diff % (1000 * 60)) / 1000);

            let days = Math.floor(totalhours / 24);
            let hours = totalhours % 24;

            days === 0 ? (this.countTarget.innerHTML = ` ${hours} h ${minutes} m ${seconds} s`)
                : (this.countTarget.innerHTML = ` ${days} j ${hours} h ${minutes} m ${seconds} s`)


    }

    connect() {

        this.fetchDuration().then((r) => {

                this._date = new Date(r.time.date).getTime();

                this.messageTarget.innerHTML = r.time_message;
                this._type = r.type;
                this.idTarget.classList.add(r.type);

                if (this._type === 'campagne' || this._type === 'vote') {


                    this._timer = setInterval(() => {

                        this.time()

                        if (this._diff <= 0) {
                            clearInterval(this._timer);
                            location.reload();
                        }

                    }, 1000);

                }

            }
        ).catch(() => {
                throw new Error('Impossible de contacter le serveur')

            }
        );


    }


}
