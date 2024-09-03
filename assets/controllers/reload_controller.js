import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['proposal', 'sujet', 'salon'];

    reload() {
        this.proposalTargets.reload();
    }
}
