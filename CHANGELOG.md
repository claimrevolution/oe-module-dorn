# 1.0.2
- Fix the blank **Account Number** on the Connectivity tab. The API client now
  sends an `Accept: application/json` header, so bare-string lab-api endpoints
  (`Customer/v1/GetAccountNumber`) return quoted JSON (`"HLTH1"`) instead of
  `text/plain` (`HLTH1`), which `json_decode()` was turning into `null`. Other
  endpoints already returned JSON and are unaffected.
- Move the **Connectivity** tab to the end of the navbar (after Route List).

# 1.0.1
Adds operator-facing connectivity and contact features (mirrors ClaimRev Connect):
- Home page now pulls Support/Sales contact info from the ClaimRev public
  SupportInfo API (`ConnectorApi::getSupportInfo`), falling back to the previous
  static values if the call fails.
- New **Connectivity** tab (`public/connectivity.php` + `src/ConnectivityInfo.php`)
  showing the configured authority, client ID, scope, API server, token status,
  the account number (resolved from the new lab-api `Customer/v1/GetAccountNumber`
  endpoint), and the installed module version.
- No remote version/update check is performed; only the locally installed version
  is shown.

# 1.0.0
First standalone Composer release of the DORN lab-integration module, extracted
from the bundled openemr/openemr copy. Single binary runs on OpenEMR 7.x, 8.0.x,
and flex/master via the `src/Compat/` shim layer (KernelCompat, OEGlobalsBagShim,
CryptoInterfaceShim, ServiceContainerShim, CsrfHelper). Resolves the OpenEMR
kernel through `Compat\KernelCompat::resolve()` instead of the flex-only
`OEGlobalsBag::getKernel()`, and reads stored secrets with `decryptStandard`
(present on every supported line).
