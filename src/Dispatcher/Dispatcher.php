<?php

/**
 * @package     KindOfUseful.Module.Ecologi
 *
 * @copyright   (C) 2026 Kind of Useful. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace KindOfUseful\Module\Ecologi\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Language\Text;
use KindOfUseful\Module\Ecologi\Site\Helper\Impact;
use KindOfUseful\Module\Ecologi\Site\Helper\RemoteCache;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Fetches (and caches) an Ecologi profile's impact and turns it into counters.
 *
 * @since  2.0.0
 */
class Dispatcher extends AbstractModuleDispatcher
{
    /** Option name => [impact kind, language key stem, unit, decimals]. */
    private const SHOW = [
        'show_trees'   => ['trees', 'TREES', '', 0],
        'show_carbon'  => ['carbonOffset', 'CARBON', '', 2],
        'show_removal' => ['carbonRemoval', 'REMOVAL', '', 2],
        'show_habitat' => ['habitatRestoration', 'HABITAT', '', 0],
    ];

    /** @return array|false */
    protected function getLayoutData()
    {
        $data     = parent::getLayoutData();
        $params   = $data['params'];
        $module   = $data['module'];
        $username = Impact::username((string) $params->get('username', ''));
        $data['notice'] = '';
        $data['stats']  = [];

        if ($username === '') {
            return $this->noticeOnly($data, Text::_('MOD_KOU_ECOLOGI_NOT_CONFIGURED'));
        }

        $hours  = max(1, min(168, (int) $params->get('cache_hours', 6)));
        $cache  = new RemoteCache(JPATH_CACHE . '/mod_kou_ecologi');
        $result = $cache->remember('impact:' . strtolower($username), $hours * 3600, static fn () => Impact::fetch($username));

        if ($result['data'] === null) {
            return $this->noticeOnly($data, Text::sprintf('MOD_KOU_ECOLOGI_FETCH_FAILED', $result['error']));
        }

        $totals   = Impact::parse((array) $result['data'], (bool) $params->get('include_pending', 0));
        $hideZero = (bool) $params->get('hide_zero', 1);

        foreach (self::SHOW as $option => [$kind, $stem, $unit, $decimals]) {
            $default = \in_array($option, ['show_trees', 'show_carbon'], true) ? 1 : 0;

            if (!$params->get($option, $default) || ($hideZero && $totals[$kind] <= 0)) {
                continue;
            }

            $value = $totals[$kind];
            $dp    = $decimals && floor($value) != $value ? $decimals : 0;

            $data['stats'][] = [
                'kind'   => $kind,
                'value'  => $value,
                'number' => number_format($value, $dp, Text::_('DECIMALS_SEPARATOR'), Text::_('THOUSANDS_SEPARATOR')),
                'unit'   => $unit === '' ? '' : Text::_('MOD_KOU_ECOLOGI_UNIT_' . strtoupper($unit)),
                'label'  => Text::_('MOD_KOU_ECOLOGI_' . $stem . ($value == 1 ? '_ONE' : '')),
                'dp'     => $dp,
            ];
        }

        if (!$data['stats']) {
            return $this->noticeOnly($data, Text::_('MOD_KOU_ECOLOGI_NOTHING_YET'));
        }

        $referral = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $params->get('referral', ''));

        $data['id']        = 'kou-eco-' . (int) $module->id;
        $data['intro']     = trim((string) $params->get('intro', ''));
        $data['showLink']  = (bool) $params->get('show_link', 1);
        $data['linkText']  = trim((string) $params->get('link_text', '')) ?: Text::_('MOD_KOU_ECOLOGI_LINK_DEFAULT');
        $data['newTab']    = (bool) $params->get('new_tab', 1);
        $data['profile']   = 'https://ecologi.com/' . rawurlencode($username) . ($referral !== '' ? '?r=' . $referral : '');
        $data['animate']   = (bool) $params->get('animate', 0);
        $data['showIcons'] = (bool) $params->get('show_icons', 1);
        $data['classes']   = implode(' ', [
            'kou-eco',
            'kou-eco--' . self::pick($params->get('theme', 'green'), ['green', 'light', 'plain'], 'green'),
            'kou-eco--align-' . self::pick($params->get('align', 'start'), ['start', 'center', 'end'], 'start'),
        ]);

        $this->addAssets($data, $params);

        return $data;
    }

    /** Visitors see nothing; people who can manage modules see why. */
    private function noticeOnly(array $data, string $message)
    {
        $user = $this->getApplication()->getIdentity();

        if (!$user || !$user->authorise('core.manage', 'com_modules')) {
            return false;
        }

        $data['notice'] = $message;

        return $data;
    }

    private function addAssets(array $data, $params): void
    {
        $wa = $this->getApplication()->getDocument()->getWebAssetManager();
        $wa->getRegistry()->addExtensionRegistryFile('mod_kou_ecologi');
        $wa->useStyle('mod_kou_ecologi.ecologi');

        if ($data['animate']) {
            $wa->useScript('mod_kou_ecologi.count');
        }

        $accent = preg_match('/^#[0-9a-f]{3,8}$/i', trim((string) $params->get('accent', ''))) ? trim((string) $params->get('accent')) : '';

        if ($accent !== '') {
            $wa->addInlineStyle('#' . $data['id'] . '{--kou-eco-accent:' . $accent . ';}');
        }
    }

    private static function pick($value, array $allowed, string $default): string
    {
        return \in_array((string) $value, $allowed, true) ? (string) $value : $default;
    }
}
