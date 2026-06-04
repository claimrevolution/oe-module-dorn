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

/**
 * @var string|null $supportPhone
 * @var string|null $supportEmail
 * @var string|null $salesEmail
 *
 * Values are normally supplied by index.php from ConnectorApi::getSupportInfo().
 * Fall back to static defaults if this template is included standalone or the
 * API call did not populate them.
 */
$supportPhone = (isset($supportPhone) && is_string($supportPhone) && $supportPhone !== '') ? $supportPhone : '1-918-842-9564';
$supportEmail = (isset($supportEmail) && is_string($supportEmail) && $supportEmail !== '') ? $supportEmail : 'support@claimrev.com';
$salesEmail = (isset($salesEmail) && is_string($salesEmail) && $salesEmail !== '') ? $salesEmail : 'info@claimrev.com';
$telDigits = preg_replace('/\D+/', '', $supportPhone) ?? '';
?>

<div class="card">
    <h6><?php echo xlt("Support/Sales"); ?></h6>
    <ul>
        <li><?php echo xlt("Call"); ?>: <a href="tel:<?php echo attr($telDigits); ?>"><?php echo text($supportPhone); ?></a></li>
        <li><?php echo xlt("Email Support"); ?>: <a href="mailto:<?php echo attr($supportEmail); ?>"><?php echo text($supportEmail); ?></a></li>
        <li><?php echo xlt("Email Sales"); ?>: <a href="mailto:<?php echo attr($salesEmail); ?>"><?php echo text($salesEmail); ?></a></li>
    </ul>
</div>
