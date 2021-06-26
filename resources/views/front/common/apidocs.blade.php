<html>
<title>Apidocument</title>
<head>

<style type="text/css">
.navbar .navbar-collapse.collapse{display: flex !important;}
.exchange_dropdown .nav-link.index-header-link.dropdown-toggle{margin-top: 14px;}
.center-nav-links li a.active {

    color: #659aea;

}
#collapsingNavbar2 ul li a {
    color: #000;
}
</style>
</head>

<body>



  <div class="inner-page-container api-docs">
                <div class="container">
                    <div class="row pt-40 pb-40">
                        <div class="col-lg-6 col-12 api-docs-left">
                            <h2 class="title">{{trans('app_lang.api_heading') }}</h2>
                          
      <p>{{trans('app_lang.api_content') }}</p> <br>     
                            <h4 class="h4">{{trans('app_lang.api_heading2') }}</h4>
                            <p>{{trans('app_lang.api_content2') }}</p>
                            <p><p style="width: 65%;" class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">{{URL::to('/api')}}</p>
                            </p>
                            <p>{{trans('app_lang.api_content3') }}</p>
                        </div>
                        <div class="col-lg-6 col-12 api-docs-right">
                            
                            <img src="{{$banner->image_url}}" alt="API Docs">
                            

                        </div>
                    </div>
                    <div class="row pb-40">
                        <div class="col-lg-12 col-12">
                            <h4 class="h4">{{trans('app_lang.api_heading3') }}</h4>
                                <p>{{trans('app_lang.api_content4') }}</p>
                                                        <p>{{trans('app_lang.api_example') }}</p>
                            <div class="api-list">
                                <pre><code># {{trans('app_lang.api_example1') }}
                                curl "<a href="{{URL::to('/api/getMarketHistory/BTC_EUR')}}">{{URL::to('/api/getMarketHistory/BTC_EUR')}}</a>" 
                     
                                </code></pre>
                            </div>
                            <div class="api-list">
                                <pre><code>// Example valid response
                               {
                                "status": "1",
                                "result": {
                                    "pair": "BTC_EUR",
                                    "transaction_history": [
                                        {
                                            "datetime": "2019-05-20 09:13:27",,
                                            "type": "increase",
                                            "price": "0.41",
                                            "amount": "1",
                                            "from_cur": "BTC",
                                            "to_cur": "EUR",
                                            "sell_fee": 0.000123,
                                            "buy_fee": 0.02,
                                            "sellordertype": "limit",
                                            "buyordertype": "market"
                                        },
                                    }
                                }
                                </code></pre>
                            </div>
                            <div class="api-list">
                                <pre><code>// Example error response
                                 {"errors":[{"status":"0","message":"invalid pairs"}]}</code></pre>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="row pb-40">
                        <div class="col-lg-12 col-12">
                            <h2 class="title">{{trans('app_lang.api_private') }}</h2>
                            <p>{{trans('app_lang.api_privatecnt') }}</p>
                            <br>
                            <div class="api-docs-collapse" role="tablist" aria-multiselectable="true">
                                <div class="card collapse-icon accordion-icon-rotate">
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#makeorder"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_makeorder') }}</span></a>
                                    </div>
                                    <div id="makeorder" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_makeordercnt') }}</p>
                                                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                                                    {{URL::to('/api/createOrder')}}</p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>order</span>(string) - limit / market/stoprder.
                                                    </li>
                                                    <li><span>type</span>(string) - buy / sell
                                                    </li>
                                                    <li><span>pair</span>(string) - BTC_USDT
                                                    </li>
                                                    <li><span>price</span>(float) - Trade Price
                                                    </li>
                                                    <li><span>stopprice</span>(float) - Stop Price
                                                    </li>
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <pre><code>{
    "status": "success",
    "message": "order placed",
    "response": {
        "active": {
            "amount": "5",
            "price": "1",
            "total": "5",
            "datetime": "2019-05-22 10:09",
            "id": "A-900npUhckX9IaNgyzzOZk3pAR0v4S8kXkqD1AGjbI",
            "type": "Sell",
            "ordertype": "Limit"
        }
    }
}</pre></code>
                                              
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#openbuyorder"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_openbuyorder') }}</span></a>
                                    </div>
                                    <div id="openbuyorder" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_openbuyordercnt') }}</p>
                                                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                                                  {{URL::to('/api/openBuyorders')}}</p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>pair </span>(string) BTC_EUR.
                                                    </li>
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                    
                                                <pre><code>{
    "status": "1",
    "openOrders": [
        {
            "amount": "1",
            "type": "buy",
            "price": "0.42",
            "total": "0.42",
            "from_cur": "BTC",
            "to_cur": "EUR"
        }
    ]
}</pre></code>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#opensellorder"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_opensellorder') }}</span></a>
                                    </div>
                                    <div id="opensellorder" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_opensellordercnt') }}</p>
                                                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                                                   {{URL::to('/api/openSellOrders')}}</p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>pair </span>(string) - BTC_EUR.
                                                    </li>
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                   <pre><code>{  "status": "1",
    "openOrders": [
        {
            "amount": "1",
            "type": "sell",
            "price": "50",
            "total": "50",
            "from_cur": "BTC",
            "to_cur": "EUR"
        }
    ]
}</pre></code>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#balance"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_accountbalance') }}</span></a>
                                    </div>
                                    <div id="balance" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_accountbalancecnt') }}</p>
                                                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                                                   {{URL::to('/api/getAccountbalance')}}</p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>No params </span>
                                                    </li>
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                     <pre><code>{
    "status": 1,
    "data": {
        "BTC": "994.7",
        "ETH": "1000.0588",
        "USDT": 1000,
        "TEACH": 1000,
        "TYC": 1000,
        "TEX": 1000,
        "EUR": 10000,
        "WAVES": 1000
    }
} </pre></code>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#curbalance"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_currencybalance') }}</span></a>
                                    </div>
                                    <div id="curbalance" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_currencybalancecnt') }}</p>
                                                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                                                    {{URL::to('/api/getCurrencybalance')}}</p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>currency </span>(string) - BTC
                                                    </li>
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                   <pre><code>{
    "status": "1",
    "data": {
        "currencyname": "Bitcoin",
        "Available balance": "993.7"
    }
}</pre></code>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#tradehistory"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_filledorders') }}</span></a>
                                    </div>
                                    <div id="tradehistory" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_filledorderscnt') }}</p>
                                                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                                                   {{URL::to('/api/getFilledOrder')}}</p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>pair </span>(string) - BTC_EUR
                                                    </li>
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                     <pre><code>{
    "status": "1",
    "getfilledorders": [
        {
            "datetime": "10:02:44",
            "type": "increase",
            "price": "50",
            "amount": "1",
            "from_cur": "BTC",
            "to_cur": "EUR",
            "sell_fee": 0.015,
            "buy_fee": 0.02,
            "sellordertype": "limit",
            "buyordertype": "limit"
        },
    ]
} </pre></code>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#deposit_history"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_deposithistory') }}</span></a>
                                    </div>
                                    <div id="deposit_history" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_deposithistorycnt') }}</p>
                                                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                                                    {{URL::to('/api/getDeposithistory')}}</p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>No params </span>
                                                    </li>
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <pre><code>{
    "status": "1",
    "Deposit_history": [
        {
            "currency": "EUR",
            "transaction_id": "ttttt",
            "amount": "15000",
            "proof": "",
            "datetime": "2019-05-21 10:22:36",
            "status": "Confirmed",
            "type": "fiat"
        },
    ]
 } </code></pre>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#withdraw_history"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_withdrawhistory') }}</span></a>
                                    </div>
                                    <div id="withdraw_history" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_withdrawhistorycnt') }}</p>
                                                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                                                    {{URL::to('/api/getWithdrawhistory')}}</p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>No params </span>
                                                    </li>
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                   <pre><code>{
    "status": "1",
    "Withdraw_history": [
        {
            "id": "PA_obgJA0ilVGNel0MnsNcPKPWrI-oRKDsjsrnV495Q",
            "currency": "BTC",
            "transaction_id": "",
            "amount": "3",
            "fee": "0.003",
            "address": "3Dc1mYtWVWmLDaFv8ExmTVRGVwDfCGaYEM",
            "datetime": "2019-05-20 11:26:47",
            "status": "Pending",
            "type": "crypto"
        },
        ]
    }</code></pre>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                 
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#cancel_order"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_cancelorder') }}</span></a>
                                    </div>
                                    <div id="cancel_order" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_cancelordercnt') }}</p>
                                                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                                                    {{URL::to('/api/closeOrder')}}</p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>order_id </span>(string) - 1
                                                    </li>
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <pre><code>{
    "status": "1",
    "Cancel amount": "5.00000000",
    "Orderid": "A-900npUhckX9IaNgyzzOZk3pAR0v4S8kXkqD1AGjbI",
    "message": "Order cancelled successfully"
}</code></pre>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>









<div id="heading16" class="card-header">
        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#deposit_amnt"
           aria-expanded="false"
           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">Deposit</span></a>
    </div>
    <div id="deposit_amnt" role="tabpanel" aria-labelledby="heading16" class="collapse">
        <div class="card-content">
            <div class="card-body">
                <p>{{trans('app_lang.api_cancelordercnt') }}</p>
                <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                    {{URL::to('/api/deposit')}}</p>
                <br>
                <h4 class="h4">Parameters:</h4>
                <ul class="api-docs-ul-list">
                    <li><span>currency </span>(string) - BTC 
                    </li>
                </ul>
                <h4 class="h4">Response:</h4>
                <ul class="api-docs-ul-list">
                    <pre>
                        <code>
{
    "status": "1",
    "deposit_address": "{BTC Address}",
    "qrcode": "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl={BTC Address}"
    
}                                                        </code>
                    </pre>
                </ul>
            </div>
        </div>
    </div>









    <div id="heading17" class="card-header">
    <a data-toggle="collapse" data-parent="#accordionWrap1" href="#withdraw_amnt"
       aria-expanded="false"
       aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">Withdraw</span></a>
</div>
<div id="withdraw_amnt" role="tabpanel" aria-labelledby="heading17" class="collapse">
    <div class="card-content">
        <div class="card-body">
            <p>This endpoint used to send withdraw request to admin .</p>
            <p class="round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">POST
                {{URL::to('/api/withdraw')}}</p>
            <br>
            <h4 class="h4">Parameters:</h4>
            <ul class="api-docs-ul-list">
               <ul class="api-docs-ul-list">
                    <li><span>currency </span>(string) - BTC </li>
                    <li><span>amount </span>(float)  0.00000000</li>
                    <li><span>to_address </span>(string)  </li>
                    <li><span>remark </span>(string)  </li>
                </ul>
            </ul>
            <h4 class="h4">Response:</h4>
            <ul class="api-docs-ul-list">
                <pre>
                    <code>
{
    "status": "1",
    "message" : "Withdraw request placed successfully! Please confirm your email"
}                                                        </code>
                </pre>
            </ul>
        </div>
    </div>
</div>





















                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pb-40">
                        <div class="col-lg-12 col-12">
                            <h2 class="title">{{trans('app_lang.api_public') }}</h2>
                            <p>{{trans('app_lang.api_publiccnt') }}</p>
                            <br>
                            <div class="api-docs-collapse" role="tablist" aria-multiselectable="true">
                                <div class="card collapse-icon accordion-icon-rotate">
                                    <div id="heading11" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#stats"
                                           aria-expanded="false"
                                           aria-controls="stats" class="card-title lead collapsed"><span class="gradient-color">{{trans('app_lang.api_returnticker') }}</span></a>
                                    </div>
                                    <div id="stats" role="tabpanel" aria-labelledby="heading11" class="collapse">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_returntickercnt') }}</p>
                                                <p><a href="{{URL::to('/api/returnTicker')}}"
                                                      class="btn round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">GET
                                                   {{URL::to('/api/returnTicker')}}</a></p>
                                                <br>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>No params</span>
                                                    </li>
                                                   
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                <pre><code>{
    "status": 1,
    "data": [
        {
            "first_currency": "BTC",
            "second_currency": "ETH",
            "last_market_price": "0.41000000",
            "lowestaskprice": "0.20000000",
            "highestbidprice": "0.41000000",
            "volume": "4.9700"
        },]}</code></pre>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading12" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#accordion12"
                                           aria-expanded="false"
                                           aria-controls="accordion12" class="card-title lead collapsed"><span
                                                class="gradient-color">{{trans('app_lang.api_getcurrency') }}</span></a>
                                    </div>
                                    <div id="accordion12" role="tabpanel" aria-labelledby="heading12" class="collapse"
                                         aria-expanded="false">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_getcurrencycnt') }}</p>
                                                <p><a href="{{URL::to('/api/getCurrencies')}}"
                                                      class="btn round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">GET
                                                  {{URL::to('/api/getCurrencies')}}</a></p>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>No Params </span> 
                                                    </li>
                                                  
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                     <pre><code>{
    "status": 1,
    "data": [
        {
            "currenycname": "Bitcoin",
            "Symbol": "BTC",
            "min_withdrawlimit": "0.10000000",
            "max_withdrawlimit": "10.00000000"
        },
        {
            "currenycname": "Ethererum",
            "Symbol": "ETH",
            "min_withdrawlimit": "1.00000000",
            "max_withdrawlimit": "0.50000000"
        }
         ]
         }</code></pre></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading13" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#accordion13"
                                           aria-expanded="false"
                                           aria-controls="accordion13" class="card-title lead collapsed"><span
                                                class="gradient-color">{{trans('app_lang.api_orderbook') }}</span></a>
                                    </div>
                                    <div id="accordion13" role="tabpanel" aria-labelledby="heading13" class="collapse"
                                         aria-expanded="false">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_orderbookcnt') }}</p>
                                                <p><a href="{{URL::to('/api/getOrderbook/BTC_EUR')}}"
                                                      class="btn round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">GET
                                                    {{URL::to('/api/getOrderbook/BTC_EUR')}}</a></p>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>pair </span>(string) - BTC_EUR
                                                    </li>
                                                    
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                   <pre><code>{
    "status": "1",
    "result": {
        "pair": "BTC_EUR",
        "asks": [],
        "bids": [
            "[1,0.42]"
        ]
    }
}</code></pre>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="heading14" class="card-header">
                                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#accordion14"
                                           aria-expanded="false"
                                           aria-controls="accordion14" class="card-title lead collapsed"><span
                                                class="gradient-color">{{trans('app_lang.api_markethistory') }}</span></a>
                                    </div>
                                    <div id="accordion14" role="tabpanel" aria-labelledby="heading14" class="collapse"
                                         aria-expanded="false" style="height: 0px;">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <p>{{trans('app_lang.api_markethistorycnt') }}</p>
                                                <p><a href="{{URL::to('/api/getMarketHistory/BTC_EUR')}}"
                                                      class="btn round-theme-btn btn-teal btn-glow btn-lg box-shadow-2">GET
                                                    {{URL::to('/api/getMarketHistory/BTC_EUR')}}</a></p>
                                                <h4 class="h4">Parameters:</h4>
                                                <ul class="api-docs-ul-list">
                                                    <li><span>pair </span>(string) - BTC_EUR
                                                    </li>
                                                    
                                                </ul>
                                                <h4 class="h4">Response:</h4>
                                                <ul class="api-docs-ul-list">
                                                   <pre><code>{
    "status": "1",
    "result": {
        "pair": "BTC_EUR",
        "transaction_history": [
            {
                "datetime": "09:27:24",
                "type": "increase",
                "price": "0.41",
                "amount": "1",
                "from_cur": "BTC",
                "to_cur": "EUR",
                "sell_fee": 0.000123,
                "buy_fee": 0.02,
                "sellordertype": "limit",
                "buyordertype": "market"
            },
        ]
    }</code></pre>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</body>
 </html>


