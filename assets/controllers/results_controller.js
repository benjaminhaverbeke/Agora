import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['grid'];


    async fetchResults() {


        const id = document.querySelector('#time').dataset.salonId;
        const r = await fetch(`/salon/get-results/${id}`, {
            method: 'GET',
            headers: {
                "Accept": "application/json",
                "Content-type": "application/json"
            }
        })
        if (r.ok === true) {


            return r.json();

        } else {
            throw new Error('Impossible de contacter le serveur')
        }

    }

    initialize() {

        this.fetchResults().then((res) => {


            res.forEach((element) => {

                if (element.result.length === 0) {

                    return null;

                } else {

                    console.log(element.result)

                    const sujet = document.querySelector('#sujet_results_' + element.sujet);


                    element.result.forEach((result, index) => {

                        index++;

                        const grid = sujet.querySelector('#tour_' + index);


                        console.log(grid)
                        console.log(typeof result)
                        result.forEach((prop) => {


                            const proposal = grid.querySelector('.proposal_' + prop.proposalId);


                            const resultArray = Object.entries(prop.pourcent);


                            resultArray.forEach((element) => {
                                const vote = element[1];


                                const mention = proposal.querySelector('.' + element[0]);

                                mention.style.flexGrow = vote;


                            });


                        })


                    })

                    /*****AFFICHAGE DU GAGNANT******/


                    const nbtour = element.result.length;

                    const lasttour = element.result[nbtour - 1];

                    const winnerDisplay = sujet.querySelector('#winner');


                    if (lasttour.length > 1) {

                        winnerDisplay.textContent = "Egalité entre les propositions "

                        lasttour.forEach((prop) => {

                            winnerDisplay.textContent = winnerDisplay.textContent + "" + prop.proposalTitle;

                        });

                    } else {

                        winnerDisplay.textContent = "La proposition " + lasttour[0].proposalTitle + " a gagné !"
                    }


                }

            })


        })


        /***target grid******/
        // const grid = container.querySelector('.grid');


        // let toggle = container.classList.toggle("active");

        /***target rows******/
        // let rows = grid.querySelectorAll('.row');


        /***test de mettre une transition pour afficher les résultats*********/
        // rows.forEach((row)=> {
        //     row.classList.toggle('transition');
        //
        // });


    }


}