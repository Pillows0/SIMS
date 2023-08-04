<?php

    session_start();

    //if no variables, redirect to login 
    if(!isset($_SESSION['user'])) header('Location: Login.php');

    $_SESSION['table'] = "suppliers";
    $_SESSION['redirected-to'] = 'add_supplier.php';

    $user = $_SESSION['user'];

    $show_table = 'suppliers';
    $supplier_list = include('database/show.php');

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
                            <h1 class="add_user_heading">Add Supplier</h1>

                                <div class="add_form_div">
                                    <form action="database/add.php" method="POST" class="add_form"> 
                                        <div class="add_input"> 
                                            <label for="first_name" class="add_label">Supplier Name:</label>
                                            <input type="text" class="add_form_input" id="supplier_name" name="supplier_name" />
                                        </div>
                                        <div class="add_input"> 
                                            <label for="last_name" class="add_label">Location:</label>
                                            <input type="text" class="add_form_input" id="supplier_location" name="supplier_location" />
                                        </div>
                                        <div class="add_input"> 
                                            <label for="email" class="add_label">Email:</label>
                                            <input type="text" class="add_form_input" id="email" name="email" />
                                        </div>
                                        <button type="submit" class="add_user_button">Add Supplier</button>
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
                            <h1 class="add_user_heading">List of Suppliers</h1>
                                <div class="user_list">
                                    <table>
                                        <thead> 
                                            <tr> 
                                                <th>#</th>
                                                <th>Supplier Name</th>
                                                <th>Location</th>
                                                <th>Email</th>
                                                <th>Created by</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                                <th>Option</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                                foreach($supplier_list as $index => $supplier){ ?>
                                                    <tr>
                                                        <td><?= $index + 1 ?></td>
                                                        <td class="first-name"><?= $supplier['supplier_name'] ?></td>
                                                        <td class="last-name"><?= $supplier['supplier_location'] ?></td>
                                                        <td class="email"><?= $supplier['email'] ?></td>
                                                        <td class="last-name"><?= $supplier['created_by'] ?></td>
                                                        <td><?= date('M d, Y @ h:i:s A', strtotime($supplier['created_at'])) ?></td>
                                                        <td><?= date('M d, Y @ h:i:s A', strtotime($supplier['updated_at'])) ?></td>

                                                        <td class="options">
                                                            <a href="" class="edit_supplier" data-sid="<?= $supplier['supplier_id']?>">Edit</a>
                                                            <a href="" class="delete_supplier" data-sid="<?= $supplier['supplier_id']?>" data-sname="<?= $supplier['supplier_name']?>" >Delete</a>
                                                        </td>
                                                    </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="user_count"><?= count($supplier_list)?> Suppliers</p>
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

                        if(classList.contains('delete_supplier')){
                            e.preventDefault(); //prevent default action of anchor tag
                            supp_id = targetElement.dataset.sid;
                            supp_name = targetElement.dataset.sname;

                            BootstrapDialog.confirm({
                                type: BootstrapDialog.TYPE_DANGER,
                                message: 'Delete supplier "' + supp_name + '" from database?',
                                callback: function(isDeleted){
                                        $.ajax({
                                        method: 'POST',
                                        data: {
                                            s_id: supp_id,
                                            //s_name: supp_name,
                                            table: 'suppliers'

                                        },
                                        url: 'database/delete_supplier.php',
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

                        if(classList.contains('edit_supplier')){
                            e.preventDefault();

                            supplierName = targetElement.closest('tr').querySelector('td.first-name').innerHTML;
                            supplierLocation = targetElement.closest('tr').querySelector('td.last-name').innerHTML;
                            mail = targetElement.closest('tr').querySelector('td.email').innerHTML;
                            S_id = targetElement.dataset.sid;

                            BootstrapDialog.confirm({
                                title: 'Update "' + supplierName + '"?',
                                message:

                                '<form>\
                                    <div class="form-group">\
                                        <label for="Fname">Supplier Name</label>\
                                        <input type="text" class="form-control" id="SNAME" value="'+  supplierName +'">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="Fname">Location</label>\
                                        <input type="text" class="form-control" id="SLOC" value="'+  supplierLocation +'">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="Email">Email</label>\
                                        <input type="email" class="form-control" id="SEmail" value="'+  mail +'">\
                                    </div>\
                                </form>',

                                callback: function(isUpdated){

                                    if(isUpdated){
                                        $.ajax({
                                            method: 'POST',
                                            data: {
                                                supp_ID: S_id,
                                                supp_NAME: document.getElementById('SNAME').value,
                                                supp_LOC: document.getElementById('SLOC').value,
                                                supp_Email: document.getElementById('SEmail').value
                                            },

                                            url: 'database/update_supplier.php',
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