
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['mentionUl', 'originalSelect', 'title'];


    initialize() {



       const mentions =  this.mentionUlTarget.querySelectorAll('.mention-li');

        mentions.forEach((mention) => {

            let select = this.originalSelectTarget;

            mention.addEventListener('click', function() {

                select.value = mention.dataset.mention;
                console.log(select.value)
                mentions.forEach((mention)=> {

                    mention.style.opacity = '100%';

                    if(mention.dataset.mention !== select.value){

                        mention.style.opacity = '40%';
                    }

                })

                // if(select.value === 'inadapte'){
                //
                //
                //     title.style.backgroundColor = 'red';
                //
                // }
                // else if(select.value ==='passable')
                // {
                //     title.style.backgroundColor = 'brown';
                //
                // }
                //
                // else if(select.value ==='bien')
                //     {
                //
                //         title.style.backgroundColor = 'yellow';
                //
                //     }
                // else if(select.value === 'tresbien')
                // {
                //     title.style.backgroundColor = 'orange';
                //
                //
                // }
                // else if(select.value ===" excellent"){
                //
                //     title.style.backgroundColor = 'green';
                //
                // }



            });
        })

    }

    animate(){




    }


}