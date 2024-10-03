
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['mentionUl', 'originalSelect'];


    initialize() {



       const mentions =  this.mentionUlTarget.querySelectorAll('.mention-li');

        mentions.forEach((mention) => {

            let select = this.originalSelectTarget;

            mention.addEventListener('click', function() {


                select.value = mention.dataset.mention;

            });
        })

    }

    animate(){




    }


}