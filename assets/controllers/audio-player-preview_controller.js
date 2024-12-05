import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'modal',
        'loader',
        'title'
    ];

    connect() {
        this.modalTarget.classList.remove('in');
        this.modalTarget.style = '';
    }

    update(event) {
        this.modalTarget.classList.add('in');
        this.modalTarget.style = 'display: block; padding-right: 15px;';
        this.loaderTarget.src = event.detail.path;
        this.titleTarget.innerHTML = event.detail.title;
    }

    closeModal() {
        this.modalTarget.classList.remove('in');
        this.modalTarget.style = '';
        this.loaderTarget.src = '';
    }
}
