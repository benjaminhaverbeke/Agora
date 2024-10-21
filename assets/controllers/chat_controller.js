
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['close', 'aside', 'open', 'messages', 'input'];
    static values = { isOpen: {type: Boolean, default: false}}
    animate() {

        this.isOpenValue ? this.hide() : this.show();
        this.isOpenValue = !this.isOpenValue;

    }

    hide() {
        this.asideTarget.classList.remove("is-chatting");

    }

    show() {
        this.asideTarget.classList.add("is-chatting");
        this.inputTarget.focus();

    }



    clear(){

        this.inputTarget.value = "";
        console.log('test');

    }

    // connect() {
    //
    //     this.messagesTarget.scrollTop = this.messagesTarget.scrollHeight;
    //
    //
    // }
    //
    // scrolldown() {
    //
    //     this.messagesTarget.scrollTop = +this.messagesTarget.scrollHeight;
    //
    // }



}