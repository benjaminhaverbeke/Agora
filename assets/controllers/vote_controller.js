
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['mentionUl', 'originalSelect'];


    initialize() {


        /***targeting vote form, changing inputs opacities***/

       const mentions =  this.mentionUlTarget.querySelectorAll('.mention-li');

        mentions.forEach((mention) => {

            let select = this.originalSelectTarget;

            mention.addEventListener('click', function() {

                select.value = mention.dataset.mention;
                select.setAttribute('value', mention.dataset.mention);
                mentions.forEach((mention)=> {

                    mention.style.opacity = '100%';

                    if(mention.dataset.mention !== select.value){

                        mention.style.opacity = '40%';
                    }

                })


            });
        })

    }

}