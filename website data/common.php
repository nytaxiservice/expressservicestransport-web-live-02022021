<?php

/*
  Below included Licence file is set for your domain.
  Do not remove or change code of licence file.
  It will stop your website and apps.
  ## DO NOT MAKE CHANGED BELOW THIS LINE IN THIS FILE. IT MAY STOP YOUR WEBSITE OR APP OR MAKE THEM WORK DIFFERENTLY.
 */
include_once ('licence.php');
//Added By HJ On 28-08-2019 For Solved Aug - Sheet Issue #269 Start
if ($MAINTENANCE_WEBSITE == "Yes") {
    //$tsite_url = $tconfig['tsite_url'];
    //echo "<pre>";print_r($_SERVER['REQUEST_URI']);die;
    if (strpos($_SERVER['REQUEST_URI'], 'admin') !== false) {
        //Admin Panel Running
    } else if (!isset($_REQUEST['maintanance'])) {
        header("Location:maintanance?maintanance=yes");
    }
}
//Added By HJ On 28-08-2019 For Solved Aug - Sheet Issue #269 End
?>
