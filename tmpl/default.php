<?php

/**
 * @package     KindOfUseful.Module.Ecologi
 *
 * @copyright   (C) 2026 Kind of Useful. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Counters: one card per kind of impact, with a link to the Ecologi profile.
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use KindOfUseful\Module\Ecologi\Site\Helper\Icons;

/** @var string $notice  from the Dispatcher */
/** @var array  $stats */

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

if ($notice !== '') {
    echo '<div class="alert alert-warning">' . $e($notice) . '</div>';

    return;
}
?>
<div id="<?php echo $e($id); ?>" class="<?php echo $e($classes); ?> kou-eco--cards">
    <?php if ($intro !== '') : ?>
        <p class="kou-eco-intro"><?php echo $e($intro); ?></p>
    <?php endif; ?>
    <ul class="kou-eco-stats">
        <?php foreach ($stats as $s) : ?>
            <li class="kou-eco-stat kou-eco-stat--<?php echo $e($s['kind']); ?>">
                <?php if ($showIcons) : ?>
                    <svg class="kou-eco-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="<?php echo $e(Icons::PATHS[$s['kind']]); ?>"/></svg>
                <?php endif; ?>
                <span class="kou-eco-value"<?php echo $animate ? ' data-kou-eco-count="' . $e((string) $s['value']) . '" data-kou-eco-dp="' . (int) $s['dp'] . '"' : ''; ?>><?php echo $e($s['number']); ?></span><?php if ($s['unit'] !== '') : ?><span class="kou-eco-unit"><?php echo $e($s['unit']); ?></span><?php endif; ?>
                <span class="kou-eco-label"><?php echo $e($s['label']); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php if ($showLink) : ?>
        <p class="kou-eco-link"><a href="<?php echo $e($profile); ?>"<?php echo $newTab ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo $e($linkText); ?><?php if ($newTab) : ?><span class="visually-hidden"> <?php echo $e(Text::_('MOD_KOU_ECOLOGI_OPENS_NEW_TAB')); ?></span><?php endif; ?></a></p>
    <?php endif; ?>
</div>
