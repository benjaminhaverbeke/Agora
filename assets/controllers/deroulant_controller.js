import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['arrow', 'ul', 'loupe', 'sujetDescript', 'votedUl'];
    static values = {
        isViewing: {type: Boolean, default: false},
        isOpen: {type: Boolean, default: false},
        votedUl: {type: Boolean, default: false}
    }

    animate() {

        this.isOpenValue ? this.hide() : this.show();
        this.isOpenValue = !this.isOpenValue;

    }

    hide() {
        this.ulTarget.classList.remove("active");
        this.arrowTarget.style.rotate = "180deg";
    }

    show() {
        this.ulTarget.classList.add("active");
        this.arrowTarget.style.rotate = "0deg";
    }


    animDesSujet() {

        this.isViewingValue ? this.close() : this.open();
        this.isViewingValue = !this.isViewingValue;

    }

    open() {

        this.sujetDescriptTarget.classList.add("active");


    }

    close() {

        this.sujetDescriptTarget.classList.remove('active');
    }

    animVoted() {

        this.votedUlValue ? this.closeVoted() : this.openVoted();
        this.votedUlValue = !this.votedUlValue;

    }

    openVoted() {

        this.votedUlTarget.classList.add("active");
        this.arrowTarget.style.rotate = "180deg";


    }

    closeVoted() {

        this.votedUlTarget.classList.remove('active');
        this.arrowTarget.style.rotate = "0deg";

    }


}