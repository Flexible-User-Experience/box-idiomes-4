import { Controller } from '@hotwired/stimulus';
import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import googleCalendarPlugin from '@fullcalendar/google-calendar';

/* stimulusFetch: 'lazy' */
export default class extends Controller
{
    static values = {
        events: String,
        gcal: String,
    }

    static targets = [
        'holder',
    ];

    connect()
    {
        console.log('fullcalendar connection!', this.eventsValue, this.gcalValue);
        const calendar = new Calendar(this.holderTarget, {
            plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin, googleCalendarPlugin],
            initialView: 'dayGridMonth',
            timeZone: 'Europe/Madrid',
            headerToolbar: {
                start: 'prev,today,next',
                center: 'title',
                end: 'timeGridDay,timeGridWeek,dayGridMonth listWeek'
            },
            views: {
                timeGrid: {
                    nowIndicator: true,
                    allDaySlot: true,
                    slotLabelFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        omitZeroMinute: false,
                        meridiem: 'short'
                    },
                    scrollTime: '12:00:00',
                    slotMinTime: '07:00:00',
                    slotMaxTime: '22:00:00',
                    hiddenDays: [ 0 ]
                }
            },
            height: 780,
            locale: 'ca',
            firstDay: 1,
            lazyFetching: false,
            editable: false,
            navLinks: true,
            businessHours: false,
            displayEventTime: true,
            fixedWeekCount: false,
            weekNumbers: false,
            themeSystem: 'bootstrap3',
            googleCalendarApiKey: this.gcalValue,
            eventSources: [
                {
                    googleCalendarId: 'es.spain#holiday@group.v.calendar.google.com',
                    backgroundColor: '#FED3D7',
                    textColor: '#FF0000',
                    color: '#FED3D7'
                },
                {
                    url: this.eventsValue,
                    type: 'POST',
                    data: {},
                    error: function(data) {
                        console.error('error!', data.responseText);
                    }
                }
            ],
            events: [
                {
                    title: 'All Day Event',
                    start: '2020-09-01'
                },
                {
                    title: 'Long Event',
                    start: '2020-09-07',
                    end: '2020-09-10'
                },
            ],
        })
        calendar.render()
    }
}
