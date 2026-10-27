<?php

/**
 * @package     KindOfUseful.Module.Ecologi
 *
 * @copyright   (C) 2026 Kind of Useful. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace KindOfUseful\Module\Ecologi\Site\Helper;

use Joomla\CMS\Http\HttpFactory;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Reads a public Ecologi profile's impact from Ecologi's public (keyless) reporting API:
 * GET https://public.ecologi.com/users/{username}/impact
 *
 * @since  2.0.0
 */
final class Impact
{
    public const ENDPOINT = 'https://public.ecologi.com/users/%s/impact';

    /** The four kinds of impact Ecologi reports, in display order. */
    public const KINDS = ['trees', 'carbonOffset', 'carbonRemoval', 'habitatRestoration'];

    /** Ecologi usernames are the last part of the profile address (ecologi.com/username). */
    public static function username(string $input): string
    {
        $input = trim($input);

        if (preg_match('#ecologi\.com/(?:users/)?([A-Za-z0-9_.-]+)#i', $input, $m)) {
            $input = $m[1];
        }

        return preg_match('/^[A-Za-z0-9_.-]{1,100}$/', $input) ? $input : '';
    }

    /**
     * @return  array  Raw decoded JSON.
     *
     * @throws  \RuntimeException
     */
    public static function fetch(string $username): array
    {
        try {
            $response = HttpFactory::getHttp()->get(
                \sprintf(self::ENDPOINT, rawurlencode($username)),
                ['Accept' => 'application/json'],
                8
            );
        } catch (\Throwable $e) {
            throw new \RuntimeException('Ecologi could not be reached: ' . $e->getMessage());
        }

        $body = json_decode((string) $response->getBody(), true);
        $code = $response->getStatusCode();

        if ($code === 404) {
            throw new \RuntimeException('Ecologi says: ' . ($body['error'] ?? 'user not found or private'));
        }

        if ($code !== 200 || !\is_array($body)) {
            throw new \RuntimeException('Ecologi answered HTTP ' . $code);
        }

        return $body;
    }

    /**
     * Normalises the API answer to the four totals, optionally including impact that is
     * paid for but not yet delivered ("pending").
     *
     * @return  array<string, float>
     */
    public static function parse(array $json, bool $includePending = false): array
    {
        $out = [];

        foreach (self::KINDS as $kind) {
            $value = (float) ($json[$kind] ?? 0);

            if ($includePending) {
                $value += (float) ($json['pending'][$kind] ?? 0);
            }

            $out[$kind] = max(0.0, $value);
        }

        return $out;
    }
}
