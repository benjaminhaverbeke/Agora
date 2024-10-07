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
        let diff = this._date - now;
        if (diff > 0) {


            console.log(diff)

            let totalhours = Math.floor(diff / (1000 * 60 * 60));
            let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((diff % (1000 * 60)) / 1000);

            let days = Math.floor(totalhours/24);
            let hours = totalhours % 24;

            days === 0 ? (this.countTarget.innerHTML = ` ${hours} h ${minutes} m ${seconds} s`)
                : (this.countTarget.innerHTML = ` ${days} j ${hours} h ${minutes} m ${seconds} s`)


        }
        else if(diff === 0) {
clearInterval(this._timer);



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



        } else if (this._type !== "results") {

            this.fetchDuration().then(r => {

                    this._date = new Date(r.time.date).getTime();
                    this.messageTarget.innerHTML = r.time_message
                    this._type = r.type;


                }
            ).catch(() => {
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
