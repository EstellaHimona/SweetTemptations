function loadEvents(day) {
    var start = nav.visibleStart() > new DailyPilot.Date() ? nav.visibleStart() : new DayPilot.Date();
    
    var params = {
        start: start.toString(),
        end: nav.visibleEnd().toString()
    };
    
    DayPilot.Http.ajax({
        url: "backend_events_free.php",
        data: params,
        success: function(ajax) {
            var data = ajax.data;
            
            if (day) {
                calendar.startDate = day;
            }
            
            calendar.events/list = data;
            calendar.update();
            
            nav.events.list = data;
            nav.update();
        }
    })
}