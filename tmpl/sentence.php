<?php

/**
 * @package     KindOfUseful.Module.Ecologi
 *
 * @copyright   (C) 2026 Kind of Useful. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Sentence: plain text, e.g. "We have planted 1,266 trees and offset 105.77 t CO₂e with Ecologi."
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

if ($notice !== '') {
    echo '<div class="alert alert-warning">' . $e($notice) . '</div>';

    return;
}

$parts = array_map(
    static fn (array $s): string => '<strong>' . $e($s['number']) . ($s['unit'] !== '' ? $e($s['unit']) : '') . '</strong> ' . $e($s['label']),
    $stats
);
$last = array_pop($parts);
$list = $parts ? implode(', ', $parts) . ' ' . $e(Text::_('MOD_KOU_ECOLOGI_AND')) . ' ' . $last : $last;
?>
<p id="<?php echo $e($id); ?>" class="<?php echo $e($classes); ?> kou-eco--sentence">
    <?php if ($intro !== '') : ?><?php echo $e($intro); ?> <?php endif; ?>
    <?php echo Text::sprintf('MOD_KOU_ECOLOGI_SENTENCE', $list); ?>
    <?php if ($showLink) : ?>
        <a href="<?php echo $e($profile); ?>"<?php echo $newTab ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo $e($linkText); ?><?php if ($newTab) : ?><span class="visually-hidden"> <?php echo $e(Text::_('MOD_KOU_ECOLOGI_OPENS_NEW_TAB')); ?></span><?php endif; ?></a>
    <?php endif; ?>
</p>
