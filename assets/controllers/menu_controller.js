
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {


    static targets = ['list', 'burger', 'close', 'invit'];
    static values = { isOpen: {type: Boolean, default: false}}


    animate() {

    this.isOpenValue ? this.hide() : this.show();
    this.isOpenValue = !this.isOpenValue;

    }

    hide() {
        this.listTarget.classList.remove("active");

    }

    show() {
        this.listTarget.classList.add("active");
    }









}

