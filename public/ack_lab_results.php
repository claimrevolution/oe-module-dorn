<?php

/**
 *
 * @package OpenEMR
 * @link    https://www.open-emr.org
 *
 * @author    Brad Sharp <brad.sharp@claimrev.com>
 * @copyright Copyright (c) 2022-2025 Brad Sharp <brad.sharp@claimrev.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

    require_once __DIR__ . "/../../../../globals.php";

    use OpenEMR\Common\Acl\AccessDeniedHelper;
    use OpenEMR\Common\Acl\AclMain;
    use OpenEMR\Modules\Dorn\CsrfHelper;
    use OpenEMR\Core\Header;
    use OpenEMR\Modules\Dorn\ConnectorApi;

if (!empty($_GET)) {
    CsrfHelper::checkCsrfInput(INPUT_GET, dieOnFail: true);
}

if (!empty($_POST)) {
    CsrfHelper::checkCsrfInput(INPUT_POST, dieOnFail: true);
}

if (!AclMain::aclCheckCore('admin', 'users')) {
    AccessDeniedHelper::denyWithTemplate("ACL check failed for admin/users: Acknowledge Lab Results", xl("Acknowledge Lab Results"));
}

$resultsGuid = $_REQUEST['resultGuid'] ?? '';
$rejectResults = $_REQUEST['rejectResults'] ?? '';
if (empty($rejectResults)) {
    $rejectResults = false;
}

$rejectResults = $rejectResults == "true" ? true : false;
$ackError = '';
if ($resultsGuid) {
    $ackResponse = ConnectorApi::sendAck($resultsGuid, $rejectResults, null);
    // getData/postData returns "" on a transport/HTTP failure; flag an error on a
    // non-object return or an explicit isSuccess === false so we don't tell the
    // user the lab was notified when it wasn't.
    $ackFailed = ($ackResponse === "" || $ackResponse === false || $ackResponse === null)
        || (is_object($ackResponse) && property_exists($ackResponse, 'isSuccess') && !$ackResponse->isSuccess);
    if ($ackFailed) {
        $ackError = (is_object($ackResponse) && !empty($ackResponse->responseMessage))
            ? $ackResponse->responseMessage
            : xl('Could not reach the lab service — the acknowledgement may not have been sent.');
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <?php Header::setupHeader(['opener']);?>
        <title><?php echo xlt("Alert"); ?></title>
    </head>
    <body>
    <?php
    if ($ackError !== '') {
        ?>
        <div class="alert alert-danger" role="alert"><?php echo text($ackError); ?></div>
        <?php
    } elseif ($rejectResults == true) {
        ?>
        <h3><?php echo xlt("Results Rejected"); ?></h3>
        <?php
    } else {
        ?>
        <h3><?php echo xlt("Results Accepted"); ?></h3>
        <?php
    }
    ?>
    </body>
</html>
