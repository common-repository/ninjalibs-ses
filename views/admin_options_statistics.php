<?php

use NinjaLibs\EmailListTable;
use NinjaLibs\Ses\DB;
use NinjaLibs\Ses\Utils as SesUtils;

if (!defined('WPINC')) {
    die;
}

function ninjalibs_graph_group_by_day($sendDataPoints, $field)
{
    $grouped_data = [];
    foreach ($sendDataPoints as $datapoint) {
        $hourdate = date('Y-m-d', strtotime($datapoint["Timestamp"]->__toString()));
        if (!isset($grouped_data[$hourdate])) {
            $grouped_data[$hourdate] = [$field=>$datapoint[$field],'DeliveryAttempts'=> $datapoint["DeliveryAttempts"]];
        } else {
            $grouped_data[$hourdate] = [
                  $field=> $grouped_data[$hourdate][$field] + $datapoint[$field] ,
                 'DeliveryAttempts'=> $datapoint["DeliveryAttempts"] + $grouped_data[$hourdate]["DeliveryAttempts"]
             ];
        }
    }
    return $grouped_data;
}

function sortbydate(&$array)
{
    usort($array, function ($data1, $data2) {
        return strtotime($data1['time']) > strtotime($data2['time']) ? -1 : 1;
    });
}

function ninjalibs_calc_reputation($sendDataPoints, $field)
{
    $grouped_data = ninjalibs_graph_group_by_day($sendDataPoints, $field);

    $reputation = [
        'day'=>count($grouped_data),
        'sent'=>0,
        'rate'=>0.0,
        $field=>0,
    ];

    $rep  = 0;
    foreach ($grouped_data as $time => $data) {
        $rep += $data[$field];
        $reputation['sent']+=$data['DeliveryAttempts'];
    }

    $reputation[$field] = $rep;
    $reputation['rate'] = $rep / $reputation['sent'];
    return $reputation;
}

function ninjalibs_graph_calc_by_day($grouped_data, $field)
{
    $calculcated_data = [];
    foreach ($grouped_data as $time => $data) {
        $calculcated_data[] = ['time'=>$time,'value'=>$data[$field]/$data['DeliveryAttempts']];
    }

    return $calculcated_data;
}

function ninjalib_graph_create_data($sendDataPoints, $field)
{
    $arr =   ninjalibs_graph_calc_by_day(ninjalibs_graph_group_by_day($sendDataPoints, $field), $field);
    sortbydate($arr);
    return $arr;
}


//TODO: get some meaningfull data from statistics and try UPSELL!
$statistics = SesUtils::getSendStatistics();
$bounce_data = [];
$complaints_data = [];


$reputation_bounce =  [
        'day'=> 0,
        'sent'=>0,
        'rate'=>0.0,
        'Bounces'=>0,
    ];


$reputation_complaints = [
        'day'=> 0,
        'sent'=>0,
        'rate'=>0.0,
        'Complaints'=>0,
    ];


if ($statistics && isset($statistics['SendDataPoints']) && is_array($statistics['SendDataPoints'])) {

    //graph data
    $bounce_data = ninjalib_graph_create_data($statistics['SendDataPoints'], "Bounces");
    $complaints_data = ninjalib_graph_create_data($statistics['SendDataPoints'], "Complaints");
    $rejects_data = ninjalib_graph_create_data($statistics['SendDataPoints'], "Rejects");

    //reputation data
    $reputation_bounce = ninjalibs_calc_reputation($statistics['SendDataPoints'], "Bounces");
    $reputation_complaints = ninjalibs_calc_reputation($statistics['SendDataPoints'], "Complaints");
}

?>
<script type="text/javascript">
jQuery(document).ready(function(){
var theChartDiv  = document.getElementById('ninjalibs-bounce-chart');  
var chart = LightweightCharts.createChart(theChartDiv, {
  height: 300,
  rightPriceScale:{
  	visible: false,
  },
	leftPriceScale: {
		visible: true,
    },
    timeScale: {
	rightOffset: 2,
        tickMarkFormatter: (time, tickMarkType, locale) => {
            return String(time.year)+"-"+String(time.month)+"-"+String(time.day);
        },
    },
});

var areaSeries = chart.addAreaSeries({
  topColor: 'rgba(197, 57, 51,0.4)',
  bottomColor: 'rgba(197, 57, 51,0.1)',
  lineColor: 'rgba(197, 57, 51,1)',
  lineWidth: 2,
});

areaSeries.setData(<?php echo json_encode($bounce_data, JSON_PRETTY_PRINT); ?>);

chart.timeScale().fitContent();

});
</script>

<script type="text/javascript">
jQuery(document).ready(function(){
var theChartDiv  = document.getElementById('ninjalibs-complaints-chart');  
var chart = LightweightCharts.createChart(theChartDiv, {
  height: 300,
  rightPriceScale:{
  	visible: false,
  },
	leftPriceScale: {
		visible: true,
    },
    timeScale: {
	rightOffset: 2,
        tickMarkFormatter: (time, tickMarkType, locale) => {
            return String(time.year)+"-"+String(time.month)+"-"+String(time.day);
        },
    },
});

var areaSeries = chart.addAreaSeries({
  topColor: 'rgba(141 ,107, 184,0.4)',
  bottomColor: 'rgba(141 ,107, 184,0.1)',
  lineColor: 'rgba(141 ,107, 184,1)',
  lineWidth: 2,
});

areaSeries.setData(<?php echo json_encode($complaints_data, JSON_PRETTY_PRINT); ?>);

chart.timeScale().fitContent();

});
</script>


<script type="text/javascript">
jQuery(document).ready(function(){
var theChartDiv  = document.getElementById('ninjalibs-rejects-chart');  
var chart = LightweightCharts.createChart(theChartDiv, {
  height: 300,
  rightPriceScale:{
  	visible: false,
  },
	leftPriceScale: {
		visible: true,
    },
    timeScale: {
	rightOffset: 2,
        tickMarkFormatter: (time, tickMarkType, locale) => {
            return String(time.year)+"-"+String(time.month)+"-"+String(time.day);
        },
    },
});

var areaSeries = chart.addAreaSeries({
  topColor: 'rgba(245, 124, 0, 0.4)',
  bottomColor: 'rgba(245, 124, 0, 0.1)',
  lineColor: 'rgba(245, 124, 0, 1)',
  lineWidth: 2,
});

areaSeries.setData(<?php echo json_encode($rejects_data, JSON_PRETTY_PRINT); ?>);

chart.timeScale().fitContent();

});
</script>

<div id="universal-message-container">
    <h2>Sending Statistics</h2>
    <div> 
    <?php
      $bounce_rate = round($reputation_bounce['rate'], 2, PHP_ROUND_HALF_UP);
      $complaint_rate = round($reputation_complaints['rate'], 2, PHP_ROUND_HALF_UP);
    ?>
    <p><?php if ($bounce_rate < 0.05): ?><span style="color: green; font-weight:bold">HEALTY:</span><?php endif;?> <?php echo $bounce_rate?>% Bounce Rate<hr>
    Your current bounce rate is <b><?php echo $bounce_rate ?>%</b>. This is measured over the last <b><?php echo $reputation_bounce['sent']; ?></b> eligible emails you sent, spanning over approximately the last <b><?php echo $reputation_bounce['day']; ?></b> days. We expect our senders' bounce rates to remain below <b>5%</b>. Senders with a bounce rate exceeding <b>10%</b> risk a sending Pause. <a href="https://docs.aws.amazon.com/console/ses/reputationdashboard-bounce" target="_blank">Learn more</a>.
     </p>
     <p><?php if ($complaint_rate < 0.1): ?><span style="color: green; font-weight:bold">HEALTY:</span><?php endif;?> <?php echo $complaint_rate ?>% Complaint Rate<hr>
        Your current complaint rate is <b><?php echo $complaint_rate?>%</b>. This is measured over the last <b><?php echo $reputation_complaints['Complaints']?></b> eligible emails you sent, spanning over approximately the last <b><?php echo $reputation_complaints['day']; ?></b> days. We expect our senders' complaint rates to remain below <b>0.1%</b>. Senders with a complaint rate exceeding <b>0.5%</b> risk a sending Pause. <a href="https://docs.aws.amazon.com/console/ses/reputationdashboard-complaint" target="_blank">Learn more</a>.
    </p>
    <div>
        <div>To learn more about reputation statuses please see <a href="https://docs.aws.amazon.com/console/ses/reputationdashboard-account-status"  target="blank">Reputation Status Messages</a>.</div> 
        <div>To learn more about setting CloudWatch alarms please see <a href="https://docs.aws.amazon.com/console/ses/reputationdashboard-cloudwatch-alarm" target="blank">How to create Reputation Monitoring Alarms</a>.</div>
    </div>
    <hr />
    <p>You can open the <a target="_blank" href="https://console.aws.amazon.com/ses/home?region=<?php echo NINJALIBS_SES_AWS_REGION; ?>#reputation-dashboard:">reputation dashboard</a> to review your bounce or complaint rate. However, the dashboard doesn't send you notifications when the bounce or complaint rate reaches a certain threshold.</p> 
    <p>To get notifications for a bounce or complaint rate threshold, set up an Amazon CloudWatch alarm with an Amazon Simple Notification Service (Amazon SNS) topic. You can set the CloudWatch alarm to notify the SNS topic whenever your bounce or complaint rate reaches a threshold that you specify. Then, the SNS topic sends a notification to the endpoint (such as an email address) that you subscribed to the topic. For instructions on setting up this notification system, see <a href="https://docs.aws.amazon.com/ses/latest/DeveloperGuide/reputationdashboard-cloudwatch-alarm.html" target="_blank">Creating Reputation Monitoring Alarms Using CloudWatch</a>.</p> 
    <p>Keep in mind the following recommendations when you set your CloudWatch alarm thresholds:</p> 
    <ul> 
    <li> Amazon SES recommends that you maintain a bounce rate under <b>5%</b>. Amazon SES might pause your account's ability to send emails if your bounce rate is greater than <b>10%</b>. We recommend that you set the bounce rate threshold at <b>0.05 (5%)</b>.</li> 
    <li>Amazon SES recommends that you maintain a complaint rate under <b>0.1%</b>. Amazon SES might pause your account's ability to send emails if your complaint rate is greater than <b>0.5%</b>. We recommend that you set the complaint rate threshold at <b>0.001 (0.1%)</b>.</li> 
    </ul> 
    <p><b>Note:</b> The metrics for bounces and complaints are available in the CloudWatch metrics only if your account has had a bounce or complaint event. To see the bounce and complaint metrics in the CloudWatch console, you can send <a href="https://docs.aws.amazon.com/ses/latest/DeveloperGuide/send-email-simulator.html" target="_blank">test emails to the designated mailbox simulator email addresses</a>.<br> </p> 
     </div>
    <h3 style="color: #c53933;">Bounces</h3>
    <div id="ninjalibs-bounce-chart" style="position: relative;"></div>
    <h3 style="color: #8d6bb8;">Complaints</h3>
    <div id="ninjalibs-complaints-chart" style="position: relative;"></div>
    <h3>Rejects</h3>
    <div id="ninjalibs-rejects-chart" style="position: relative;"></div>
</div>