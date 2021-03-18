import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import caLocale from "@fullcalendar/core/locales/ca";

import "@fullcalendar/core/main.css";
import "@fullcalendar/daygrid/main.css";
import "@fullcalendar/timegrid/main.css";
import "@fullcalendar/list/main.css";

document.addEventListener('DOMContentLoaded', () => {
    let calendarEl = document.getElementById('calendar-holder');
    let calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        timeZone: 'UTC',
        header: {
            left: 'prev today next',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay listWeek'
        },
        views: {
            timeGrid: {
                allDaySlot: true,
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    omitZeroMinute: false,
                    meridiem: 'short'
                },
                scrollTime: '08:00:00',
                minTime: '06:00:00',
                maxTime: '22:00:00',
                hiddenDays: [ 0 ]
            }
        },
        height: 750,
        locale: caLocale,
        timeFormat: 'HH:mm',
        firstDay: 1,
        lazyFetching: false,
        editable: true,
        navLinks: true,
        eventLimit: true,
        businessHours: false,
        displayEventTime: true,
        fixedWeekCount: false,
        weekNumbers: false,
        defaultView: 'timeGridWeek',
        themeSystem: 'bootstrap3',
        googleCalendarApiKey: 'AIzaSyCZZYZV-LqX2qDtggiEo1GmeNhxe3SAhfI',
        eventSources: [
            {
                googleCalendarId: 'es.spain#holiday@group.v.calendar.google.com',
                backgroundColor: '#FED3D7',
                textColor: '#FF0000',
                color: '#FED3D7'
            },
            {
                url: Routing.generate('fc_load_events'),
                type: 'POST',
                data: {},
                error: function(data) {
                    console.log('error!', data.responseText);
                }
            }
        ],
        datesRender: function (calendar) {
            if (calendar.hasOwnProperty('view') && calendar.view.hasOwnProperty('props') && calendar.view.props.hasOwnProperty('dateProfile') && calendar.view.props.dateProfile.hasOwnProperty('currentRangeUnit') && calendar.view.props.dateProfile.hasOwnProperty('currentRange') && calendar.view.props.dateProfile.currentRange.hasOwnProperty('start') && calendar.view.props.dateProfile.currentRange.hasOwnProperty('end') && calendar.view.props.dateProfile.currentRange.start instanceof Date && calendar.view.props.dateProfile.currentRange.end instanceof Date) {
                let exportCalendarPdfListAnchorNode = jQuery('#export_calendar_pdf_list_anchor');
                let start = calendar.view.props.dateProfile.currentRange.start;
                let end = calendar.view.props.dateProfile.currentRange.end;
                console.log(calendar.view.props.dateProfile, start, end);
                exportCalendarPdfListAnchorNode.attr('data-scope', calendar.view.props.dateProfile.currentRangeUnit);
                exportCalendarPdfListAnchorNode.attr('data-start', start.getFullYear()+'-'+twoDigitsPadWithZeros(start.getMonth())+'-'+twoDigitsPadWithZeros(start.getDate()));
                exportCalendarPdfListAnchorNode.attr('data-end', end.getFullYear()+'-'+twoDigitsPadWithZeros(end.getMonth())+'-'+twoDigitsPadWithZeros(end.getDate()));
            }
        }
    });
    calendar.render();
});

function twoDigitsPadWithZeros(number) {
    number = number + '';

    return number.length >= 2 ? number : new Array(2).join('0') + number;
}
