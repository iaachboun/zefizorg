<?php
$postData = $uploadedFile = $statusMsg = '';
$msgClass = 'errordiv';
if(isset($_POST['submit'])){
    // Get the submitted form data
    $postData = $_POST;
    $email = $_POST['email'];
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Check whether submitted data is not empty
    if(!empty($email) && !empty($name) && !empty($subject) && !empty($message)){

        // Validate email
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            $statusMsg = 'Please enter your valid email.';
        }else{
            $uploadStatus = 1;

            // Upload attachment file
            if(!empty($_FILES["attachment"]["name"])){

                // File path config
                $targetDir = "uploads/";
                $fileName = basename($_FILES["attachment"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

                // Allow certain file formats
                $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg');
                if(in_array($fileType, $allowTypes)){
                    // Upload file to the server
                    if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)){
                        $uploadedFile = $targetFilePath;
                    }else{
                        $uploadStatus = 0;
                        $statusMsg = "Sorry, there was an error uploading your file.";
                    }
                }else{
                    $uploadStatus = 0;
                    $statusMsg = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.';
                }
            }

            if($uploadStatus == 1){

                // Recipient
                $toEmail = 'ili.aachboun1@gmail.com';

                // Sender
                $from = 'sender@example.com';
                $fromName = 'CodexWorld';

                // Subject
                $emailSubject = 'Contact Request Submitted by '.$name;

                // Message
                $htmlContent = '<h2>Contact Request Submitted</h2>
                    <p><b>Name:</b> '.$name.'</p>
                    <p><b>Email:</b> '.$email.'</p>
                    <p><b>Subject:</b> '.$subject.'</p>
                    <p><b>Message:</b><br/>'.$message.'</p>';

                // Header for sender info
                $headers = "From: $fromName"." <".$from.">";

                if(!empty($uploadedFile) && file_exists($uploadedFile)){

                    // Boundary
                    $semi_rand = md5(time());
                    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

                    // Headers for attachment
                    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

                    // Multipart boundary
                    $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
                        "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";

                    // Preparing attachment
                    if(is_file($uploadedFile)){
                        $message .= "--{$mime_boundary}\n";
                        $fp =    @fopen($uploadedFile,"rb");
                        $data =  @fread($fp,filesize($uploadedFile));
                        @fclose($fp);
                        $data = chunk_split(base64_encode($data));
                        $message .= "Content-Type: application/octet-stream; name=\"".basename($uploadedFile)."\"\n" .
                            "Content-Description: ".basename($uploadedFile)."\n" .
                            "Content-Disposition: attachment;\n" . " filename=\"".basename($uploadedFile)."\"; size=".filesize($uploadedFile).";\n" .
                            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                    }

                    $message .= "--{$mime_boundary}--";
                    $returnpath = "-f" . $email;

                    // Send email
                    $mail = mail($toEmail, $emailSubject, $message, $headers, $returnpath);

                    // Delete attachment file from the server
                    @unlink($uploadedFile);
                }else{
                    // Set content-type header for sending HTML email
                    $headers .= "\r\n". "MIME-Version: 1.0";
                    $headers .= "\r\n". "Content-type:text/html;charset=UTF-8";

                    // Send email
                    $mail = mail($toEmail, $emailSubject, $htmlContent, $headers);
                }

                // If mail sent
                if($mail){
                    $statusMsg = 'Your contact request has been submitted successfully !';
                    $msgClass = 'succdiv';

                    $postData = '';
                }else{
                    $statusMsg = 'Your contact request submission failed, please try again.';
                }
            }
        }
    }else{
        $statusMsg = 'Please fill all the fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ZefiZorg - Contact</title>
    <meta charset="utf-8">
    <link rel="icon" href="images/logoSmall.png" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="ZefiZorg uitzend bureau">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
    <link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
    <link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
    <link rel="stylesheet" type="text/css" href="styles/contact.css">
    <link rel="stylesheet" type="text/css" href="styles/contact_responsive.css">
</head>
<body>

<div class="super_container">

    <!-- Header -->

    <header class="header trans_200">

        <!-- Top Bar -->
        <div class="top_bar">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="top_bar_content d-flex flex-row align-items-center justify-content-start">
                            <div class="top_bar_item"><a href="contact.php">Neem contact op</a></div>
                            <div class="top_bar_item"><a href="contact.php">Maak een afspraak</a></div>
                            <div class="emergencies  d-flex flex-row align-items-center justify-content-start ml-auto">
                                <div class="home_about_icon"><img src="images/phone-call.svg" alt=""></div>
                                +31 687042813

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Header Content -->
        <div class="header_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="header_content d-flex flex-row align-items-center justify-content-start">
                            <nav class="main_nav ml-auto">
                                <ul>
                                    <li><a href="index.html">Home</a></li>
                                    <li><a href="about.html">Over ons</a></li>
                                    <li><a href="zorgaanbieders.html">Voor zorgaanbieders</a></li>
                                    <li><a href="zorgprofessionals.html">Voor zorgprofessionals</a></li>
                                    <li><a href="contact.php">Contact</a></li>
                                </ul>
                            </nav>
                            <div class="hamburger ml-auto"><i class="fa fa-bars" aria-hidden="true"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo -->
        <div class="logo_container_outer">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="logo_container">
                            <a href="index.html">
                                <div class="logo_content d-flex flex-column align-items-start justify-content-center">
                                    <div class="logo_line"></div>
                                    <div class="logo d-flex flex-row align-items-center justify-content-center">
                                        <div class="logo_text"><img
                                                    src="images/28cad8ba02ce46109e46df013f291705.png"></div>
                                    </div>
                                    <div class="logo_sub">zelfstandig - empatisch - flexibel - inovatief</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </header>

    <!-- Menu -->

    <div class="menu_container menu_mm">

        <!-- Menu Close Button -->
        <div class="menu_close_container">
            <div class="menu_close"></div>
        </div>

        <!-- Menu Items -->
        <div class="menu_inner menu_mm">
            <div class="menu menu_mm">
                <ul class="menu_list menu_mm">
                    <li class="menu_item menu_mm"><a href="index.html">Home</a></li>
                    <li class="menu_item menu_mm"><a href="about.html">Over ons</a></li>
                    <li class="menu_item menu_mm"><a href="zorgaanbieders.html">Voor zorgaanbieders</a></li>
                    <li class="menu_item menu_mm"><a href="zorgprofessionals.html">Voor zorgprofessionals</a></li>
                    <li class="menu_item menu_mm"><a href="contact.php">Contact</a></li>
                </ul>
            </div>

            <div class="menu_extra">
                <div class="menu_appointment"><a href="contact.php">Maak een afspraak</a></div>
                <div class="menu_emergencies"><strong style="font-size: 1.3em;">✆</strong> +31 687042813</div>
            </div>

        </div>

    </div>

    <!-- Home -->

    <div class="home">
        <div class="home_background parallax-window" data-parallax="scroll" data-image-src="images/contact.jpg"
             data-speed="0.8"></div>
        <div class="home_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="home_content">
                            <div class="home_title"><span>ZefiZorg</span> Aanmelden</div>
                            <div class="breadcrumbs">
                                <ul>
                                    <li><a href="#">Home</a></li>
                                    <li>Contact</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact -->

    <div class="contact">
        <div class="container">
            <h2 class="aanmeldTitle">Meld je aan</h2>

            <div class="row">
                <!-- Contact Form -->
                <div class="col-md-6 form_col aanmeldForm">
                    <div class="contact_form_container">
                        <!-- Display submission status -->
                        <?php if (!empty($statusMsg)) { ?>
                            <p class="statusMsg <?php echo !empty($msgClass) ? $msgClass : ''; ?>"><?php echo $statusMsg; ?></p>
                        <?php } ?>

                        <!-- Display contact form -->
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control"
                                       value="<?php echo !empty($postData['name']) ? $postData['name'] : ''; ?>"
                                       placeholder="Name" required="">
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control"
                                       value="<?php echo !empty($postData['email']) ? $postData['email'] : ''; ?>"
                                       placeholder="Email address" required="">
                            </div>
                            <div class="form-group">
                                <input type="text" name="subject" class="form-control"
                                       value="<?php echo !empty($postData['subject']) ? $postData['subject'] : ''; ?>"
                                       placeholder="Subject" required="">
                            </div>
                            <div class="form-group">
                                    <textarea name="message" class="form-control" placeholder="Write your message here"
                                              required=""><?php echo !empty($postData['message']) ? $postData['message'] : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <input type="file" name="attachment" class="form-control">
                            </div>
                            <div class="submit">
                                <input type="submit" name="submit" class="btn" value="SUBMIT">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->

    <footer class="footer">
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="copyright_content d-flex flex-lg-row flex-column align-items-lg-center justify-content-start">
                            <div class="cr" style="margin: 0 auto;">
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                Copyright &copy;<script>document.write(new Date().getFullYear());</script>
                                ZefiZorg | All rights reserved <!--| This template is made with
                                <i class="fa fa-heart-o" aria-hidden="true"></i> by <a
                                    href="https://colorlib.com" target="_blank">Colorlib</a>-->
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            </div>
                            <!-- <div class="footer_social ml-lg-auto">
                                 <ul>
                                     <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                                     <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                     <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                     <li><a href="#"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>
                                     <li><a href="#"><i class="fa fa-behance" aria-hidden="true"></i></a></li>
                                     <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                 </ul>
                             </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCIwF204lFZg1y4kPSIhKaHEXMLYxxuMhA"></script>
<script src="js/contact.js"></script>
</body>
</html>
