<?php
/**
 * multiple.php is a postback application designed to provide a 
 * contact form for users to email our clients.  
 * 
 * multiple.php provides a larger form with more elements to provide 
 * a richer example form.
 *
 * @package nmCAPTCHA2
 * @author Bill & Sara Newman <williamnewman@gmail.com>
 * @version 1.01 2015/11/17
 * @link http://www.newmanix.com/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see contact_include.php 
 * @see recaptchalib.php
 * @see util.js 
 * @todo none
 */

#EDIT THE FOLLOWING:
$toAddress = "smbarajas14@gmail.com";  //place your/your client's email address here
$toName = "Sarah Barajas"; //place your client's name here
$website = "http://www.brujeriaconbarajas.dreamhosters.com/";  //place NAME of your client's website here
#--------------END CONFIG AREA ------------------------#
$sendEmail = TRUE; //if true, will send an email, otherwise just show user data.
$dateFeedback = true; //if true will show date/time with reCAPTCHA error - style a div with class of dateFeedback
include_once 'config.php'; #site keys go inside your config.php file
include 'contact-lib/contact_include.php'; #complex unsightly code moved here
$response = null;
$reCaptcha = new ReCaptcha($secretKey);
if (isset($_POST["g-recaptcha-response"]))
{// if submitted check response
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}
if ($response != null && $response->success)
    {#reCAPTCHA agrees data is valid (PROCESS FORM & SEND EMAIL)
        handle_POST($skipFields,$sendEmail,$toName,$fromAddress,$toAddress,$website,$fromDomain);             #Here we can enter the data sent into a database in a later version of this file
    ?>
    <!-- START HTML FEEDBACK -->
    <div class="contact-feedback">
        <p>Thank you for your feedback.</p>
        <p>We will contact you as soon as possible.</p>
    </div>    
    <!-- END HTML FEEDBACK -->        
    <?php
}else{#show form, either for first time, or if data not valid per reCAPTCHA 
    if($response != null && !$response->success)
    {
        $feedback = dateFeedback($dateFeedback);
        send_POSTtoJS($skipFields); #function for sending POST data to JS array to reload form elements
    }//end failure feedback
 
?>
	<!-- START HTML FORM -->
	<form action="<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post">
	<div>
		<label>
			Name:<br /><input type="text" name="Your Real Name" required="required" placeholder="Your Full Real Name (required)" title="This field is required" tabindex="10" size="44" autofocus />
		</label>
	</div>
	<div>	
		<label>
			Email:<br /><input type="email" name="Email" required="required" placeholder="Email (required)" title="Only a valid email" tabindex="20" size="44" />
		</label>
	</div>
	<!-- below change the HTML to your form elements - only 'Name' & 'Email' (above) are significant -->
	<div>	
		<label>
			How Did You Hear About Me?:<br />
			<select name="How_Did_You_Hear_About_Us?" required="required" title="How You Heard is required" tabindex="30">
				<option value="">Click Here</option>
				<option value="Facebook">Facebook</option>
                <option value="Instagram">Instagram</option>
                <option value="Twitter">Twitter</option>
				<option value="Web">Web</option>
				<option value="Friend">A Friend</option>
				<option value="Other">Other</option>
			</select>
		</label>
	</div>
	
	<div>	
		<fieldset>
			<legend>Which photography services are you interested in?</legend>
			<input type="checkbox" name="Interested_In[]" value="Portraits" tabindex="40" /> Portraits <br />
            <input type="checkbox" name="Interested_In[]" value="Event Photography" tabindex="40" /> Event Photography <br />
			<input type="checkbox" name="Interested_In[]" value="Weddings" /> Weddings <br />
			<input type="checkbox" name="Interested_In[]" value="Band Photos " /> Band Photos <br />
			<input type="checkbox" name="Interested_In[]" value="Family Photos" /> Family Photos <br />
            <input type="checkbox" name="Interested_In[]" value="Graphic Design" tabindex="40" /> Graphic Artwork <br />
            <input type="checkbox" name="Interested_In[]" value="Other" tabindex="40" /> Misc. <br />
		</fieldset>
	</div>
	
		<!-- <div>	
		<fieldset>
			<legend>Would you like to join our mailing list?</legend>
			<input type="radio" name="Join_Mailing_List?" value="Yes" 
			required="required" title="Mailing list is required" tabindex="50"  
			/> Yes <br />
			<input type="radio" name="Join_Mailing_List?" value="No" /> No <br />
		</fieldset>
	</div> -->
	<div>	
		<label>
			Comments:<br /><textarea name="We'd like to hear from you!" cols="36" rows="4" placeholder="Thanks for the feedback!" tabindex="60"></textarea>
		</label>
	</div>	
	<div><?=$feedback?></div>
    <div class="g-recaptcha" data-sitekey="<?=$siteKey;?>"></div>
	<div>
		<input type="submit" value="submit" />
	</div>
    </form>
	<!-- END HTML FORM -->
    <script type="text/javascript"
        src="https://www.google.com/recaptcha/api.js?hl=en">
    </script>
<?php
}
?>
