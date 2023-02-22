import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'modal',
    ];

    connect() {
        this.modalTarget.classList.remove('in');
        this.modalTarget.style = '';
    }

    open() {
        this.modalTarget.classList.add('in');
        this.modalTarget.style = 'display: block; padding-right: 15px;';
    }

    close() {
        this.modalTarget.classList.remove('in');
        this.modalTarget.style = '';
    }
}
