
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['inadapte', 'passable', 'bien', 'tresbien', 'excellent', 'id' ];

    static values = { validMention: {
        mention: String, id: Number
        }}

    const
    inadapte() {


        this.validMentionValue.mention = 'inadapte';
        this.validMentionValue.id = this.idTarget.dataset.id;


    }

    passable() {

        this.validMentionValue.mention = 'passable';
        this.validMentionValue.id = this.idTarget.dataset.id;

    }

    bien() {

        this.validMentionValue.mention = 'bien';
        this.validMentionValue.id = this.idTarget.dataset.id;

    }

    tresbien() {

        this.validMentionValue.mention = 'tresbien';
        this.validMentionValue.id = this.idTarget.dataset.id;
    }

    excellent() {

        this.validMentionValue.mention = 'excellent';
        this.validMentionValue.id = this.idTarget.dataset.id;

    }


    test() {

        this.dispatch('click', {detail: {content: this.validMentionValue}})
    }


}