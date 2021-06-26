
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		#tv_chart_container {
			height: 100vh !important;
			width: 100% !important;
		}
		#tv_chart_container iframe {
			height: 100% !important;
			width: 100% !important;
		}
	</style>
</head>
<body>

	
	<?php error_reporting(-1);  ini_set('display_errors', 1); ?>
	<div class="tcGraphSec">
		<div class="tradingview-widget-container">
			<div id="tv_chart_container"></div>
		</div>
		
	</div>

	<script type="text/javascript" src="{{asset('/').('public/mobile/charting_library/charting_library.min.js')}}"></script>

	<script type="text/javascript" src="{{asset('/').('public/mobile/datafeeds/udf/dist/polyfills.js')}}"></script>

	<script type="text/javascript" src="{{asset('/').('public/mobile/datafeeds/udf/dist/bundle.js')}}"></script> 

	

<script>
	siteurl = "{{URL::to('/')}}";
	var api = "https://centrex.exchange/en/centrex/trade/mobile_trade_chart/BTC_USDT";
  
 library_path = "{{ asset('/').('public/mobile/charting_library/') }}";
</script>


	<script type="text/javascript">

	pairData = {};  
	
		var pair = "BTC_ETH";
		showthechart(pair);
		function showthechart(pair) {
			var widget = new TradingView.widget({
				fullscreen: false,
				"tvwidgetsymbol" : pair,
				symbol: pair,
				toolbar_bg: '#EAEAEA',
				container_id: "tv_chart_container",
				datafeed: new Datafeeds.UDFCompatibleDatafeed(siteurl+"/chart"+'/'+pair),
				library_path: library_path,
				withdateranges: true,
				allow_symbol_change: false,
				interval: "1",
				locale: "en",
				save_image: false,
				hideideas: true,
				debug: false,  
				drawings_access: { type: 'black', tools: [ { name: "Regression Trend" } ] },
				disabled_features: ["use_localstorage_for_settings"],
				overrides: {
					"paneProperties.background": '#EAEAEA',
					"paneProperties.vertGridProperties.color": "#000",
					"paneProperties.horzGridProperties.color": "#000",
					"symbolWatermarkProperties.transparency": 90,
					"scalesProperties.textColor" : "#AAA",},
				});
		}
	</script>
</body>
</html>