<?php
/**
 * @package    mod_kou_ecologi
 *
 * @author     Eoin Oliver <info@kindofuseful.com>
 * @copyright  Onion Marketing Ltd
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://www.kindofuseful.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;


$ecologiUserName        = $params->get('ecologiUserName');
$ecologiEndPoint        = $params->get('endpoints');
$ecologiReferralLink    = $params->get('ecologiReferralLink');
$ecologiBaseUrl         = "public.ecologi.com";


$json = file_get_contents("https://" . $ecologiBaseUrl . "/users/" . $ecologiUserName . "/" . $ecologiEndPoint);
$data = json_decode($json,true);

if($data['trees'])
{
    $trees = $data['trees'];
}
else
{
    echo "<script>console.log('Sorry there was an error, we couldn\'t find any trees)</script>";
}

if($data['trees'])
{
    $CO2OffsetSoFar = $data['carbonOffset'];
}
else
{
    echo "<script>console.log('Sorry there was an error, we couldn\'t find any value for Carbon Offset)</script>";
}


?>

<div class="mod_kou_ecologi">
    <div id="ecologi">
        <p><?php JText::_( 'MOD_KOU_ECOLOGI_ECOLOGI_TREES_IN_OUR_FOREST_SO_FAR'); ?><span id="ecologiTrees"><?php echo $trees; ?></span></p>
        <p><?php JText::_( 'MOD_KOU_ECOLOGI_ECOLOGI_TREES_IN_OUR_TONNES_OF_CO2_EMMISSIONS_OFFSET_SO_FAR'); ?><span id="ecologiCarbon"><?php echo $CO2OffsetSoFar; ?>t</span></p>
        <p><a href="<?php echo $ecologiReferralLink; ?>>" target="_blank" class="button"><?php JText::_( 'MOD_KOU_ECOLOGI_ECOLOGI_VERIFY_THIS_INFORMATION'); ?></a></p>
    </div>
</div>