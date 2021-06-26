<!DOCTYPE html>
<html lang="en">
<head>
 
  

  <link rel="stylesheet" href="{{asset('/').('public/assets/css/style.css')}}?{{date('Y-m-d h:i:s')}}"> 
  
</head>

<body>

            <div id="mobile_chart_container"  style="margin-bottom: 10px;height: 100%"></div>
              
       
<script src="{{asset('/').('public/assets/js/jquery.min.js')}}"></script>

<script type="text/javascript">
  siteurl = "{{URL::to('/')}}";
  
 library_path = "{{ asset('/').('public/mobile/charting_library/') }}";
</script>
<script type="text/javascript" src="{{asset('/').('public/mobile/charting_library/charting_library.min.js')}}"></script>

  <script type="text/javascript" src="{{asset('/').('public/mobile/datafeeds/udf/dist/polyfills.js')}}"></script>

  <script type="text/javascript" src="{{asset('/').('public/mobile/datafeeds/udf/dist/bundle.js')}}"></script> 


<script>


  displayChart('{{$pair_symbol}}');
  

 function displayChart(pair) {
  theme = 'light';
  
  if(theme == 'light') {
      var backClr = "#ffffff";
      var gridClr = "#eee";
      var textClr = "#333";
      var cssFile = "light_style.css";
    } else {
      var backClr = "#0a1e32";
      var gridClr = "#111";
      var textClr = "#fff";
      var cssFile = "dark_style.css";
    }

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
        "container_id": "mobile_chart_container",
        "datafeed": new Datafeeds.UDFCompatibleDatafeed(siteurl+"/chart"+'/'+pair),
        "library_path": library_path,
        "withdateranges": true,
        "allow_symbol_change": false,
        "interval": "5",
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
        "disabled_features": ["use_localstorage_for_settings","dome_widget","display_market_status","display_header_toolbar_chart","header_compare","header_undo_redo","compare_symbol","header_settings","study_dialog_search_control","caption_buttons_text_if_possible","header_screenshot","volume_force_overlay","header_widget","left_toolbar"],
        "overrides": {

          "paneProperties.background": backClr,
          "paneProperties.horzGridProperties.color": gridClr,
          "paneProperties.vertGridProperties.color": gridClr,
          "symbolWatermarkProperties.transparency": 90,
          "scalesProperties.textColor" : textClr
        }
    });
}

</script>


</body>
</html>