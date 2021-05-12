<?php
	include_once("../common.php");
	$_SESSION['sess_iAdminUserId'] = "";
	$_SESSION["sess_vAdminFirstName"] = "";
	$_SESSION["sess_vAdminLastName"] = "";
	$_SESSION["sess_vAdminEmail"] = "";
	$_SESSION["current_link"] = "";
	if($_SESSION["SessionUserType"]=='hotel')
	{
		$_SESSION["SessionUserType"] = "";   
		header("location:../hotel");
	}else{   
		header("location:index.php");
	}
	exit;
?>