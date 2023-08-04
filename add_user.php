<?php

    session_start();

    //if no variables, redirect to login 
    if(!isset($_SESSION['user'])) header('Location: Login.php');

    $_SESSION['table'] = 'users';
    $_SESSION['redirected-to'] = 'add_user.php';

    $user = $_SESSION['user'];
    $users_list = include('database/list_users.php');

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
                            <h1 class="add_user_heading">Add User</h1>

                                <div class="add_form_div">
                                    <form action="database/add.php" method="POST" class="add_form"> 
                                        <div class="add_input"> 
                                            <label for="first_name" class="add_label">First Name:</label>
                                            <input type="text" class="add_form_input" id="first_name" name="first_name" />
                                        </div>
                                        <div class="add_input"> 
                                            <label for="last_name" class="add_label">Last Name:</label>
                                            <input type="text" class="add_form_input" id="last_name" name="last_name" />
                                        </div>
                                        <div class="add_input"> 
                                            <label for="email" class="add_label">Email:</label>
                                            <input type="text" class="add_form_input" id="email" name="email" />
                                        </div>
                                        <div class="add_input"> 
                                            <label for="password" class="add_label">Password:</label>
                                            <input type="password" class="add_form_input" id="password" name="password" />
                                        </div>
                                        <button type="submit" class="add_user_button">Add User</button>
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
                            <h1 class="add_user_heading">List of Users</h1>
                                <div class="user_list">
                                    <table>
                                        <thead> 
                                            <tr> 
                                                <th>#</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                                <th>Option</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                                foreach($users_list as $index => $user){ ?>
                                                    <tr>
                                                        <td><?= $index + 1 ?></td>
                                                        <td class="first-name"><?= $user['first_name'] ?></td>
                                                        <td class="last-name"><?= $user['last_name'] ?></td>
                                                        <td class="email"><?= $user['email'] ?></td>
                                                        <td><?= date('M d, Y @ h:i:s A', strtotime($user['created_at'])) ?></td>
                                                        <td><?= date('M d, Y @ h:i:s A', strtotime($user['updated_at'])) ?></td>

                                                        <td class="options">
                                                            <a href="" class="edit_user" data-userid="<?= $user['id']?>">Edit</a>
                                                            <a href="" class="delete_user" data-userid="<?= $user['id']?>" data-fname="<?= $user['first_name']?>" data-lname="<?= $user['last_name']?>">Delete</a>
                                                        </td>
                                                    </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="user_count"><?= count($users_list)?> Users</p>
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

                        if(classList.contains('delete_user')){
                            e.preventDefault(); //prevent default action of anchor tag
                            userId = targetElement.dataset.userid;
                            fname = targetElement.dataset.fname;
                            lname = targetElement.dataset.lname;
                            name = fname + ' ' + lname;

                            BootstrapDialog.confirm({
                                type: BootstrapDialog.TYPE_DANGER,
                                message: 'Delete user ' + name + ' from database?',
                                callback: function(isDeleted){
                                        $.ajax({
                                        method: 'POST',
                                        data: {
                                            user_id: userId,
                                            f_name: fname,
                                            l_name: lname

                                        },
                                        url: 'database/delete_user.php',
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

                        if(classList.contains('edit_user')){
                            e.preventDefault();

                            firstName = targetElement.closest('tr').querySelector('td.first-name').innerHTML;
                            lastName = targetElement.closest('tr').querySelector('td.last-name').innerHTML;
                            mail = targetElement.closest('tr').querySelector('td.email').innerHTML;
                            userId = targetElement.dataset.userid;

                            BootstrapDialog.confirm({
                                title: 'Update ' + firstName + ' ' + lastName + '?',
                                message:

                                '<form>\
                                    <div class="form-group">\
                                        <label for="Fname">First Name</label>\
                                        <input type="text" class="form-control" id="Fname" value="'+  firstName +'">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="Fname">Last Name</label>\
                                        <input type="text" class="form-control" id="Lname" value="'+  lastName +'">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="Email">Email</label>\
                                        <input type="email" class="form-control" id="Email" value="'+  mail +'">\
                                    </div>\
                                </form>',

                                callback: function(isUpdated){

                                    if(isUpdated){
                                        $.ajax({
                                            method: 'POST',
                                            data: {
                                                user_id: userId,
                                                f_name: document.getElementById('Fname').value,
                                                l_name: document.getElementById('Lname').value,
                                                email: document.getElementById('Email').value
                                            },

                                            url: 'database/update_user.php',
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