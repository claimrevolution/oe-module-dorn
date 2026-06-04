<?php

/**
 * Connectivity information for the DORN module.
 *
 * Gathers the configured client connection settings and, when the module is
 * configured, attempts to authenticate and resolve the account number from the
 * DORN lab-api. All remote work is best-effort: any failure leaves the
 * corresponding field at its default so the Connectivity page still renders.
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 * @author    Brad Sharp <brad.sharp@claimrev.com>
 * @copyright Copyright (c) 2022-2026 Brad Sharp <brad.sharp@claimrev.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

declare(strict_types=1);

namespace OpenEMR\Modules\Dorn;

use OpenEMR\Modules\Dorn\Compat\KernelCompat;

class ConnectivityInfo
{
    public string $client_authority = '';
    public string $clientId = '';
    public string $client_scope = '';
    public string $api_server = '';
    public bool $hasToken = false;
    public string $accountNumber = '';

    public function __construct()
    {
        try {
            $bootstrap = new Bootstrap(KernelCompat::resolve()->getEventDispatcher());
            $globalsConfig = $bootstrap->getGlobalConfig();

            $clientId = $globalsConfig->getClientId();
            $this->clientId = is_string($clientId) ? $clientId : '';
            $this->client_authority = (string) $globalsConfig->getClientAuthority();
            $this->client_scope = (string) $globalsConfig->getClientScope();
            $this->api_server = (string) $globalsConfig->getApiServer();
        } catch (\Throwable) {
            return;
        }

        try {
            $token = ConnectorApi::getAccessToken();
            $this->hasToken = is_string($token) && $token !== '';
            if ($this->hasToken) {
                $this->accountNumber = ConnectorApi::getAccountNumber();
            }
        } catch (\Throwable) {
            // Leave hasToken/accountNumber at their defaults on any failure.
        }
    }
}
