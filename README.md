# OpenEMR DORN Lab Integration Module

Connects OpenEMR to the DORN (Diagnostic Ordering Result Network) to send lab
orders (HL7) and receive results.

## Install

    composer require claimrevolution/oe-module-dorn

Then enable it under **Modules > Manage Modules**, and run
**Setup > Run Upgrade** for the module.

## Compatibility

Single binary supporting OpenEMR 7.x, 8.0.x, and flex/master via the runtime
shims in `src/Compat/`.

## Configuration

Settings live under **Administration > Globals > "DORN Lab Integration"**
(Client ID / Secret / environment supplied by ClaimRev).
