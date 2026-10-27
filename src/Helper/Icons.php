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
 * Decorative 24×24 icons for each kind of impact (always aria-hidden).
 *
 * @since  2.0.0
 */
final class Icons
{
    public const PATHS = [
        // A tree.
        'trees'              => 'M12 2a6 6 0 0 0-5.65 8.02A4.5 4.5 0 0 0 8.5 18H11v4h2v-4h2.5a4.5 4.5 0 0 0 2.15-7.98A6 6 0 0 0 12 2Z',
        // A cloud with a tick: emissions avoided.
        'carbonOffset'       => 'M19.35 10.04A7.49 7.49 0 0 0 12 4a7.5 7.5 0 0 0-6.65 4.04A6 6 0 0 0 6 20h13a5 5 0 0 0 .35-9.96ZM10.5 17 7 13.5l1.41-1.41 2.09 2.08 5.09-5.08L17 10.5 10.5 17Z',
        // A cloud with a down arrow: carbon taken out of the air.
        'carbonRemoval'      => 'M19.35 10.04A7.49 7.49 0 0 0 12 4a7.5 7.5 0 0 0-6.65 4.04A6 6 0 0 0 6 20h13a5 5 0 0 0 .35-9.96ZM12 18l-4-4h3V9h2v5h3l-4 4Z',
        // A flower: habitat restored.
        'habitatRestoration' => 'M12 22a1 1 0 0 1-1-1v-5.1A5 5 0 0 1 7 11V9.6A3 3 0 0 1 9 4a3 3 0 0 1 3-2 3 3 0 0 1 3 2 3 3 0 0 1 2 5.6V11a5 5 0 0 1-4 4.9V21a1 1 0 0 1-1 1Zm0-15a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z',
    ];
}
