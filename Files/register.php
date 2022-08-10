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
                <h3>Register</h3>
                <p>Please fill in this from to create an account</p>
                <fieldset>
                    <p>
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" maxlength="20" placeholder="Enter username" required><br>
                    </p>
                    <p>
                        <label for="first name">First name</label>
                        <input type="text" name="first name" maxlength="30" placeholder="Enter your first name" required><br>
                    </p>
                    <p>
                        <label for="last name">Last name</label>
                        <input type="text" name="last name" maxlength="30" placeholder="Enter your last name" required><br>
                    </p>
                    <p>
                        <label for="date of birth">Date of Birth</label>
                        <input type="date" name="date of birth"><br>
                    </p>
                    <p>
                        <label for="mobile phone">Mobile Phone Number</label>
                        <input type="number" name="mobile phone" maxlength="11" placeholder="Enter your mobile phone number"><br>
                    </p>

                    <p>
                        <label for="Gender">Gender</label>
                        <label for="Gender"><input type="radio" name="Gender" value="Male" />Male</label>
                        <label for="Gender"><input type="radio" name="Gender" value="Female" />Female</label>
                        <label for="Gender"><input type="radio" name="Gender" value="Other" />Other</label>
                        <label for="Gender"><input type="radio" name="Gender" value="Prefer not to say" />Prefer not to say</label><br>
                    </p>

                    <p>
                        <label>Email Address</label>
                        <input type="email" maxlength="40" placeholder="Enter email" /><br>
                    </p>

                    <p>
                        <label>Password</label>
                        <input type="password" maxlength="20" placeholder="Enter password" required /><br>
                    </p>

                    <p>
                        <label>Repeat Password</label>
                        <input type="password" maxlength="20" placeholder="Repeat password" required /><br>
                    </p>

                    <p>
                        <label>Where did you hear about us</label>
                        <select id="Where did you hear about us">
                            <option value="Facebook">Facebook</option>
                            <option value="Instagram">Instagram</option>
                            <option value="Twitter">Twitter</option>
                            <option value="Google">Google</option>
                        </select><br>
                    </p>

                    <p>
                        <input type="checkbox" /> By creating an account you agree to our <a href="#">Terms &amp; Privacy </a><br>
                    </p>

                    <div>
                        <button type="submit" class="registerbtn">Register</button>
                    </div>

                    <div>
                        <p>Already have an account? <a href=login.php>Login</a>
                        </p>
                    </div>

                </fieldset>
            </div>
        </form>

        <?php
        $conn = new mysqli('smcse-stuproj00.city.ac.uk', 'adbt208', '200010135', 'adbt208');
        if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email_address']) && !empty($_POST['first_name']) && !empty($_POST['last_name'])) { 

            $username = $_POST['username'];
            $password = $_POST['password'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $date_of_birth = $_POST['date_of_birth'];
            $email_address = $_POST['email_address'];
            $mobile_number = $_POST['mobile_number'];
            
            $query_username = "SELECT username FROM register WHERE username = '$username'";
        // execute the query
        $res_username = mysqli_query($conn, $query_username);

        // if the username entered by the user already exists, then create an alert and redirect to register page
        if (mysqli_num_rows($res_username) > 0) {
            echo "<script language='javascript'>
                    alert('Username already taken. Registration failed.');
                    window.location.href = 'https://smcse.city.ac.uk/student/adbt208/register.php';
                    </script>";
        }
        else {
            mysqli_query($conn, "INSERT INTO `register` ('username', 'password', 'first_name', 'last_name', 'date_of_birth', 'email_address', 'mobile_number') VALUES ('".$username."', '".$password."', '".$first_name."', '".$last_name."', '".$date_of_birth."', '".$email_address."', '".$mobile_number."')")
                
            or die(mysqli_error($conn)); // cancel if there is an error

            // then redirect the user to the same page and log in
            echo "<script language='javascript'>
                    alert('Registered successfully!')
                    window.location.href = 'https://smcse.city.ac.uk/student/adbt208/login.php ';
                    </script>";
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
