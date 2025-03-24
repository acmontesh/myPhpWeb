<?php
require __DIR__ . '/../config/contactForm.php';
?>
<div class="contact-me about-info">
    <div class="header-contact-me">
        <h1>Contact Me</h1>
    </div>
    <div class="div-form-contact-me">
        <?php
        if (isset($_GET['errm'])) {
            $errCode     = filter_var($_GET['errm'],FILTER_SANITIZE_NUMBER_INT);
            $errMsg      = "";
            if ($errCode==0) {
                $errMsg     = "Message could not be sent. Please try again, or contact me directly through my email: acmontesh@gmail.com";        
            } elseif ($errCode==1) {
                $errMsg     = "Server error. Message could not be sent."; 
            } elseif ($errCode==2) {
                $errMsg     = "reCAPTCHA verification was not completed successfully. Please try again."; 
            }?>             
        <div class="form-error"><p><?php echo $errMsg ?></p></div>
        <?php } elseif( isset($_GET['stme']) ) { 
            $stMe     = filter_var($_GET['stme'],FILTER_SANITIZE_NUMBER_INT);
            if ($stMe==1) {
            ?>
            <div class="okmsg">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
                <p>Your message has been successfully sent. Thank you for reaching out.</p>
            </div>
                <?php  } 
                }?>
        <form onsubmit="handleSubmit(event)" class="formulario" method="POST" id="form-submit" enctype="multipart/form-data" >
            <fieldset>
                <legend>Contact Information</legend>
                <label for="name">Name</label>    
                <input type="text" name="name" id="name" placeholder="Your name" required>
                <label for="affiliation">Affiliation</label>    
                <input type="text" name="affiliation" id="affiliation" placeholder="Your affiliation">
                <label for="email">Email</label>    
                <input type="email" name="email" id="email" placeholder="Your email address" required>                
            </fieldset>
            <fieldset>
                <legend>Message</legend>
                <label for="mensaje">Message</label>    
                <textarea name="mensaje" id="mensaje" cols="30" rows="10" placeholder="Your message" required></textarea>
          
                <div id="contenedor-captcha">
                <div class="g-recaptcha" data-size='compact' data-sitekey="<?php echo $_ENV["CAPTCHA_SITE"] ?>"></div>
                </div>
    

            </fieldset>
            
            <input type="submit" name="submit" value="Send" class="boton-formulario">                                
        </form>
    </div>
    
</div> <!--Contact me-->
