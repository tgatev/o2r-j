<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2015. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die;

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_uu/assets/css/uu.css');

?>

<script type="application/javascript">

    window.onload = function(){
        var ctx = document.getElementById("chartusers").getContext("2d");
        var myNewChart = new Chart(ctx).Bar(datausers);

        var ctx2 = document.getElementById("chartusersgroups").getContext("2d");;
        var myPieChart = new Chart(ctx2).Pie(datausersgroups,{
            animateScale: false,
            segmentShowStroke : true,
            segmentStrokeWidth : 2,
            animateRotate : false,
            animationEasing : "easeOutBounce"
        });

        var legendHolder = document.getElementById('chartusersgroupslegend')
        legendHolder.innerHTML =  myPieChart.generateLegend();

        var helpers = Chart.helpers;
        // Include a html legend template after the module doughnut itself
        helpers.each(legendHolder.firstChild.childNodes, function(legendNode, index){
            helpers.addEvent(legendNode, 'mouseover', function(){
                var activeSegment = myPieChart.segments[index];
                activeSegment.save();
                activeSegment.fillColor = activeSegment.highlightColor;
                myPieChart.showTooltip([activeSegment]);
                activeSegment.restore();
            });
        });
        helpers.addEvent(legendHolder.firstChild, 'mouseout', function(){
            myPieChart.draw();
        });

        myPieChart.generateLegend();
        document.getElementById('chartusersgroupslegend').innerHTML = myPieChart.generateLegend();
    }

</script>

<div class="row-fluid">

    <div class="span6">
        <h3><?php echo JText::_('COM_UU_CPANEL_USERS_CHART') ?></h3>
        <div id="uuuserschart">
            <canvas id="chartusers" width="450" height="300"></canvas>
            <p id="uuuserschart-nodata" style="display:none">
                <?php echo JText::_('COM_UU_CPANEL_USERS_NODATA')?>
            </p>
        </div>
        <h3><?php echo JText::_('COM_UU_CPANEL_USERS_GROUPS_CHART') ?></h3>
        <div id="uuusersgroupschart">
            <div class="canvasholder">
                <canvas id="chartusersgroups" width="250" height="250" style="width: 250px;height:250px"></canvas>
            </div>
            <div id="chartusersgroupslegend"></div>
            <p id="chartusersgroups-nodata" style="display:none">
                <?php echo JText::_('COM_UU_CPANEL_USERS_GROUPS_NODATA')?>
            </p>
        </div>
    </div>
    <div class="span6">
        <h3><?php echo JText::_('COM_UU_CPANEL_STATS')?></h3>
        <table width="100%" class="table table-striped">
            <tbody>
            <tr class="row0">
                <td width="70%"><?php echo JText::_('COM_UU_CPANEL_STATS_LASTYEAR')?></td>
                <td align="right" width="30%"><?php echo $this->statistics['lastyear']?></td>
            </tr>
            <tr class="row1">
                <td width="70%"><?php echo JText::_('COM_UU_CPANEL_STATS_THISYEAR')?></td>
                <td align="right" width="30%"><?php echo $this->statistics['thisyear']?></td>
            </tr>
            <tr class="row0">
                <td width="70%"><?php echo JText::_('COM_UU_CPANEL_STATS_LASTMONTH')?></td>
                <td align="right" width="30%"><?php echo $this->statistics['lastmonth']?></td>
            </tr>
            <tr class="row1">
                <td width="70%"><?php echo JText::_('COM_UU_CPANEL_STATS_THISMONTH')?></td>
                <td align="right" width="30%"><?php echo $this->statistics['thismonth']?></td>
            </tr>
            </tbody>
            <tr class="row0">
                <td width="70%"><?php echo JText::_('COM_UU_CPANEL_STATS_LAST7DAYS')?></td>
                <td align="right" width="30%"><?php echo $this->statistics['last7days']?></td>
            </tr>
            <tr class="row1">
                <td width="70%"><?php echo JText::_('COM_UU_CPANEL_STATS_YESTERDAY')?></td>
                <td align="right" width="30%"><?php echo $this->statistics['yesterday']?></td>
            </tr>
            <tr class="row1">
                <td width="70%"><?php echo JText::_('COM_UU_CPANEL_STATS_TODAY')?></td>
                <td align="right" width="30%"><?php echo $this->statistics['today']?></td>
            </tr>
            </tbody>
        </table>

        <br/>

        <h3><?php echo JText::_('COM_UU_CPANEL_STATUSSUMMARY')?></h3>
        <div style="padding: 5px;">
            <table class="adminlist">
                <th width="120" align="right" style="padding-right:10px"><?php echo JText::_('COM_UU_CPANEL_CURRENT_VERSION'); ?>:</th>
                <td><?php echo $this->currentVersion; ?></td>
                </tr>
                <tr>
                    <th width="120" align="right"  style="padding-right:10px"><?php echo JText::_('COM_UU_CPANEL_LATEST_VERSION'); ?>:</th>
                    <td><div id="uu-last-version"><?php
                            if (version_compare($this->latestVersion,$this->currentVersion,'>' )) {
                                ?><span class="update-msg-new"><?php
                                echo $this->latestVersion;
                                ?></span><?php
                            } else {
                                echo $this->currentVersion;
                            }
                            ?>
                            <?php if ($this->updateInfo->hasUpdates) {?>
                                <span class="update-msg-new"><?php echo JText::_('COM_UU_CPANEL_OLD_VERSION'); ?>
                                    <a href="index.php?option=com_uu&view=liveupdate"><?php echo JText::_('COM_UU_CPANEL_UPDATE_LINK'); ?></a>
                        </span>
                            <?php } else { ?>
                                <span class="update-msg-info"><?php echo JText::_('COM_UU_CPANEL_LATEST_VERSION'); ?></span>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th width="120" align="right"  style="padding-right:10px"><?php echo JText::_('COM_UU_CPANEL_LICENCE_TYPE'); ?>:</th>
                    <td><?php echo ucfirst(UU_LICENCE); ?></td>
                </tr>
                <tr>
                    <th>
                    </th>
                    <td>
                        <input type="button" value="<?php echo JText::_('COM_UU_CPANEL_CHECK_UPDATES'); ?>" onclick="checkUpdates();">
                        <span id="uu-update-progress"></span>
                    </td>
                </tr>
            </table>
            <div id="updatescontent" class="updates"></div>
        </div>

    </div>

</div>