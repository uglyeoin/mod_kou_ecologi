<?php

/**
 * @package     KindOfUseful.Module.Ecologi
 *
 * @copyright   (C) 2026 Kind of Useful. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Badge: a compact pill that links to the Ecologi profile, for footers and sidebars.
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use KindOfUseful\Module\Ecologi\Site\Helper\Icons;

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

if ($notice !== '') {
    echo '<div class="alert alert-warning">' . $e($notice) . '</div>';

    return;
}

$tag  = $showLink ? 'a' : 'div';
$attr = $showLink ? ' href="' . $e($profile) . '"' . ($newTab ? ' target="_blank" rel="noopener"' : '') : '';
?>
<div id="<?php echo $e($id); ?>" class="<?php echo $e($classes); ?> kou-eco--badge">
    <<?php echo $tag . $attr; ?> class="kou-eco-pill">
        <?php if ($showIcons) : ?>
            <svg class="kou-eco-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="<?php echo $e(Icons::PATHS[$stats[0]['kind']]); ?>"/></svg>
        <?php endif; ?>
        <span class="kou-eco-pill-text">
            <?php foreach ($stats as $i => $s) : ?>
                <?php echo $i ? '<span class="kou-eco-sep" aria-hidden="true">·</span>' : ''; ?>
                <span class="kou-eco-pill-stat"><strong class="kou-eco-value"<?php echo $animate ? ' data-kou-eco-count="' . $e((string) $s['value']) . '" data-kou-eco-dp="' . (int) $s['dp'] . '"' : ''; ?>><?php echo $e($s['number']); ?></strong><?php echo $s['unit'] !== '' ? $e($s['unit']) : ''; ?> <?php echo $e($s['label']); ?></span>
            <?php endforeach; ?>
            <?php if ($showLink) : ?>
                <span class="kou-eco-pill-by"><?php echo $e(Text::_('MOD_KOU_ECOLOGI_WITH_ECOLOGI')); ?></span>
                <?php if ($newTab) : ?><span class="visually-hidden"> <?php echo $e(Text::_('MOD_KOU_ECOLOGI_OPENS_NEW_TAB')); ?></span><?php endif; ?>
            <?php endif; ?>
        </span>
    </<?php echo $tag; ?>>
</div>
