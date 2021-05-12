<?php
include_once('../../../include_config.php');
$returnUrl = $_REQUEST['returnUrl'];
ini_set("display_errors", 1);
        error_reporting(E_ALL);
if (isset($_REQUEST["payStatus"]) && $_REQUEST["payStatus"] != "") {
    $status = isset($_REQUEST["payStatus"]) ? $_REQUEST["payStatus"] : 'Failed';
    if ($status == "Failed") {
        $redirectUrl = $returnUrl . "?success=0";
        ?>
        <script>window.location.replace("<?php echo $redirectUrl; ?>");</script>
        <?php
    } else {
        $redirectUrl = $returnUrl . "?success=1";
        //Insert Wallet Code Here
        $userId = $_REQUEST['iUserId'];
        $UserType =$_REQUEST["UserType"];
        $chargeAmt = $_REQUEST['amount'];
        /*if($UserType == "Passenger"){
            $UserType = "Rider";
        }*/
        $DebitAmt= $_REQUEST['DebitAmt'];
        $iTripId= $_REQUEST['iTripId'];
        $eForTip= $_REQUEST['eForTip'];

        $generalobj->InsertIntoUserWallet($userId, $UserType, $chargeAmt, 'Credit', 0, 'Deposit', '#LBL_AMOUNT_CREDIT#', 'Unsettelled', Date('Y-m-d H:i:s'));

        if($eForTip == 'Yes'){
            $user_available_balance = $generalobj->get_user_available_balance($userId,$UserType);
            $Tipcharge = $chargeAmt+$DebitAmt;
            //if($user_available_balance == $DebitAmt) {
                $where = " iTripId = '$iTripId'";
                $data['fTipPrice'] = $Tipcharge;
                $id = $obj->MySQLQueryPerform("trips", $data, 'update', $where);
                $vRideNo = $generalobj->get_value('trips', 'vRideNo', 'iTripId', $iTripId, '', 'true');
                $data_wallet['iUserId'] = $userId;
                $data_wallet['eUserType'] = $UserType;
                $data_wallet['iBalance'] = $Tipcharge;
                $data_wallet['eType'] = "Debit";
                $data_wallet['dDate'] = date("Y-m-d H:i:s");
                $data_wallet['iTripId'] = $iTripId;
                $data_wallet['eFor'] = "Booking";
                $data_wallet['ePaymentStatus'] = "Unsettelled";
                $data_wallet['tDescription'] = "#LBL_DEBITED_BOOKING#" . $vRideNo;

                $generalobj->InsertIntoUserWallet($data_wallet['iUserId'], $data_wallet['eUserType'], $data_wallet['iBalance'], $data_wallet['eType'], $data_wallet['iTripId'], $data_wallet['eFor'], $data_wallet['tDescription'], $data_wallet['ePaymentStatus'], $data_wallet['dDate']);
            //}
                        
        }        

        ?>
        <script>window.location.replace("<?php echo $redirectUrl; ?>");</script>
        <?php
        //echo "Transaction Successfully done";
        //die;
    }
} else {
    die;
}
?>