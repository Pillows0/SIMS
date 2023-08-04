<!DOCTYPE html>
<link rel="stylesheet" href="ims_css.css">
<html>
    <head>
        <title>IMS Homepage</title>
    </head>

    <body>
        <div class="hp_login_link">
            <a href="Login.php">Login</a>
        </div>

        <div class="hp_content">
            <h1 style="text-align: center">IMS</h1>
            <!-- <p>Inventory Management System</p> -->
        </div>

        <div class="hp_content2">
            <div class="pictures"> 
                <article>
                    <script>
                        //Slideshows of pictures
                        let i = 0; 
                        let images = []; 
                        let timer = 5000; //5000ms so pics change every 5 secs. 

                        //initializing array with the pics 
                        images[0] = "images/hp1.jpg";
                        images[1] = "images/hp2.jpg";
                        images[2] = "images/hp3.jpg";

                        //making fuction that will change the pics 
                        function imageChanger() {
                            document.slideshow.src = images[i]; 

                            if(i<images.length-1){ 
                                i++; 
                            }
                            else{
                                i=0; 
                            }
                            //run function with set time limit
                            setTimeout("imageChanger()", timer);
                        }
                        //when the window loads, imageChanger function is called and starts the slide show;
                        window.onload = imageChanger;   
                    </script>
                    
                    <div>
                        <img name="slideshow" width="auto" height="auto"> <!--slideshow pics set up-->
                    </div>
                </article>
            </div>
            <div>
                <p style="text-align: center">
                    Track goods from one end to the other along your supply chain. <br>
                    Ensuring throughout that you know what you have, where it is, and how to manage it.
                </p>
            </div>
        </div>

    </body>
</html>
