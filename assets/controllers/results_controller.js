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

                /***response contains all rounds***/

                result.forEach((tour, index) => {

                    /***incrementing counter***/
                    index++;

                    /***get grid to display round results***/

                    const grid = this.idTarget.querySelector('.tour_' + index);

                    tour.forEach((prop) => {

                        /***to select grid row for each proposal***/

                        const proposal = grid.querySelector('.proposal_' + prop.proposalId);

                        /***getting proposal percent***/

                        const pourcent = Object.entries(prop.pourcent);

                        /***percents injected by classes with same mention names***/

                        pourcent.forEach((mention) => {

                            const vote = mention[1];
                            const mentionDom = proposal.querySelector('.' + mention[0]);
                            mentionDom.style.flexGrow = vote;
                        });
                    })

                    /**********WINNER DISPLAY***********/

                    const lasttour = result[result.length - 1];

                    const winnerDisplay = this.idTarget.querySelector('.winner');

                    /***checking last tour, if more than one proposal, its equality***/

                    if (lasttour.length > 1) {


                        winnerDisplay.textContent = "Egalité entre les propositions "

                        lasttour.forEach((prop, index) => {

                            if (index + 1 !== lasttour.length) {

                                winnerDisplay.textContent = winnerDisplay.textContent + " " + prop.proposalTitle + " &";

                            } else {
                                winnerDisplay.textContent = winnerDisplay.textContent + " " + prop.proposalTitle;
                            }
                        });

                    } else {

                        winnerDisplay.textContent = "La proposition " + lasttour[0].proposalTitle + " a gagné !"
                    }
                })
            }
        })
    }
}