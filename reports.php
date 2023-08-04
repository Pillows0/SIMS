<?php

    session_start();

    //no variables redirect to login 
    if(!isset($_SESSION['user'])) header('Location: Login.php');

    $user = $_SESSION['user']; 

?>

<!DOCTYPE html>        
<link rel="stylesheet" href="ims_css.css">
<html>
    <head>
        <title>IMS Dashboard</title>
    </head>
    <body>
        <div id="db_container">
            <?php include('partials/sidebar.php') ?>

            <div class="db_content">
                <?php include('partials/topNav.php') ?>

                <div class="db_content_main">
                    <div id="reports_container">
                        <div class="boxes">
                            <div class="reportbox">
                                <p style="font-size: 15pt ">Export Products</p>
                                <p style="text-align: right"><a href="database/report_pdf2.php?report=product" class="export">PDF</a></p>
                            </div>
                            <!--<div class="reportbox">
                                <p style="font-size: 15pt">Export Suppliers</p>
                                <p style="text-align: right"><a href="" class="export" >PDF</a></p>
                            </div>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>