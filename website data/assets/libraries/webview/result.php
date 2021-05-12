<?php
die;
$status = isset($_REQUEST["payStatus"]) ? $_REQUEST["payStatus"] : 'Failed';
if ($status == "Failed") {
    echo "Transaction failed";
    die;
} else {
    echo "Transaction Successfully done";
    die;
}
?>