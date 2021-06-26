<!DOCTYPE html>
<html>
<head>
  <title>Trade Chart</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('/').('public/assets/css/bootstrap.min.css')}}">
</head>
<body>
<div class="col-xs-12 col-sm-12 col-lg-12 card-div no-padding px-0">
  <div id="chart_container" class="basic_trade" style="margin-bottom: 10px;"></div>
</div>
</body>
</html>
<script src="{{asset('/').('public/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('/').('public/assets/js/script.js')}}"></script>
<script type="text/javascript" src="{{asset('/').('public/charting_library/charting_library.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/').('public/datafeeds/udf/dist/polyfills.js') }}"></script>
<script type="text/javascript" src="{{asset('/').('public/datafeeds/udf/dist/bundle.js') }}"></script>
<script type="text/javascript" src="{{asset('/').('public/assets/js/socket.io.min.js') }}"></script>

<script type="text/javascript">
  siteurl = "{{URL::to('/')}}";
  pair = "<?php echo $pair?>";
  theme = localStorage.getItem('trade_theme');
  
  if(theme == 'dark') {
      var backClr = "#ffffff";
      var gridClr = "#eee";
      var textClr = "#333";
      var cssFile = "light_style.css";
    } else {
      var backClr = "#1D2129";
      var gridClr = "#111";
      var textClr = "#fff";
      var cssFile = "dark_style.css";
    }
 
  library_path = "{{ asset('/').('public/charting_library/') }}";


   var widget = new TradingView.widget({
        "fullscreen": true,
        "tvwidgetsymbol" :pair,
        "symbol": pair,
        "style": "1",
        "precision": 3,
        "show_popup_button": true,
        "popup_width": "1050",
        "popup_height": "250",
        "toolbar_bg": backClr,
        "container_id": "chart_container",
        "datafeed": new Datafeeds.UDFCompatibleDatafeed(siteurl+"/chart"+'/'+pair),
        "library_path": library_path,
        "withdateranges": true,
        "allow_symbol_change": false,
        "interval": "1",
        "locale": "en",
        "theme" : "light",
        "height": "372px",
        "save_image": false,
        "hideideas": true,
        "custom_css_url": cssFile,
        "debug": false,
        "show_popup_button": true,
        "locale": "en",
        "drawings_access": { type: 'black', tools: [ { name: "Regression Trend" } ] },
        // "disabled_features": ["use_localstorage_for_settings","dome_widget","display_market_status","display_header_toolbar_chart","header_compare","header_undo_redo","compare_symbol","header_settings","study_dialog_search_control","caption_buttons_text_if_possible","header_screenshot","volume_force_overlay","header_widget","left_toolbar"],
        "disabled_features": ["use_localstorage_for_settings","dome_widget","display_market_status","header_compare","header_undo_redo","compare_symbol","study_dialog_search_control","caption_buttons_text_if_possible","volume_force_overlay","left_toolbar"],
        "overrides": {
       //   "mainSeriesProperties.style": 8,
          "paneProperties.background": backClr,
          "paneProperties.horzGridProperties.color": gridClr,
          "paneProperties.vertGridProperties.color": gridClr,
          "symbolWatermarkProperties.transparency": 90,
          "scalesProperties.textColor" : textClr
        }
    });
</script>
