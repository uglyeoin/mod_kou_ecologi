<?php
/**
 * @package    mod_kou_ecologi
 *
 * @author     Eoin Oliver <eion@squareballoon.co.uk>
 * @copyright  Onion Marketing Ltd
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://squareballoon.co.uk
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;



$ecologiUserName        = $params->get('ecologiUserName');
$ecologiEndPoint        = $params->get('endpoints');
$ecologiBaseUrl         = "public.ecologi.com";
$ecologiFullUrl         = "https://" . $ecologiBaseUrl . "/users/" . $ecologiUserName;
$ecologiReferralLink    = $ecologiFullUrl . "?r=" . $ecologiUserName;

$json = file_get_contents($ecologiFullUrl . "/" . $ecologiEndPoint);
$data = json_decode($json,true);


if($ecologiEndPoint == "impact")
{
    $dataVariable = "trees";
}
else
{
    $dataVariable = "total";
}

if($data[$dataVariable])
{
    if($data[$dataVariable == "trees"])
    {
        $trees = $data['trees'];
    }
    elseif($data[$dataVariable == "carbon-offset"])
    {
        $CO2OffsetSoFar = $data['total'];
    }
    else
    {
        $trees          = $data['total'];
    }
}

?>

<div class="mod_kou_ecologi">
    <div id="ecologi">
        <?php if($ecologiEndPoint == "trees" OR "impact") { ?>
            <p><?php echo Text::_( 'MOD_KOU_ECOLOGI_ECOLOGI_TREES_IN_OUR_FOREST_SO_FAR'); ?><span id="ecologiTrees"><?php echo $trees; ?></span></p>
        <?php } ?>
        <?php if($ecologiEndPoint == "trees" OR "carbon-offset") { ?>
            <p><?php echo Text::_( 'MOD_KOU_ECOLOGI_ECOLOGI_TREES_IN_OUR_TONNES_OF_CO2_EMMISSIONS_OFFSET_SO_FAR'); ?><span id="ecologiCarbon"><?php echo $CO2OffsetSoFar; ?>t</span></p>
        <?php } ?>
        <p><a href="<?php echo $ecologiReferralLink; ?>" target="_blank" class="button"><?php echo Text::_( 'MOD_KOU_ECOLOGI_ECOLOGI_VERIFY_THIS_INFORMATION'); ?></a></p>
    </div>
</div>