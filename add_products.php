<?php

    session_start();

    //if no variables, redirect to login 
    if(!isset($_SESSION['user'])) header('Location: Login.php');

    $_SESSION['table'] = "products";
    $_SESSION['redirected-to'] = 'add_products.php';

    $user = $_SESSION['user'];
    
    $show_table = 'products';
    $products = include('database/show.php');

?>

<!DOCTYPE html>        
<link rel="stylesheet" href="ims_css.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css" integrity="sha512-PvZCtvQ6xGBLWHcXnyHD67NTP+a+bNrToMsIdX/NUqhw+npjLDhlMZ/PhSHZN4s9NdmuumcxKHQqbHlGVqc8ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <div class="row"> 
                        <div class="column"> 
                            <h1 class="add_user_heading">Add Products</h1>

                                <div class="add_form_div">
                                    <form action="database/add.php" method="POST" class="add_form"> 
                                        <div class="add_input"> 
                                            <label for="product_name" class="add_label">Product Name:</label>
                                            <input type="text" class="add_form_input" id="product_name" name="product_name" placeholder="Enter product name"/>
                                        </div>
                                        <div class="add_input"> 
                                            <label for="last_name" class="add_label">Description:</label>
                                            <textarea class="prod_desc" id="description" name="description">

                                            </textarea>
                                        </div>

                                        <button type="submit" class="add_user_button">Add Product</button>
                                    </form> 

                                    <!-- pop-up message after succesfully being added to system -->
                                    <?php if(isset($_SESSION['response'])) { 
                                        $response_message = $_SESSION['response']['message'];
                                        $is_success = $_SESSION['response']['success'];
                                    ?>
                                        <div class="responseMessage">
                                            <p class="<?= $is_success ? 'responseMessage_success' : 'responseMessage_error' ?>">
                                                <?= $response_message ?>
                                            </p>
                                        </div>
                                    <?php unset($_SESSION['response']); } ?>
                                </div>
                        </div> <!-- div column 1 end-->

                        <div class="column">
                            <h1 class="add_user_heading">List of Products</h1>
                                <div class="user_list">
                                    <table>
                                        <thead> 
                                            <tr> 
                                                <th>#</th>
                                                <th>Product Name</th>
                                                <th>Description</th>
                                                <th>Created by</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                                <th>Option</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                                foreach($products as $index => $product){ ?>
                                                    <tr>
                                                        <td><?= $index + 1 ?></td>
                                                        <td class="first-name"><?= $product['product_name'] ?></td>
                                                        <td class="last-name"><?= $product['description'] ?></td>
                                                        <td class="email"><?= $product['created_by'] ?></td>
                                                        <td><?= date('M d @ h:i A', strtotime($product['created_at'])) ?></td>
                                                        <td><?= date('M d @ h:i A', strtotime($product['updated_at'])) ?></td>

                                                        <td class="options">
                                                            <a href="" class="edit_product" data-pid="<?= $product['product_id']?>">Edit</a>
                                                            <a href="" class="delete_product" data-pid="<?= $product['product_id']?>" data-p_name="<?= $product['product_name'] ?>" >Delete</a>
                                                        </td>
                                                    </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="user_count"><?= count($products)?> Products</p>
                                </div>
                        </div> <!-- div column 2 end-->
                    </div> <!-- div row end-->
                </div>
            </div>
        </div>
        <!-- Edit/Delete user-->
        <script src="js/jquery-3.7.0.min.js"></script>

        <script>
            function script(){

                this.initialize = function(){
                    this.registerEvents();


                },
                this.registerEvents = function(){
                    document.addEventListener('click', function(e){ 
                        targetElement = e.target;
                        classList = targetElement.classList;

                        if(classList.contains('delete_product')){
                            e.preventDefault(); //prevent default action of anchor tag
                            pId = targetElement.dataset.pid;
                            pName = targetElement.dataset.p_name;


                            BootstrapDialog.confirm({
                                type: BootstrapDialog.TYPE_DANGER,
                                message: 'Delete product "' + pName + '" from database?',
                                callback: function(isDeleted){
                                        $.ajax({
                                        method: 'POST',
                                        data: {
                                            p_id: pId,
                                            //id: pId,
                                            //P_Name: pName,
                                            table: 'products'

                                        },
                                        url: 'database/delete_product.php',
                                        dataType: 'json',
                                        success: function(data){
                                            if(data.success){
                                                BootstrapDialog.alert({
                                                    type: BootstrapDialog.TYPE_SUCCESS,
                                                    message: data.message,
                                                    callback: function(){
                                                        location.reload();
                                                    }
                                                });
                                            } else {
                                                BootstrapDialog.alert({
                                                    type: BootstrapDialog.TYPE_DANGER,
                                                    message: data.message,
                                                });
                                            }
                                        }
                                    })
                                }
                            });
                        }

                        if(classList.contains('edit_product')){
                            e.preventDefault();

                            ProdName = targetElement.closest('tr').querySelector('td.first-name').innerHTML;
                            ProdDesc = targetElement.closest('tr').querySelector('td.last-name').innerHTML;
                            pId = targetElement.dataset.pid;

                            BootstrapDialog.confirm({
                                title: 'Update ' + ProdName + '?',
                                message:

                                '<form>\
                                    <div class="form-group">\
                                        <label for="Fname">First Name</label>\
                                        <input type="text" class="form-control" id="PNAME" value="'+  ProdName +'">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="Fname">Last Name</label>\
                                        <input type="text" class="form-control" id="DESC" value="'+  ProdDesc +'">\
                                    </div>\
                                </form>',

                                callback: function(isUpdated){

                                    if(isUpdated){
                                        $.ajax({
                                            method: 'POST',
                                            data: {
                                                prod_id: pId,
                                                prod_name: document.getElementById('PNAME').value,
                                                prod_desc: document.getElementById('DESC').value,

                                            },

                                            url: 'database/update_product.php',
                                            dataType: 'json',
                                            success: function(data){
                                                if(data.success){
                                                    BootstrapDialog.alert({
                                                        type: BootstrapDialog.TYPE_SUCCESS,
                                                        message: data.message,
                                                        callback: function(){
                                                            location.reload();
                                                        }
                                                    });

                                                } else {
                                                    BootstrapDialog.alert({
                                                        type: BootstrapDialog.TYPE_DANGER,
                                                        message: data.message,
                                                    });
                                                }

                                            }
                                        })
                                    }
                                }
                            });
                        }
                    });
                }
            }
            var script = new script();
            script.initialize();
        </script>


        <!--PASTED FROM WEB 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.js" integrity="sha512-AZ+KX5NScHcQKWBfRXlCtb+ckjKYLO1i10faHLPXtGacz34rhXU8KM4t77XXG/Oy9961AeLqB/5o0KTJfy2WiA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </body>
</html>   


