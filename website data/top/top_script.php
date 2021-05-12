<?php

//print_r($_REQUEST);

if (isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'yes') {
    $_SESSION['edita'] = 1;
}

if (isset($_REQUEST['edit']) && $_REQUEST['edit'] == 'no') {
    //setcookie('edit', $cookie_value, time() - (86400 * 30));
    unset($_SESSION['edit']);
    $_SESSION['edita'] = "";
}

include_once("include/config.php");
include($templatePath . "top/top_script.php");
?>
<script>
    var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    document.cookie = "vUserDeviceTimeZone=" + timezone;
</script>
<?= $GOOGLE_ANALYTICS; ?>
<?php

//added by SP on 29-06-2019 for disallow other css and apply css which are given by ckeditor
$filename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
if ($filename == 'Page-Not-Found' || $filename == 'about' || $filename == 'help-center' || $filename == 'terms-condition' || $filename == 'how-it-works' || $filename == 'trust-safty-insurance' || $filename == 'privacy-policy' || $filename == 'legal') {
    ?>

    <script>
        $(document).ready(function () {
            $(".static-page ol li").each(function (index) {
                $(this).attr('data-number', index + 1);
            });
        })
    </script>

    <style>
        strong {
            font-weight : bold;
        }
        em {
            font-style : italic;
        }
        u {
            text-decoration: underline;
        }
        s {
            text-decoration: line-through;
        }
        .static-page ol li:before {
            content: attr(data-number);
            position: absolute;
            left: 0;
            font-size: 14px;
            font-weight: 600;
        }
        .static-page ol li {
            background:none;
            position:relative;
        }
    </style>
<?php } ?>