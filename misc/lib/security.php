<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//performs a few security checks
function securityCheck($path_parts, $yourURL, $form = false) {
    $passed = true; // start off thinking everything is good until a test fails
    
    $debug = false;
    
    //not completly safe
    if (isset($_SERVER['HTTP_REFERER'])) {
        $link = htmlentities($_SERVER['HTTP_REFERER'], ENT_QUOTES, "UTF-8");
        $url = parse_url($link);
        $fromPage = "//"  . $url['host'] . $url['path'];
    } else {
        $fromPage = "noplace";
    }
    
    // add all your page names to this array
    $whiteListPages = array();
    $whiteListPages[] = "index.php";
    $whiteListPages[] = "tables.php";
    $whiteListPages[] = "top.php";
    $whiteListPages[] = "footer.php";
    $whiteListPages[] = "nav.php";
    $whiteListPages[] = "header.php";
    $whiteListPages[] = "skihard.php";
    $whiteListPages[] = "snowboardhard.php";
    $whiteListPages[] = "otherhard.php";
    $whiteListPages[] = "softgoods.php";
    $whiteListPages[] = "skis.php";
    $whiteListPages[] = "poles.php";
    $whiteListPages[] = "bindings.php";
    $whiteListPages[] = "skiboots.php";
    $whiteListPages[] = "board.php";
    $whiteListPages[] = "helmets.php";
    $whiteListPages[] = "goggles.php";
    $whiteListPages[] = "boardbindings.php";
    $whiteListPages[] = "boardboots.php";
    $whiteListPages[] = "jackets.php";
    $whiteListPages[] = "gloves.php";
    $whiteListPages[] = "pants.php";
    $whiteListPages[] = "othersoft.php";
    $whiteListPages[] = "register.php";
    $whiteListPages[] = "sellSoft.php";
    $whiteListPages[] = "sellHard.php";
    $whiteListPages[] = "about.php";
    $whiteListPages[] = "users.php";
    

    
    
    
    
    
    //add all the folders to this array
    $whiteListFolders = array();
    $whiteListFolders[] = "/SkiSwap";
    $whiteListFolders[] = "/SkiSwap/misc";
    $whiteListFolders[] = "/SkiSwap/misc/css";
    $whiteListFolders[] = "/SkiSwap/misc/img";
    $whiteListFolders[] = "/SkiSwap/misc/lib";
    $whiteListFolders[] = "/SkiSwap/misc/sql";
    $whiteListFolders[] = "/SkiSwap/lib";
    $whiteListFolders[] = "/SkiSwap/bin";
    $whiteListFolders[] = "/SkiSwap/img";
    
    
    
    
    
    
    
    
    // Check for valid page name
    if (!in_array($path_parts['basename'], $whiteListPages)) {
        $passed = false;
        $errorMsg[] = "<p>Failed white list pages check: " . $path_parts['basename'] . "</p>";
    }
    // Check for valid folder name
    if (!in_array($path_parts['dirname'], $whiteListFolders)) {
        $passed = false;
        $errorMsg[] = "<p>Failed white list folders check: " . $path_parts['dirname'] . "</p>";
    }
    // Check server
    $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, "UTF-8");
    if ($server != get_current_user() . ".w3.uvm.edu") {
        $passed = false;
        $errorMsg[] = "<p>Failed server check: " . $server . "</p>";
    }
    // when it is a form page check to make sure it submitted to itself
    if ($form) { 
        $errorMsg[] = "<p>From: " . $fromPage . " should match your Url: " . $yourURL;
        if ($fromPage != $yourURL) {
            $passed = false;
            $errorMsg[] = "<p>Failed from page check" . $path_parts['dirname'] . "</p>";
        }
    }
    $errorMsg[] = "<p>returning: " . $passed;
    $message = join("\n", $errorMsg);
    if ($debug) {
        print $message;
        print "<p>path_parts<pre>";
        print_r($path_parts);
        print "</pre></p>";
        print "<p>white list pages<pre>";
        print_r($whiteListPages);
        print "</pre></p>";
        print "<p>white list folder<pre>";
        print_r($whiteListFolders);
        print "</pre></p>";
    }
    
    if (!$passed) {
        //send message to me
        $message = "<p>Login failed: " . date("F j, Y") . " at " . date("h:i:s") . "</p>\n" . $message;
        $to = ADMIN_EMAIL;
        $cc = "";
        $bcc = "";
        $from = "Site Login <security@uvm.edu>";
        $subject = "Login Status ";
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    }
    return $passed;
}
?>
