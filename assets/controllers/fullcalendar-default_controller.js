import { Controller } from '@hotwired/stimulus';
import 'fullcalendar';  // <--- this sucks !!! keep it here
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import googleCalendarPlugin from '@fullcalendar/google-calendar';
import routes from '../js/fos_routes.js';
import Router from '@toyokumo/fos-router';

/* stimulusFetch: 'lazy' */
export default class extends Controller
{
    static values = {
        events: String,
        gcal: String,
    }

    static targets = [
        'holder',
        'exporter',
    ];

    connect()
    {
        Router.setRoutingData(routes);
        const calendar = new Calendar(this.holderTarget, {
            plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin, googleCalendarPlugin],
            initialView: 'timeGridDay',
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
            datesSet: (datesSet) => {
                if (datesSet.hasOwnProperty('start') && datesSet.hasOwnProperty('end')) {
                    let start = datesSet.start;
                    let end = datesSet.end;
                    this.exporterTarget.href = Router.generate('admin_app_extrahelpermanager_exportCalendarPdfList', {start: start.getFullYear() + '-' + this.twoDigitsPadWithZeros(start.getMonth() + 1) + '-' + this.twoDigitsPadWithZeros(start.getDate()), end: end.getFullYear() + '-' + this.twoDigitsPadWithZeros(end.getMonth() + 1) + '-' + this.twoDigitsPadWithZeros(end.getDate())});
                }
            }
        })
        calendar.render()
    }

    twoDigitsPadWithZeros(number) {
        number = number + '';

        return number.length >= 2 ? number : new Array(2).join('0') + number;
    }
}
