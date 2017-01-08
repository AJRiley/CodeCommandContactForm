<?php

session_start();

$name = '';
$email = '';
$message = '';

if (isset($_SESSION['return_data'])) {
    
    $formOK = $_SESSION['return_data']['formOK'];
    $entries = $_SESSION['return_data']['entries'];
    $errors = $_SESSION['return_data']['errors'];
    unset($_SESSION['return_data']);
    
    if (!$formOK) {
        foreach ($entries as $key => $value) {
            ${$key} = $value;
        }
        $submitmessage = 'There were some problems with your submission.';
        $responsetype = 'failure';
    }
    else {
        $submitmessage = 'Thank you! Your email has been submitted.';
        $responsetype = 'success';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Ajax Contact Form</title>
    <meta name="description" content="A basic contact form - built using phpmailer and the jquery validate and form plugins.">
    <meta name="keywords" content="contact form, jquery, validate, form, phpmailer, ajax">
    <meta name="author" content="Chris Wojcik, hello@chriswojcik.net">
    <meta name="viewport" content="width=device-width">
    <style type="text/css"> 
        /* Form */
        form ol { list-style: none; padding: 0; }        
 
        #submit-container { overflow: hidden; }
        #send { padding: 0.25em; float: left; }
        #loading { width: 32px; height: 32px; display: none; margin-left: 0.75em; float: left; background: url(img/ajax-loader.gif); }
        .instruction { font-style: italic; }        
        .star { color: red; }
        input.error, textarea.error { -webkit-box-shadow: 0px 0px 2px 0px #fa301e; -moz-box-shadow: 0px 0px 2px 0px #fa301e; box-shadow: 0px 0px 2px 0px #fa301e; }
        label.error { color: red; display: inline; margin-left: 0.5em; }
        
        /* Submit Message */
        .failure { color: red; background: pink }
        .success { color: green; background: lightgreen; }
        .success, .failure { padding: 1em; font-style: italic; }
        #submit-message { margin-top: 2em; clear: both; }        
        
        /* Utility */
        .hidden { display: none !important; visibility: hidden; }
		@charset "utf-8";
/* CSS Document */

@font-face {
    font-family: 'BebasRegular';
    src: url('fonts/BEBAS___-webfont.eot');
    src: url('fonts/BEBAS___-webfont.eot?#iefix') format('embedded-opentype'),
         url('fonts/BEBAS___-webfont.woff') format('woff'),
         url('fonts/BEBAS___-webfont.ttf') format('truetype'),
         url('fonts/BEBAS___-webfont.svg#BebasRegular') format('svg');
    font-weight: normal;
    font-style: normal;
}

body {
	font-size:100%;
	font-family:Georgia, "Times New Roman", Times, serif;
	color:#3a3a3a;
}

.body {
	width:576px;
	margin:0 auto;
	display:block;
}

h1 {
	width:498px;
	height:64px;
	background:url(images/h1-bg.jpg);
	color:#fff;
	font-family:bebas;
	padding:17px 0px 0px 78px;
	letter-spacing:1px;
	font-size:2.2em;
	margin:0 auto;
}

form {
	width:459px;
	margin:0 auto;
}

label {
	display:block;
	margin-top:20px;
	font-family:'futura pt';
	display: block; 
	width: 100%;
	font-weight: 700; 
	margin-top: 1em; 
}

input, textarea {
	width:439px;
	height:27px;
	background:#efefef;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border:1px solid #dedede;
	padding:10px;
	margin-top:3px;
	font-size:0.9em;
	color:#3a3a3a;
}

	input:focus, textarea:focus {
		border:1px solid #97d6eb;
	}

textarea {
	height:213px;
	font-family:Arial, Helvetica, sans-serif;
	background:url(images/textarea-bg.jpg) right no-repeat #efefef;
}

#submit {
	background:url(images/submit.jpg);
	width:127px;
	height:38px;
	text-indent:-9999px;
	border:none;
	margin-top:20px;
	cursor:pointer;
}

	#submit:hover {
		opacity:0.9;
	}

footer a img {
	border:none;
	float:right;
	margin:0px 59px 40px 0px;
}
    </style>
</head>

<body>
   
    <form method="post" id="contact-form" action="process.php" novalidate="novalidate">
        <ol>
            <li id="name-container">
                <label for="name">Your Name</label>                
                <input type="text" name="name" id="name" <?php if (isset($errors['name'])) { echo 'class="error"';}?> value="<?php echo $name; ?>" required="required"/>
                <?php if (isset($errors['name'])): ?><label class="error"><?php echo $errors['name']; ?></label><?php endif; ?>
            </li>
            <li id="email-container">
                <label for="email">Your Email</label>
                <input type="email" name="email" id="email" <?php if (isset($errors['email'])) { echo 'class="error"';}?> value="<?php echo $email; ?>" required="required"/>
                <?php if (isset($errors['email'])): ?><label class="error"><?php echo $errors['email']; ?></label><?php endif; ?>
            </li>
            <li id="message-container">
                <label for="message">Your Message</label>                
                <textarea rows="6" name="message" id="message" <?php if (isset($errors['message'])) { echo 'class="error"';}?> required="required"/><?php echo $message; ?></textarea>
                <?php if (isset($errors['message'])): ?><label class="error"><?php echo $errors['message']; ?></label><?php endif; ?>
            </li>
            <li id="submit-container">                            
                <p class="instruction"><span class="star">&#42;</span> All Fields Required</p>
                <input class="button" type="submit" name="send" value="Send" id="send"/>
                <span id="loading"></span>                
            </li>
        </ol>
        <div id="submit-message">
            <span class="<?php echo (isset($formOK) ? $responsetype : 'hidden'); ?>"><?php if(isset($formOK)) { echo $submitmessage; } ?></span>
        </div>
    </form>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/libs/jquery-1.8.3.min.js"><\/script>')</script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>
    <!--<![endif]-->
</body>

</html>