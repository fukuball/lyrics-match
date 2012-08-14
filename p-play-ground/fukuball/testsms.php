<?php
require_once dirname(dirname(dirname(__FILE__)))."/p-config/application-setter.php";

//variable configuration
$host       = "hiapi.ext.hipaas.hinet.net";
$serviceid  = "14";
$isvid      = "a93ac44e86924497892b674d619c29fe";
$isvkey     = "FngenvssyLCMw+A1W8TeNg==";
$phone      = "0919095468";
$msg        = "testtest";

//get the token and sign
$a       = LMHelper::hiapi_get_auth($host);
$token   = $a[0];
$sign    = $a[1];

smstest();

?>