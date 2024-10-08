import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['grid', 'id'];


    async fetchResults() {


        const id = this.idTarget.dataset.id;


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

        this.fetchResults().then((result) => {


            if (result[0].length === 0) {

                return null;

            } else {

                /***le résultat contient tous les tours***/
                result.forEach((tour, index) => {

                    /***incrémentation du tour***/
                    index++;

                    /***recupère la grille pour afficher les résultats du tour***/
                    const grid = this.idTarget.querySelector('.tour_' + index);


                    tour.forEach((prop) => {


                        /***Pour selection la ligne correspondante dans la grille pour chaque proposition***/
                        const proposal = grid.querySelector('.proposal_' + prop.proposalId);

                        /***on récupère les pourcentages de la proposition***/
                        const pourcent = Object.entries(prop.pourcent);

                        /***les porucentage sont injectés grâce aux classes portant le nom de mentions***/
                        pourcent.forEach((mention) => {

                            const vote = mention[1];

                            const mentionDom = proposal.querySelector('.' + mention[0]);

                            mentionDom.style.flexGrow = vote;

                        });

                    })


                    /*****AFFICHAGE DU GAGNANT******/


                    const lasttour = result[result.length - 1];


                         const winnerDisplay = this.idTarget.querySelector('.winner');

                         /***on vérifie le dernier tour, si il reste plusieurs propositions, alors elles sont à égalité***/

                        if (lasttour.length > 1) {


                            winnerDisplay.textContent = "Egalité entre les propositions "

                            lasttour.forEach((prop, index) => {

                                if(index+1 !== lasttour.length){

                                    winnerDisplay.textContent = winnerDisplay.textContent + " " + prop.proposalTitle + " &";

                                }
                                else {
                                    winnerDisplay.textContent = winnerDisplay.textContent + " " + prop.proposalTitle;

                                }


                            });

                        } else {

                            winnerDisplay.textContent = "La proposition " + lasttour[0].proposalTitle + " a gagné !"
                        }


                })
            }


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