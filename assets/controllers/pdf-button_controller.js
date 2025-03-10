import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller
{
    static values = {
        attachment: String,
        path: String,
    };

    clicked()
    {
        const event = new CustomEvent('app-pdf-viewer-button-clicked', {detail: {attachment: this.attachmentValue, path: this.pathValue}});
        window.dispatchEvent(event);
    }
}
