<?php

/**
 * @package     KindOfUseful.Module.Ecologi
 *
 * @copyright   (C) 2026 Kind of Useful. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace KindOfUseful\Module\Ecologi\Site\Helper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Keeps the last good answer from a remote service in Joomla's cache folder.
 *
 * A fresh copy is used as is. When it is out of date the service is asked again; if that
 * fails, the old copy is shown and the next try waits a few minutes, so a slow or broken
 * service never takes the page down with it. System → Clear Cache empties it.
 *
 * @since  2.0.0
 */
final class RemoteCache
{
    private const RETRY_AFTER = 600;

    public function __construct(private string $folder)
    {
    }

    /**
     * @param   callable  $fetch  Returns the data, or throws \RuntimeException.
     *
     * @return  array{data: mixed, error: string}  data is null when nothing is available.
     */
    public function remember(string $key, int $ttl, callable $fetch): array
    {
        $file   = $this->folder . '/' . sha1($key) . '.json';
        $cached = $this->read($file);

        if ($cached !== null && $cached['expires'] > time()) {
            return ['data' => $cached['data'], 'error' => ''];
        }

        try {
            $data = $fetch();
            $this->write($file, $data, time() + max(60, $ttl));

            return ['data' => $data, 'error' => ''];
        } catch (\RuntimeException $e) {
            if ($cached !== null) {
                $this->write($file, $cached['data'], time() + self::RETRY_AFTER);
            }

            return ['data' => $cached['data'] ?? null, 'error' => $e->getMessage()];
        }
    }

    private function read(string $file): ?array
    {
        if (!is_file($file)) {
            return null;
        }

        $json = json_decode((string) @file_get_contents($file), true);

        return \is_array($json) && \array_key_exists('data', $json) ? $json + ['expires' => 0] : null;
    }

    private function write(string $file, $data, int $expires): void
    {
        if (!is_dir($this->folder) && !@mkdir($this->folder, 0755, true) && !is_dir($this->folder)) {
            return;
        }

        $tmp = $file . '.' . bin2hex(random_bytes(4));

        if (@file_put_contents($tmp, json_encode(['expires' => $expires, 'data' => $data])) !== false) {
            @rename($tmp, $file);
        }

        @unlink($tmp);
    }
}
