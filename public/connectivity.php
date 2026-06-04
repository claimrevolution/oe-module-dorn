<?php

/**
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 *
 * @author    Brad Sharp <brad.sharp@claimrev.com>
 * @copyright Copyright (c) 2022-2026 Brad Sharp <brad.sharp@claimrev.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

declare(strict_types=1);

require_once __DIR__ . "/../../../../globals.php";

use OpenEMR\Common\Acl\AccessDeniedHelper;
use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Core\Header;
use OpenEMR\Modules\Dorn\Bootstrap;
use OpenEMR\Modules\Dorn\ConnectivityInfo;

$tab = "connectivity";

if (!AclMain::aclCheckCore('admin', 'users')) {
    AccessDeniedHelper::denyWithTemplate("ACL check failed for admin/users: DORN - Connectivity", xl("DORN - Connectivity"));
}

$installedVersion = Bootstrap::MODULE_VERSION;
?>
<!DOCTYPE html>
<html lang="">
<head>
    <?php Header::setupHeader(['opener']); ?>
    <title><?php echo xlt("DORN - Connectivity"); ?></title>
</head>
<body class="container-fluid">
    <div class="row">
        <div class="col">
            <?php require '../templates/navbar.php'; ?>
        </div>
    </div>
    <?php $connectivityInfo = new ConnectivityInfo(); ?>
    <div class="row">
        <div class="col">
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo xlt("Client Connection Information"); ?></h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li><?php echo xlt("Authority"); ?>: <?php echo text($connectivityInfo->client_authority); ?></li>
                        <li><?php echo xlt("Client ID"); ?>: <?php echo text($connectivityInfo->clientId); ?></li>
                        <li><?php echo xlt("Client Scope"); ?>: <?php echo text($connectivityInfo->client_scope); ?></li>
                        <li><?php echo xlt("API Server"); ?>: <?php echo text($connectivityInfo->api_server); ?></li>
                        <li><?php echo xlt("Account Number"); ?>: <?php echo text($connectivityInfo->accountNumber); ?></li>
                        <li><?php echo xlt("Token"); ?>: <?php echo $connectivityInfo->hasToken ? xlt("Yes") : xlt("No"); ?></li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo xlt("Module Version"); ?></h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li><?php echo xlt("Installed Version"); ?>: <strong><?php echo text($installedVersion); ?></strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
