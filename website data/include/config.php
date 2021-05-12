<?
$template = "";
if ($APP_TYPE == 'Ride-Delivery-UberX') {
    $template = 'cubejek';
}
if ($APP_TYPE == 'Ride') {
    $template = 'taxishark';
}
if (ONLYDELIVERALL == 'Yes') {
    $template = 'foodtemplate';
}
$template = strtolower($template) ?: "uber";
//$template = "uber";
$templatePath = "templates/" . $template . "/";
if (isset($_SESSION['sess_systype']) && $_SESSION['sess_systype'] == "ufxall") {

    $templatePath = "templates/rush/";
}
?> 