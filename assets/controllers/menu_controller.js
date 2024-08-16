
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    list = "none";
    burger;

    static targets = ['list', 'burger'];
    animate(){

        if(this.listTarget.style.display === "block")

        {
            this.listTarget.style.display = "none";
            this.burgerTarget.classList.remove('rotate');

        } else {

            this.listTarget.style.display = "block";
            this.burgerTarget.classList.add('rotate');

        }



    }




}