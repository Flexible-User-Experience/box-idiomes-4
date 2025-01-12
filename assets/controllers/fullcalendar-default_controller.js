import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller
{
    static values = {
        events: String,
        gcal: String,
    }

    connect()
    {
        console.log('fullcalendar connection!', this.eventsValue, this.gcalValue);
    }
}
