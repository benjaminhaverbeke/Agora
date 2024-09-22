
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['vote'];

    save({detail: {content}}){

        console.log(content)
    }


}