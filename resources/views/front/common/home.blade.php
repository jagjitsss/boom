
<div class="container-fluid index-banner">
  <div class="banner-content">
    <div class="container">

        <div class="banner_content">

        <?php echo getStaticContent('homepage_banner_content')->content; ?>
        
            </div>
          </div>
        </div>  
      </div>
      <div class="container banner-under-boox">
      <div class="banner-under-boompay">
        <div class="row justify-content-center d-flex">

          <?php 
          $currencyList = getAllCurrency('crypto');
          $usdPairDetail = getCurrencyid('USD');
          foreach($currencyList as $key => $value)
          {
            
            $image = isset($value->image)?$value->image:'';
            $name = isset($value->name)?$value->name:'';
            $coinmrkt_widget = isset($value->coinmrkt_widget)?$value->coinmrkt_widget:'';
            
          ?>

          <div class="col-md-4">
            <div class="boompay-box d-flex">
              <div>
                @if($image)
                  <img class="img-fluid" src="{{url('public/assets/images/'.$image)}}" alt="">
                @endif
                </div>
              <div>
                <h3>  
                  {{$name}}
                  <img class="img-fluid" src="{{url('public/assets/images/img6.png')}}" alt=""></h3>
                <p>
                  $<?php echo $inr_value = isset($value->inr_value)?$value->inr_value:0; ?>
                </p>
              </div>
              
                <div>
                  @if($coinmrkt_widget)
                  <img class="img-fluid" src="{{$coinmrkt_widget}}" alt="">
                  @endif
                </div>
              
            </div>
          </div>
          <?php 
          }
          ?>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
</div>





  

<?php   ?>

<div class="sec_two">
  <div class="container">
    <h1 class="text-center text-black font34 fwbold"> <?php echo getStaticContent('homepage_market_value')->title; ?></h1>
    <p class="text-center text-gray font14"><?php echo getStaticContent('homepage_market_value')->content; ?></p>
    <section class="market_tab">
      <table>
        <tbody>
          <tr>
            <th width="20%" scope="col">Name</th>
            <th width="20%" scope="col">Last Price</th>
            <th width="20%" scope="col">24H change</th>
            <th width="20%" scope="col">Market</th>
            <th width="20%" scope="col">Action </th>
          </tr>


          <?php

          $currency_pairs_details = currency_pairs_details_home();

           $currencyList = getAllCurrency('crypto');
          $usdPairDetail = getCurrencyid('USD');
          foreach($currency_pairs_details as $key => $value)
          {

            $image = getCurrencyImage($value->to_symbol);
            $name = getCurrencyname($value->to_symbol);
            $coinmrkt_widget = getCurrencyCoinMrkWidget($value->to_symbol);

            $pairs = $value->to_symbol.'_'.$value->from_symbol;
            ?>
          <tr>
            <td>
              @if($image)
                  <img class="img-fluid" src="{{url('public/assets/images/'.$image)}}" alt="">
              @endif
              {{$value->to_symbol}}
            </td>
            <td>$<?php echo getCurrencyLastPrice($value->to_symbol); ?>


            <td>
              <span class="text-green">
                $<?php echo $volumeVal = isset($value->volume)?$value->volume:0; ?>
                  
                </span>
            </td>
            <td>
               @if($coinmrkt_widget)
                  <img class="img-fluid" src="{{$coinmrkt_widget}}" alt="">
                  @endif
            </td>
            <td><a href="<?php echo url('/trade/'.$pairs); ?>" class="btn-primary">Trade</a></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </section>
  </div>
</div>
<div class="benifit_sec">
  <div class="container">
     <?php echo getStaticContent('homepage_boompay_features')->content; ?>
  </div>
</div>

<div class="sec_reg_new">
  <?php echo getStaticContent('homepage_most_trusted_secure_crypto_wallet')->content; ?>
</div>

<script>

  var email_exist_subs ="{{trans('app_lang.email_exist') }}";
  var provide_email_address_subs ="{{trans('app_lang.provide_email_address') }}";
  var valid_email_subs ="{{trans('app_lang.enter_valid_email') }}";
  var no_records   =  "{{trans('app_lang.no_records_available') }}";
</script> 

<script src="{{asset('/').('public/assets/js/swiper.min.js')}}"></script> 

<script>
    var swiper = new Swiper('.swiper-container', {
      direction: 'vertical',
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });
  </script> 
