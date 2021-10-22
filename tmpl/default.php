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
        <p>Trees in our forest so far: <span id="ecologiTrees"><?php echo $trees; ?></span></p>
        <p>Total number of tonnes of CO2 emissions offset so far: <span id="ecologiCarbon"><?php echo $CO2OffsetSoFar; ?>t</span></p>
        <p><a href="<?php echo $ecologiReferralLink; ?>>" target="_blank" class="button">Verify this information & see our forest</a></p>
    </div>
</div>