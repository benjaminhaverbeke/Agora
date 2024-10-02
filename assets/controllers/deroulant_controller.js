import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['arrow', 'ul'];
    static values = { isOpen: {type: Boolean, default: false}}

    animate() {

        this.isOpenValue ? this.hide() : this.show();
        this.isOpenValue = !this.isOpenValue;

    }

    hide() {
        this.ulTarget.classList.remove("active");
        this.arrowTarget.style.rotate= "0deg";
    }

    show() {
        this.ulTarget.classList.add("active");
        this.arrowTarget.style.rotate="180deg";
    }



}