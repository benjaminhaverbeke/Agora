import { Controller } from '@hotwired/stimulus';

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
    displayResults(proposalId, results) {

        const proposal = this.gridTarget.querySelector('.proposal_'+proposalId)
        const resultArray = Object.entries(results);

        resultArray.forEach((element)=>

        {
            const vote = element[1];

            const mention = proposal.querySelector('.'+element[0]);

            mention.style.flexGrow = vote;


        });

    };

    initialize(){
        console.log('connect')
            this.fetchResults().then((res)=> {

                res.forEach((element) => {
                    console.log(res)
                    const sujet = document.querySelector('#sujet_results_'+element.sujet);

                    element.result.forEach((result)=> {

                        const proposal = sujet.querySelector('.proposal_'+result.proposalId);
                        const resultArray = Object.entries(result.pourcent);



                        resultArray.forEach((element)=>

                        {
                            const vote = element[1];


                            const mention = proposal.querySelector('.'+element[0]);

                            mention.style.flexGrow = vote;


                        });

                    })


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