# 1.0.0
First standalone Composer release of the DORN lab-integration module, extracted
from the bundled openemr/openemr copy. Single binary runs on OpenEMR 7.x, 8.0.x,
and flex/master via the `src/Compat/` shim layer (KernelCompat, OEGlobalsBagShim,
CryptoInterfaceShim, ServiceContainerShim, CsrfHelper). Resolves the OpenEMR
kernel through `Compat\KernelCompat::resolve()` instead of the flex-only
`OEGlobalsBag::getKernel()`, and reads stored secrets with `decryptStandard`
(present on every supported line).
