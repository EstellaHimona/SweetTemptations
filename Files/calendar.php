<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sweet Temptations Confectionary</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,600">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link href="javascript.js">

    <!-- DayPilot library -->
    <script src="js/daypilot.daypilot-all.min.js"></script>
</head>

<body style="font-size: 10px">
    <?php require_once '_header.php'; ?>

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

    <script>
        var elements = {
            doctor: document.querySelector("#doctor")
        };

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
        calendar.eventDeleteHandling = "Update";

        calendar.onEventMoved = function(args) {
            DayPilot.Http.ajax({
                url: "backend_move.php",
                data: args,
                success: function(ajax) {
                    calendar.message(ajax.data.message);
                }
            });
        };
        calendar.onEventResized = function(args) {
            DayPilot.Http.ajax({
                url: "backend_move.php",
                data: args,
                success: function(ajax) {
                    calendar.message(ajax.data.message);
                }
            });
        };
        calendar.onEventDeleted = function(args) {
            var params = {
                id: args.e.id(),
            };
            DayPilot.Http.ajax({
                url: "backend_delete.php",
                data: params,
                success: function(ajax) {
                    calendar.message("Deleted.");
                }
            })
        };

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

            var form = [{
                    name: "Edit Appointment"
                },
                {
                    name: "Name",
                    id: "text"
                },
                {
                    name: "Status",
                    id: "tags.status",
                    options: [{
                            name: "Free",
                            id: "free"
                        },
                        {
                            name: "Waiting",
                            id: "waiting"
                        },
                        {
                            name: "Confirmed",
                            id: "confirmed"
                        },
                    ]
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
                    name: "Doctor",
                    id: "resource",
                    disabled: true,
                    options: doctors
                },
            ];

            var data = args.e.data;

            var options = {
                focus: "text"
            };

            DayPilot.Modal.form(form, data, options).then(function(modal) {
                if (modal.canceled) {
                    return;
                }

                var params = {
                    id: modal.result.id,
                    name: modal.result.text,
                    status: modal.result.tags.status
                };

                DayPilot.Http.ajax({
                    url: "backend_update.php",
                    data: params,
                    success: function(ajax) {
                        calendar.events.update(modal.result);
                    }
                });
            });


        };
        calendar.init();


        function loadEvents(day) {
            var start = nav.visibleStart();
            var end = nav.visibleEnd();

            var params = {
                doctor: elements.doctor.value,
                start: start.toString(),
                end: end.toString()
            };

            DayPilot.Http.ajax({
                url: "backend_events_doctor.php",
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

        elements.doctor.addEventListener("change", function() {
            loadEvents();
        });

        var doctors = [];
        DayPilot.Http.ajax({
            url: "backend_resources.php",
            success: function(ajax) {
                doctors = ajax.data;

                doctors.forEach(function(item) {
                    var option = document.createElement("option");
                    option.value = item.id;
                    option.innerText = item.name;
                    elements.doctor.appendChild(option);
                });

                loadEvents();

            }
        })

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
