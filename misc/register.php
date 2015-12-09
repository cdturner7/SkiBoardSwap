<?php
include "top.php";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.

$debug = false;

if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form
$firstName = "";
$lastName = "";
$email = "twhitake@uvm.edu";
$street = "";
$city = "";
$state = "";
$zip = "";
$gender = "Male";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;
$streetERROR = false;
$cityERROR = false;
$stateERROR = false;
$zipERROR = false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// array used to hold form values that will be written to a CSV file
$dataRecord = array();

$mailed = false; // have we mailed the information to the user?
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2a Security
    // 
    if (!securityCheck($path_parts, $yourURL, true)) {
        $msg = "<p>Sorry you cannot access this page. ";
        $msg.= "Security breach detected and reported</p>";
        die($msg);
    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2b Sanitize (clean) data 
    // remove any potential JavaScript or html code from users input on the
    // form. Note it is best to follow the same order as declared in section 1c.

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;

    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $lastName;
    
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;

    $street = htmlentities($_POST["txtStreet"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $street;
    
    $city = htmlentities($_POST["txtCity"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $city;
    
    $state = htmlentities($_POST["txtState"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $state;
    
    $zip = htmlentities($_POST["txtZip"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $zip;
    
    $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $gender;

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2c Validation
    //
    // Validation section. Check each value for possible errors, empty or
    // not what we expect. You will need an IF block for each element you will
    // check (see above section 1c and 1d). The if blocks should also be in the
    // order that the elements appear on your form so that the error messages
    // will be in the order they appear. errorMsg will be displayed on the form
    // see section 3b. The error flag ($emailERROR) will be used in section 3c.

    if ($firstName == "") {
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your first name appears to have extra character.";
        $firstNameERROR = true;
    }
    
    if ($lastName == "") {
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = "Your last name appears to have an extra character.";
        $lastNameERROR = true;
    }

    if ($email == "") {
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;
    }
    
    if ($street == "") {
        $errorMsg[] = "Please enter your street";
        $streetERROR = true;
    } elseif (!verifyAlphaNum($street)) {
        $errorMsg[] = "Your street appears to have an extra character.";
        $streetERROR = true;
    }
    
    if ($city == "") {
        $errorMsg[] = "Please enter your city";
        $streetERROR = true;
    } elseif (!verifyAlphaNum($city)) {
        $errorMsg[] = "Your city appears to have an extra character.";
        $streetERROR = true;
    }
    
    if ($state == "") {
        $errorMsg[] = "Please enter your state";
        $stateERROR = true;
    } elseif (!verifyAlphaNum($state)) {
        $errorMsg[] = "Your state appears to have extra character.";
        $stateERROR = true;
    }
    
    if ($zip == "") {
        $errorMsg[] = "Please enter your zip code";
        $zipERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = "Your zip code appears to have extra character.";
        $zipERROR = true;
    }
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2d Process Form - Passed Validation
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //
    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2e Save Data
        //
        // This block saves the data to a CSV file.

//////////THIS IS WHERE WE CONNECT TO DATABASE/////////////////////////

        $query = 'INSERT INTO tblPerson SET fldFirstName = ?, fldLastName = ?, '
                . 'fldGender = ?, fldEmail = ?, fldAddress = ?, fldCity = ?,'
                . 'fldState = ?, fldZip = ?';
        $data = array($firstName, $lastName, $gender, $email, $street, $city, $state, $zip);
        $results = $thisDatabaseWriter->insert($query, $data);
 
        
        
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2f Create message
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (section 2g).

        $message = '<h2>Success!</h2>'
                . '<p> We have recieved your information. If there is a change in your '
                . 'info, feel free to re-submit any changes. Thanks!</p>';

        foreach ($_POST as $key => $value) {

            $message .= "<p>";

            $camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));

            foreach ($camelCase as $one) {
                $message .= $one . " ";
            }
            $message .= " = " . htmlentities($value, ENT_QUOTES, "UTF-8") . "</p>";
        }


        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2g Mail to user
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f.
        $to = $email; // the person who filled out the form
        $cc = "";
        $bcc = "";
        $from = "Ski Swap <noreply@yoursite.com>";

        // subject of mail should make sense to your form
        $todaysDate = strftime("%x");
        $subject = "Thanks for your help!" . $todaysDate;

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    } // end form is valid
} // ends if form was submitted.
//
//#############################################################################
//
// SECTION 3 Display Form
//
?>

<article id="main">

    <?php
//####################################
//
// SECTION 3a.
//
// 
// 
// 
// If its the first time coming to the form or there are errors we are going
// to display the form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print "<h1>Your Request has ";

        if (!$mailed) {
            print "not ";
        }

        print "been processed</h1>";

        print "<p>A copy of this message has been sent to:";
        if (!$mailed) {
            print "not ";
        }
        print "<p> $email </p>";
        print "<p>Mail Message:</p>";

        print $message;
    } else {


        //####################################
        //
        // SECTION 3b Error Messages
        //
        // display any error messages before we print out the form

        if ($errorMsg) {
            print '<div id="errors">';
            print "<ol>\n";
            foreach ($errorMsg as $err) {
                print "<li>" . $err . "</li>\n";
            }
            print "</ol>\n";
            print '</div>';
        }


        //####################################
        //
        // SECTION 3c html Form
        //
        /* Display the HTML form. note that the action is to this same page. $phpSelf
          is defined in top.php
          NOTE the line:

          value="<?php print $email; ?>

          this makes the form sticky by displaying either the initial default value (line 35)
          or the value they typed in (line 84)

          NOTE this line:

          <?php if($emailERROR) print 'class="mistake"'; ?>

          this prints out a css class so that we can highlight the background etc. to
          make it stand out that a mistake happened here.

         */
        ?>

        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmRegister">

            <fieldset class="wrapper">
                <p class="content">Register with us to sell your gear!</p>

                <fieldset id="wrapperTwo">

                    <fieldset class="contact">
                        <h2 id="space">Please complete the following form</h2>
                        <label for="txtFirstName" class="required">First Name
                            <input type="text" id="txtFirstName" name="txtFirstName"
                                   value="<?php print $firstName; ?>"
                                   tabindex="100" maxlength="45" placeholder="Enter your first name"
                                   <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>
                        
                        <label for="txtLastName" class="required">Last Name
                            <input type="text" id="txtLastName" name="txtLastName"
                                   value="<?php print $lastName; ?>"
                                   tabindex="100" maxlength="45" placeholder="Enter your last name"
                                   <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>
                        
                        
                        <h4>What is your gender?</h4>
                        <label><input type="radio" 
                                      id="radGenderMale" 
                                      name="radGender" 
                                      value="Male"
                                      <?php if ($gender == "Male") print 'checked' ?>
                                      tabindex="330">Male</label>
                        <label><input type="radio" 
                                      id="radGenderFemale" 
                                      name="radGender" 
                                      value="Female"
                                      <?php if ($gender == "Female") print 'checked' ?>
                                      tabindex="340">Female</label>
                        <label><input type="radio" 
                                      id="radGenderOther" 
                                      name="radGender" 
                                      value="Other"
                                      <?php if ($gender == "Other") print 'checked' ?>
                                      tabindex="350">Other</label>
                                                

                        <label for="txtEmail" class="required">Email
                            <input type="text" id="txtEmail" name="txtEmail"
                                   value="<?php print $email; ?>"
                                   tabindex="120" maxlength="45" placeholder="Enter a valid email address"
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()" 
                                   >
                        </label>
                        
                        <label for="txtStreet" class="required">Street
                            <input type="text" id="txtStreet" name="txtStreet"
                                   value="<?php print $street; ?>"
                                   tabindex="130" maxlength="45" placeholder="Enter a valid street address"
                                   <?php if ($streetERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()" 
                                   >
                        </label>
                        
                        <label for="txtCity" class="required">City
                            <input type="text" id="txtCity" name="txtCity"
                                   value="<?php print $city; ?>"
                                   tabindex="140" maxlength="45" placeholder="Enter a valid city"
                                   <?php if ($cityERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()" 
                                   >
                        </label>
                        
                        <label for="txtState" class="required">State
                            <input type="text" id="txtState" name="txtState"
                                   value="<?php print $street; ?>"
                                   tabindex="150" maxlength="45" placeholder="Enter a valid state"
                                   <?php if ($stateERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()" 
                                   >
                        </label>
                        
                        <label for="txtZip" class="required">Zip Code
                            <input type="text" id="txtZip" name="txtZip"
                                   value="<?php print $zip; ?>"
                                   tabindex="160" maxlength="45" placeholder="Enter a valid zip code"
                                   <?php if ($zipERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()" 
                                   >
                        </label>

                    </fieldset>



                    <fieldset class="buttons">
                        <legend></legend>
                        <input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="900" class="button">
                    </fieldset> <!-- ends buttons -->
                </fieldset> <!-- ends wrapper Two -->
            </fieldset> <!-- Ends Wrapper -->
        </form>

        <?php
    } // end body submit
    ?>

</article>

<?php include "footer.php"; ?>

</body>
</html>