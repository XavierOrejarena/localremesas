<?php
// 2 hours in seconds
$inactive = 3600; 
ini_set('session.gc_maxlifetime', $inactive); // set the session max lifetime to 2 hours

// session_start();

if (isset($_SESSION['time']) && (time() - $_SESSION['time'] > $inactive)) {
    // last request was more than 2 hours ago
    session_unset();     // unset $_SESSION variable for this page
    session_destroy();   // destroy session data
}
$_SESSION['time'] = time(); // Update session

?>