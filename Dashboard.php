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
                <div>
                    <p>Inventory Management Dashboard</p>
                </div>

                <div class="db_content_main">
					<div class="col5">
						<figure class="highcharts-figure">
						    <div id="container"></div>
						    <p class="highcharts-description" style="text-align: center">
						        Here is the breakdown of the Products.
						    </p>
						</figure>						
					</div>			
					<!-- <div class="col50">
						<figure class="highcharts-figure">
						    <div id="containerBarChart"></div>
						    <p class="highcharts-description">
						        Here is the breakdown of the purchase orders by status.
						    </p>
						</figure>						
					</div>-->
                    
                </div>

            </div>
        </div>

        <!-- PASTED FROM WEB -->
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script>
            // Data retrieved from https://netmarketshare.com
            Highcharts.chart('container', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Products',
                    align: 'center'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Brands',
                    colorByPoint: true,
                    data: [{
                        name: 'Product 1',
                        y: 70.67,
                        sliced: true,
                        selected: true
                    }, {
                        name: 'Product 2',
                        y: 14.77
                    },  {
                        name: 'Product 3',
                        y: 4.86
                    }, {
                        name: 'Product 4',
                        y: 2.63
                    }]
                }]
            });
        </script>
        </body>
</html>