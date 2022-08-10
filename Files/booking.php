<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sweet Temptations - Book an appointment</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,600">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link href="javascript.js">

    <!-- DayPilot library -->
    <script src="js/daypilot.daypilot-all.min.js"></script>
</head>

<body>
    <header>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <div class="topnav" id="myTopnav">
            <a class="navbar-brand">
                <div class="logo-image">
                    <img src="images/st%20cupcake%20logo.jpg" class="img-fluid">
                </div>
            </a>
            <a href="about.html">About</a>
            <a href="shop">Shop</a>
            <a href="login.html">My Account</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <br><br><br><br><br><br><br><br>
        <h1>Confectionary</h1>
        <h2>Sweet Temptations</h2>
    </header>

    <script>
        function myFunction() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
                x.className += " responsive";
            } else {
                x.className = "topnav";
            }
        }

    </script>
    <?php require_once '_header.php'; ?>

    <main>
        <?php require_once '_navigation.php'; ?>
        <form method="post" action="#">
            <div class="container">
                <h3>Book an appointment with an expert</h3>
                <p>Please fill in this from to book an appointment and visit our stores</p>
                <div id="calendar"></div>
            </div>
            <div class="column-left">
                <div id="nav"></div>
            </div>
            <div class="column-main">
                <div class="toolbar">Available time slots:</div>
                <div id="calendar"></div>
            </div>
        </form>
    </main>


    <script src="js/daypilot/daypilot-all.min.js"></script>

    <script>
        var nav = new DayPilot.Navigator("nav");
        nav.selectMode = "week";
        nav.showMonths = 3;
        nav.skipMonths = 3;
        nav.onTimeRangeSelected = function(args) {
            loadEvents(args.start.firstDayOfWeek(DayPilot.Locale.find(nav.locale).weekStarts), args.start.addDays(7));
        };
        nav.init();

        var calendar = new DayPilot.Calendar("calendar");
        calendar.viewType = "Week";
        calendar.timeRangeSelectedHandling = "Disabled";
        calendar.eventMoveHandling = "Disabled";
        calendar.eventResizeHandling = "Disabled";
        calendar.eventArrangement = "SideBySide";
        calendar.onBeforeEventRender = function(args) {
            if (!args.data.tags) {
                return;
            }
            switch (args.data.tags.status) {
                case "free":
                    args.data.backColor = "#3d85c6"; // blue
                    args.data.barHidden = true;
                    args.data.borderColor = "darker";
                    args.data.fontColor = "white";
                    args.data.html = "Available<br/>" + args.data.tags.doctor;
                    args.data.toolTip = "Click to request this time slot";
                    break;
                case "waiting":
                    args.data.backColor = "#e69138"; // orange
                    args.data.barHidden = true;
                    args.data.borderColor = "darker";
                    args.data.fontColor = "white";
                    args.data.html = "Your appointment, waiting for confirmation";
                    break;
                case "confirmed":
                    args.data.backColor = "#6aa84f"; // green
                    args.data.barHidden = true;
                    args.data.borderColor = "darker";
                    args.data.fontColor = "white";
                    args.data.html = "Your appointment, confirmed";
                    break;
            }
        };
        calendar.onEventClick = function(args) {
            if (args.e.tag("status") !== "free") {
                calendar.message("You can only request a new appointment in a free slot.");
                return;
            }

            var form = [{
                    name: "Request an Appointment"
                },
                {
                    name: "From",
                    id: "start",
                    dateFormat: "MMMM d, yyyy h:mm tt",
                    disabled: true
                },
                {
                    name: "To",
                    id: "end",
                    dateFormat: "MMMM d, yyyy h:mm tt",
                    disabled: true
                },
                {
                    name: "Name",
                    id: "name"
                },
            ];

            var data = {
                id: args.e.id(),
                start: args.e.start(),
                end: args.e.end(),
            };

            var options = {
                focus: "name"
            };

            DayPilot.Modal.form(form, data, options).then(function(modal) {
                if (modal.canceled) {
                    return;
                }

                DayPilot.Http.ajax({
                    url: "backend_request_save.php",
                    data: modal.result,
                    success: function(ajax) {
                        args.e.data.tags.status = "waiting";
                        calendar.events.update(args.e.data);
                    }
                })
            });

        };
        calendar.init();

        loadEvents();

        function loadEvents(day) {
            var start = nav.visibleStart() > new DayPilot.Date() ? nav.visibleStart() : new DayPilot.Date();

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
                    calendar.events.list = data;
                    calendar.update();

                    nav.events.list = data;
                    nav.update();

                }
            });
        }

    </script>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <p><em>Copyright &copy; 2021 Sweet Temptations Confectionary</em></p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h4>Contact us</h4>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-phone"></i>+44 123 121 2211</li>
                        <li><i class="fas fa-envelope"></i>email@sweettemptations.confectionary.co.uk</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
