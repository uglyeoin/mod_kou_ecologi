<?php

/**
 * @package     KindOfUseful.Module.Ecologi
 *
 * @copyright   (C) 2026 Kind of Useful. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

/**
 * Checks the requirements, and when upgrading from the old (Joomla 3 era) module removes
 * its leftover language files and carries its settings across.
 */
return new class () implements InstallerScriptInterface {
    private const ELEMENT = 'mod_kou_ecologi';

    public function install(InstallerAdapter $parent): bool
    {
        return true;
    }

    public function update(InstallerAdapter $parent): bool
    {
        return true;
    }

    public function uninstall(InstallerAdapter $parent): bool
    {
        return true;
    }

    public function preflight(string $type, InstallerAdapter $parent): bool
    {
        if (version_compare(PHP_VERSION, '8.1', '<') || version_compare(JVERSION, '5.0', '<')) {
            Factory::getApplication()->enqueueMessage(Text::_('MOD_KOU_ECOLOGI_REQUIREMENTS'), 'error');

            return false;
        }

        return true;
    }

    public function postflight(string $type, InstallerAdapter $parent): bool
    {
        if ($type !== 'update') {
            return true;
        }

        // The old versions installed their language files into the site and admin language
        // folders, where Joomla would keep finding them ahead of the new ones.
        foreach ([JPATH_SITE, JPATH_ADMINISTRATOR] as $base) {
            foreach (glob($base . '/language/*/*' . self::ELEMENT . '*.ini') ?: [] as $file) {
                @unlink($file);
            }
        }

        // ...and inside the module folder under their old en-GB.mod_... names.
        foreach (glob(JPATH_SITE . '/modules/' . self::ELEMENT . '/language/*/*.' . self::ELEMENT . '*.ini') ?: [] as $file) {
            @unlink($file);
        }

        // Old layouts that are not in this version (they used Joomla 3 code and would fail
        // if chosen). Template overrides live in the template, so nothing of the owner's is lost.
        $source = $parent->getParent()->getPath('source') . '/tmpl';

        if (is_dir($source)) {
            foreach (glob(JPATH_SITE . '/modules/' . self::ELEMENT . '/tmpl/*') ?: [] as $file) {
                if (is_file($file) && !is_file($source . '/' . basename($file))) {
                    @unlink($file);
                }
            }
        }

        $this->migrateParams();

        return true;
    }

    /** Carry each existing module's old settings across to the new names. */
    private function migrateParams(): void
    {
        $element = self::ELEMENT;
        $db    = Factory::getContainer()->get(DatabaseInterface::class);
        $query = $db->getQuery(true)
            ->select($db->quoteName(['id', 'params']))
            ->from($db->quoteName('#__modules'))
            ->where($db->quoteName('module') . ' = :element')
            ->bind(':element', $element);

        foreach ($db->setQuery($query)->loadObjectList() as $row) {
            $params = new Registry($row->params);
            $before = $params->toString();

            $this->mapParams($params);

            if ($params->toString() === $before) {
                continue;
            }

            $json   = $params->toString();
            $id     = (int) $row->id;
            $update = $db->getQuery(true)
                ->update($db->quoteName('#__modules'))
                ->set($db->quoteName('params') . ' = :params')
                ->where($db->quoteName('id') . ' = :id')
                ->bind(':params', $json)
                ->bind(':id', $id, ParameterType::INTEGER);
            $db->setQuery($update)->execute();
        }
    }

    private function mapParams(Registry $params): void
    {
        // 0.0.x stored the username as ecologiUserName and a single "endpoints" choice.
        if ($params->get('username', '') === '' && $params->get('ecologiUserName', '') !== '') {
            $params->set('username', (string) $params->get('ecologiUserName'));
        }

        $endpoint = (string) $params->get('endpoints', '');

        if ($endpoint !== '') {
            $params->set('show_trees', $endpoint === 'carbon-offset' ? 0 : 1);
            $params->set('show_carbon', $endpoint === 'trees' ? 0 : 1);
        }

        $params->remove('ecologiUserName');
        $params->remove('endpoints');
    }
};
