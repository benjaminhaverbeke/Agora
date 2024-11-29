import { Controller } from "@hotwired/stimulus";
import { connectStreamSource, disconnectStreamSource } from "@hotwired/turbo";

export default class extends Controller {
    static values = { url: String };


    connect() {

        //abonnement au hub
        this.es = new EventSource(this.urlValue);
        connectStreamSource(this.es);
        this.es.onmessage = event => {
            // Will be called every time an update is published by the server
            console.log(JSON.parse(event.data));


        }
    }

    disconnect() {
        this.es.close();
        disconnectStreamSource(this.es);
    }
}