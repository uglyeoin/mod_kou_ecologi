<?php
/**
 * @package    mod_kou_ecologi
 *
 * @author     Eoin Oliver <eoin@squareballoon.co.uk>
 * @copyright  Onion Marketing Ltd
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://www.squareballoon.co.uk
 */

defined('_JEXEC') or die;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_kou_ecologi', $params->get('layout', 'default'));
