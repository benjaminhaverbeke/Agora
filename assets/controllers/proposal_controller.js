import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['description'];
    static values = { isOpen: {type: Boolean, default: false}}


    animate() {

        this.isOpenValue ? this.hide() : this.show();
        this.isOpenValue = !this.isOpenValue;

    }

    hide() {
        this.descriptionTarget.classList.remove("active");

    }

    show() {
        this.descriptionTarget.classList.add("active");
    }



}