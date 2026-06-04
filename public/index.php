<?php

/**
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 *
 * @author    Brad Sharp <brad.sharp@claimrev.com>
 * @copyright Copyright (c) 2022-2025 Brad Sharp <brad.sharp@claimrev.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once __DIR__ . "/../../../../globals.php";

use OpenEMR\Core\Header;
use OpenEMR\Modules\Dorn\ConnectorApi;

$tab = "home";

// Pull support/contact info from the API, falling back to static defaults so the
// page always renders even if the remote call fails.
$contactInfo = ConnectorApi::getSupportInfo();
$contactInfo = is_array($contactInfo) ? $contactInfo : [];
$supportPhone = (isset($contactInfo['phone']) && is_string($contactInfo['phone']) && $contactInfo['phone'] !== '')
    ? $contactInfo['phone'] : '1-918-842-9564';
$supportEmail = (isset($contactInfo['supportEmail']) && is_string($contactInfo['supportEmail']) && $contactInfo['supportEmail'] !== '')
    ? $contactInfo['supportEmail'] : 'support@claimrev.com';
$salesEmail = (isset($contactInfo['salesEmail']) && is_string($contactInfo['salesEmail']) && $contactInfo['salesEmail'] !== '')
    ? $contactInfo['salesEmail'] : 'info@claimrev.com';

// ACL is covered by the menu item
?>
<!DOCTYPE html>
<html lang="">
<head>
    <?php Header::setupHeader(['opener']); ?>
    <title> <?php echo xlt("DORN Configuration"); ?>  </title>
</head>
<body class="container-fluid">
    <div class="row">
        <div class="col">
            <?php
            require '../templates/navbar.php';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h3><?php echo xlt("DORN Configuration"); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php
            require '../templates/contact.php';
            ?>
        </div>
    </div>
</body>
</html>
