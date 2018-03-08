<?php
// include config and functions
require_once( 'modules/config.php');
require_once( 'modules/functions.php');
?>

<!DOCTYPE html>

<head>
<link rel="stylesheet" type="text/css" href="modules/style.css">
<title><?php echo $siteTitle; ?></title>
<meta http-equiv="refresh" content="<?php echo $autoRefreshInSeconds; ?>">
</head>

<body>
<?php

// get curl handle
$ch = curl_init();

if (!$ch)
{
  myError('Could not initialize curl!');
}

// we have a valid curl handle here
// set some curl options
curl_setopt($ch, CURLOPT_URL, 'http://'.$nanoNodeRPCIP.':'.$nanoNodeRPCPort);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// -- Get Version String from nano_node ---------------
$rpcVersion = getVersion($ch);
$version = $rpcVersion->{'node_vendor'};

// -- Get get current block from nano_node 
$rpcBlockCount = getBlockCount($ch);
$currentBlock = $rpcBlockCount->{'count'};
$uncheckedBlocks = $rpcBlockCount->{'unchecked'};

// -- Get number of peers from nano_node 
$rpcPeers = getPeers($ch);
$peers = (array) $rpcPeers->{'peers'};
$numPeers = count($peers);

// -- Get node account balance from nano_node 
//$rpcNodeAccountBalance = getAccountBalance($ch, $nanoNodeAccount);
//$accBalanceMnano = rawToMnano($rpcNodeAccountBalance->{'balance'},4);
//$accPendingMnano = rawToMnano($rpcNodeAccountBalance->{'pending'},4);

// -- Get Number of Delegators --
$rpcDelegators =  getAccountDelegators($ch,$nanoNodeAccount);
$numDelegators =  $rpcDelegators->{'count'};



// -- Get representative info for current node from nano_node 
$rpcNodeRepInfo = getRepresentativeInfo($ch, $nanoNodeAccount);
$votingWeight = rawToMnano($rpcNodeRepInfo->{'weight'},0);
$repAccount = $rpcNodeRepInfo->{'representative'};


// close curl handle
curl_close($ch);
?>

<!-- Nano Market Data Section-->

<a href="https://nano.org/" target="_blank">
	<img src="modules/nano-logo.png" class="logo" alt="Logo Nano"/>
</a>
<h1><?php echo $siteTitle; ?></h1>
<br style="clear:all">

<?php

// get nano data from coinmarketcap
$nanoCMCData = getNanoInfoFromCMCTicker($cmcTickerUrl);

if (!empty($nanoCMCData))
{ // begin nano market data section

  // beautify market info to be displayed
  $nanoMarketCapUSD = "$" . number_format( (float) $nanoCMCData->{'market_cap_usd'} / pow(10,9), 2 ) . "B";
  $nanoMarketCapEUR =       number_format( (float) $nanoCMCData->{'market_cap_eur'} / pow(10,9), 2 ) . "Mâ‚¬";

  $nanoPriceUSD = "$" . number_format( (float) $nanoCMCData->{'price_usd'} , 2 );
  $nanoPriceEUR =       number_format( (float) $nanoCMCData->{'price_eur'} , 2 ) . "€";

  $nanoChange24hPercent = number_format( (float) $nanoCMCData->{'percent_change_24h'}, 2 );
  $nanoChange7dPercent  = number_format( (float) $nanoCMCData->{'percent_change_7d'}, 2 );

  // color values for positive and negative change
  $colorPos = "darkgreen";
  $colorNeg = "firebrick";

  $nanoChange24hPercentHTMLCol = $colorNeg;
  $nanoChange7dPercentHTMLCol  = $colorNeg;

  // prepend '+' sign and make it green (hopefully ...)
  if ( $nanoChange24hPercent > 0)
  {
    $nanoChange24hPercent  = "+" . $nanoChange24hPercent;
    $nanoChange24hPercentHTMLCol = $colorPos;
  }

  if ( $nanoChange7dPercent > 0)
  {
    $nanoChange7dPercent  = "+" . $nanoChange7dPercent;
    $nanoChange7dPercentHTMLCol = $colorPos;
  }

  // append '%''
  $nanoChange24hPercent = $nanoChange24hPercent . "%";
  $nanoChange7dPercent  = $nanoChange7dPercent . "%";

?>

<!-- Nano Market Data Table -->

<div class="ticker">
Value: <?php print ($nanoPriceUSD . " | " . $nanoPriceEUR . " | " . $nanoPriceBTC); ?>  <?php print ("<span style='color:" . $nanoChange24hPercentHTMLCol . "'>" . $nanoChange24hPercent . " (24h)</span> | ". "<span style='color:" . $nanoChange7dPercentHTMLCol  . "'>" . $nanoChange7dPercent .  " (7d)</span>"); ?>

<?php
}
?>

	</div>
<!-- Node Info -->

	<div class="info">	
<p class="medium">

<!--
Enter your description/text/picture of your cat/whatever here
-->
</p>

<h3>Node Information:</h3>

<p class="medium">Version: 10.0.1<br/>
Current Block: <?php print($currentBlock) ?><br/>
Number of Unchecked Blocks: <?php print($uncheckedBlocks) ?><br/>
Number of Peers: <?php print($numPeers) ?><br/>
Address: <a  href="https://www.nanode.co/account/<?php print($nanoNodeAccount); ?>" target="_blank"><?php print($nanoNodeAccount); ?></a><br/>
Voting Weight: <?php echo $votingWeight; ?> Nano<br/<br/>
Delegators:<?php echo $numDelegators ?></br>
System: <?php echo $serverInfo; ?><br/>
System Load Average: <?php print(getSystemLoadAvg()); ?><br/>
<?php
  $data = shell_exec('uptime');
  $uptime = explode(' up ', $data);
  $uptime = explode(',', $uptime[1]);
  $uptime = $uptime[0].', '.$uptime[1];

  echo ('Current server uptime: '.$uptime.'
');

?>
</p>

</div>
<!-- Footer -->
<hr>
<div class="footer">
<p class="small"><a href="https://github.com/stefonarch/Nanode21/" target="_blank">Nanode21</a> is forked from <a href="https://github.com/dbachm123/phpNodeXRai" target="_blank">phpNodeXrai</a></p>
<p class="small">Server Cost: <?php echo $monthlyCosts; ?>/mo. Donations:  
<a  href="https://www.nanode.co/account/<?php print($nanoDonationAccount); ?>" target="_blank"><?php print($nanoDonationAccount); ?></a>
</p>
</div>											   
</body>
</html>
