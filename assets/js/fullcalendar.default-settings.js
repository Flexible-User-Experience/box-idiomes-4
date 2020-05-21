import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";

import "@fullcalendar/core/main.css";
import "@fullcalendar/daygrid/main.css";
import "@fullcalendar/timegrid/main.css";

document.addEventListener("DOMContentLoaded", () => {
    var calendarEl = document.getElementById("calendar-holder");
    var calendar = new Calendar(calendarEl, {
        // eventSources: [
        //     {
        //         url: eventsUrl,
        //         method: "POST",
        //         extraParams: {
        //             filters: JSON.stringify({})
        //         },
        //         failure: () => {
        //             // alert("There was an error while fetching FullCalendar!");
        //         },
        //     },
        // ],
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin],
        timeZone: "UTC",
        header: {
            left: 'prev today next',
            center: 'title',
            right: 'dayGridMonth,agendaDay listWeek'
        },
        views: {

            agendaDay: {
                allDaySlot: true,
                slotLabelFormat: 'HH:mm',
                scrollTime: '08:00:00',
                minTime: '06:00:00',
                maxTime: '22:00:00'
            }
        },
        height: 750,
        locale: 'ca',
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
        defaultView: 'dayGridMonth',
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
        ]
    });
    calendar.render();
});
