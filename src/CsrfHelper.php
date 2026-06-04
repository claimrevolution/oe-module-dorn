<?php

/**
 * CSRF helper that adapts to either OpenEMR 7.x or 8.x at runtime.
 *
 * OpenEMR 8.x changed CsrfUtils::collectCsrfToken / verifyCsrfToken to take
 * a SessionInterface in the first slot. OpenEMR 7.x used ($subject, ?$session)
 * with the session optional. This helper reflects on the active CsrfUtils
 * signature and dispatches correctly so the same caller code works on both.
 *
 * @package   OpenEMR
 * @link      https://www.open-emr.org
 * @author    Brad Sharp <brad.sharp@claimrev.com>
 * @copyright Copyright (c) 2026 Brad Sharp <brad.sharp@claimrev.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

declare(strict_types=1);

namespace OpenEMR\Modules\Dorn;

use OpenEMR\Common\Csrf\CsrfUtils;
use ReflectionMethod;

final class CsrfHelper
{
    /** @var bool|null cached result of signature detection */
    private static ?bool $usesSessionFirst = null;

    /**
     * Detect whether CsrfUtils::collectCsrfToken expects SessionInterface as
     * the first parameter (OpenEMR 8.x) or subject string (OpenEMR 7.x).
     */
    private static function usesSessionFirst(): bool
    {
        if (self::$usesSessionFirst === null) {
            $ref = new ReflectionMethod(CsrfUtils::class, 'collectCsrfToken');
            $params = $ref->getParameters();
            $firstParam = $params[0] ?? null;
            if ($firstParam !== null) {
                $type = $firstParam->getType();
                // OpenEMR 8.x: first param is SessionInterface (not a string).
                self::$usesSessionFirst = $type instanceof \ReflectionNamedType
                    && !$type->isBuiltin()
                    && $firstParam->getName() === 'session';
            } else {
                self::$usesSessionFirst = false;
            }
        }
        return self::$usesSessionFirst;
    }

    /**
     * Return the active session on 8.x, null on 7.x.
     */
    private static function getSession(): mixed
    {
        if (class_exists(\OpenEMR\Common\Session\SessionWrapperFactory::class)) {
            return \OpenEMR\Common\Session\SessionWrapperFactory::getInstance()->getActiveSession();
        }
        return null;
    }

    public static function collectCsrfToken(string $subject = 'default'): string
    {
        if (self::usesSessionFirst()) {
            return CsrfUtils::collectCsrfToken(self::getSession(), $subject);
        }
        return CsrfUtils::collectCsrfToken($subject);
    }

    public static function verifyCsrfToken(string $token, string $subject = 'default'): bool
    {
        if (self::usesSessionFirst()) {
            return CsrfUtils::verifyCsrfToken($token, self::getSession(), $subject);
        }
        return CsrfUtils::verifyCsrfToken($token, $subject);
    }

    public static function checkCsrfInput(
        int $inputType,
        string $key = 'csrf_token_form',
        string $subject = 'default',
        bool $dieOnFail = false,
    ): void {
        // checkCsrfInput is a master/flex-only convenience method. The 8.0.x
        // patch line and 7.0.4 only expose collectCsrfToken/verifyCsrfToken/
        // csrfNotVerified, so calling it there is a fatal "undefined method".
        // Prefer the native method when present, otherwise replicate it from
        // primitives that exist on every supported line.
        if (method_exists(CsrfUtils::class, 'checkCsrfInput')) {
            if (self::usesSessionFirst()) {
                CsrfUtils::checkCsrfInput($inputType, self::getSession(), $key, $subject, $dieOnFail);
                return;
            }
            CsrfUtils::checkCsrfInput($inputType, $key, $subject, $dieOnFail);
            return;
        }

        $token = filter_input($inputType, $key, FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR);
        if (is_string($token) && self::verifyCsrfToken($token, $subject)) {
            return;
        }
        if ($dieOnFail) {
            CsrfUtils::csrfNotVerified();
        }
        throw new \RuntimeException('CSRF token validation failed');
    }
}
