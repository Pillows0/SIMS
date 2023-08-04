<?php
    //start session
    session_start();

    //dont think i need this maybe i will 
    //if(isset($_SESSION['user'])) header('Location: Dashboard.php');

    $error_message = '';
    
    if($_POST){
        
        include('database/connection.php');

        $username = $_POST['username'];
        $password = $_POST['password'];
        
        //query to check if username and password is set in database
        $query = 'SELECT * FROM users WHERE users.email="'. $username .'" AND users.password="'. $password .'"';
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if($stmt->rowCount() > 0){

        //after logging in, redirect user to the dashboard.

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt->fetchAll()[0]; //fetch from index 0
            $_SESSION['user'] = $user; //store user value user to session

            header('Location: Dashboard.php'); //redirect to dashboard

        } else $error_message = "Incorrect username or password";

    }
?>

<!DOCTYPE html>
<link rel="stylesheet" href="ims_css.css">
<html>

<head>
    <title>Inventory Management</title>
</head>

<body>

    <?php
        if(!empty($error_message)) { ?>
        
        <div id="errorMessage">
            <p>Error: <?= $error_message ?> </p>
         </div>
    <?php } ?>


    <div class="content">
        <div class="login_header">
            <h1>IMS</h1>
            <h3>Inventory Management System</h3>
        </div>

        <div class="login_body">

            <form action="Login.php" method="POST">

                <div class="login_input">
                    <label for="">Username:</label>
                    <input placeholder="example@ims.com" name="username" type="text">
                </div>

                <div class="login_input">
                    <label for="">Password:</label>
                    <input placeholder="password" name="password" type="password">
                </div>

                <div class="login_button">
                    <button>Login</button>
                </div>
            </form>
        </div> 
    </div>
</body>

</html>