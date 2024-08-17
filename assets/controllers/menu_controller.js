
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {


    static targets = ['list', 'burger'];
    static values = { isOpen: {type: Boolean, default: false}}


    animate() {

    this.isOpenValue ? this.hide() : this.show();
    this.isOpenValue = !this.isOpenValue;

    }

    hide() {
        this.listTarget.style.display = "none";

    }

    show() {
        this.listTarget.style.display = "block";
    }








}

