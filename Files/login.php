<?php
    if(!isset($_SESSION)) {
        session_start(); // start the session if it still does not exist
    }

   $conn = new mysqli('smcse-stuproj00.city.ac.uk', 'adbt208', '200010135', 'adbt208');
    if ($db -> connect_error) {
        printf("Connection failer: %s\n", $db -> connect_error);
        exit();
    }
     
?>

<head>
    <meta charset="utf-8">
    <title>Sweet Temptations Confectionary</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,600">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body style="font-size: 10px">
    <header>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <div class="topnav" id="myTopnav">
            <a class="navbar-brand" href="about.html">
                <div class="logo-image">
                    <img src="images/st%20cupcake%20logo.jpg" class="img-fluid">
                </div>
            </a>
            <a href="about.html">About</a>
            <a href="shop.html">Shop</a>
            <a href="login.php">My Account</a>
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
        <form method="post" action="#">
            <div class="container">
                <h3>Login</h3>
                <p>Please fill in this from to login to your account</p>
                <fieldset>
                    <p>
                        <label for="username">Username</label><br>
                        <input type="text" id="username" name="username" maxlength="20" placeholder="Enter username" required><br>
                    </p>

                    <p>
                        <label for="psw">Password</label><br>
                        <input type="password" id="psw" name="psw" maxlength="20" placeholder="Enter password" required><br>
                    </p>

                    <button type="submit" class="loginbtn"> Login</button>

                    <div>
                        <p>Don't have an account? <a href=register.php>Register</a>
                        </p>
                    </div>

                </fieldset>
            </div>
        </form>
        <?php
        $conn = new mysqli('smcse-stuproj00.city.ac.uk', 'adbt208', '200010135', 'adbt208');
        if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){
        // declare variables containing values from the input fields of the login form
        //the values come from the *name* attributes of the input fields
        $username = $_POST['username'];
        $password = $_POST['password'];

        // select the username and password fields which match the data entered in the input fields
        $query = "SELECT username, password FROM register WHERE username = '$username' AND password = '$password'";
        // execute the query
        $result = $conn->query($query);
        // store the results in $row variable
        $row = mysqli_fetch_row($result);

        // if $row returned no results from the query, then create a javascript alert
        if (!isset($row[0]) || !isset($row[1])) {
            echo "<script language='javascript'>
                    alert('Please enter valid credentials.');
                    window.location.href = 'https://smcse.city.ac.uk/student/adbt208/login.php';
                  </script>";
        }
        // if there is a match then activate a custom session called 'username' and redirect to shop.html
        else if ($username == $row[0] && $password == $row[1]) {
            $_SESSION['username'] = $username;

            // redirect to this page
            header("Location: shop.html");
        }
            
    }
        ?>
    </main>

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
