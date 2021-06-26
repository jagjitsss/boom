<?php $seg= Request::segment(2);?>
<div class="tab-pane container-fluid no-padding active" id="bankwire"> 
  
  <?php echo Form::open(array('id'=>'bank_wire','url'=>'bankwire','class'=>'add-coin-form-cnt','method'=>'POST', 'enctype' => "multipart/form-data",'onsubmit'=>'bank_load()')); ?>

  <div class="add-coin-cnt flo-unset">
  <div class="inner_banner">
          <div class="inner-sec-top-menu">
  <div class="container">
  
  <ul class="inner-sec-menu">
          <li><a href="<?php echo url('/dashboard'); ?>"><i class="fa fa-th-large" aria-hidden="true"></i> Dashboard</a></li>
          <li><a href="<?php echo url('/buy-sell'); ?>" ><i class="fa fa-arrows-h" aria-hidden="true"></i> Buy/Sell</a></li>
          <li><a href="<?php echo url('/bankwire/USD'); ?>" class="active"><i class="fa fa-folder" aria-hidden="true"></i> Bank</a></li>

          <li><a href="<?php echo url('/editprofile'); ?>"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>

            <li><a href="<?php echo url('/profile'); ?>"><i class="fa fa-user" aria-hidden="true"></i>Profile</a></li>
  </ul>

  </div>
</div>        </div>
<div class="container">
    <div class="add-coin-container bank-page-box">
      <div class="add-coin-header text-left"><?php echo e(trans('app_lang.bankwire')); ?></div>
      <div class="form-group">
        <label>Currency</label>
        <select name="currency" class="form-control" onchange="curchange(this.value)">
          <option id="USD" <?php if($seg == 'USD'){ echo "selected";}?>>USD</option>
          <option id="EUR" <?php if($seg == 'EUR'){ echo "selected";}?>>EUR</option>
          <option id="GBP" <?php if($seg == 'GBP'){ echo "selected";}?>>GBP</option>
        </select>
      </div>
      <div class="add-coin-form-row">
        <div class="add-coin-form-txtbox-cnt">
          <div class="add-coin-txtbox-label"><?php echo e(trans('app_lang.accountholdername')); ?><a style="color: red">*</a></div>
          <?php if($bankwire == NULL) { ?>
          <div class="add-coin-txtbox">
            <input type="text" name="accountholdername" placeholder="<?php echo e(trans('app_lang.accountholdername')); ?>">
          </div>
          <?php } else { ?>
          <div class="add-coin-txtbox">
            <input type="text" name="accountholdername" value="<?php echo e($bankwire->accountholdername); ?>">
          </div>
          <?php } ?>
        </div>
        <div class="add-coin-form-txtbox-cnt">
          <div class="add-coin-txtbox-label"><?php echo e(trans('app_lang.accountnumber')); ?><a style="color: red">*</a></div>
          <?php if($bankwire == NULL) { ?>
          <div class="add-coin-txtbox">
            <input type="text"  name="accountnumber" placeholder="<?php echo e(trans('app_lang.accountnumber')); ?>">
          </div>
          <?php } else { ?>
          <div class="add-coin-txtbox">
            <input type="text" name="accountnumber" value="<?php echo e($bankwire->accountno); ?>">
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="add-coin-form-row">
        <div class="add-coin-form-txtbox-cnt">
          <div class="add-coin-txtbox-label"><?php echo e(trans('app_lang.swift')); ?><a style="color: red">*</a></div>
          <?php if($bankwire == NULL) { ?>
          <div class="add-coin-txtbox">
            <input type="text" name="swift" value="000000000" placeholder="<?php echo e(trans('app_lang.swift')); ?>">
          </div>
          <?php } else { ?>
          <div class="add-coin-txtbox">
            <input type="text" name="swift" value="<?php echo e($bankwire->swift); ?>">
          </div>
          <?php } ?>
        </div>
        <div class="add-coin-form-txtbox-cnt">
          <div class="add-coin-txtbox-label">Routing Number<a style="color: red">*</a></div>
          <?php if($bankwire == NULL) { ?>
          <div class="add-coin-txtbox">
            <input type="text"  value="000000000" name="routing" placeholder="<?php echo e(trans('app_lang.routing')); ?>">
          </div>
          <?php } else { ?>
          <div class="add-coin-txtbox">
            <input type="text"  name="routing" value="<?php echo e($bankwire->routingno); ?>">
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="add-coin-form-row">
        <div class="add-coin-form-txtbox-cnt">
          <div class="add-coin-txtbox-label"><?php echo e(trans('app_lang.bankname')); ?><a style="color: red">*</a></div>
          <?php if($bankwire == NULL) { ?>
          <div class="add-coin-txtbox">
            <input type="text" name="bankname" placeholder="<?php echo e(trans('app_lang.bankname')); ?>">
          </div>
          <?php } else { ?>
          <div class="add-coin-txtbox">
            <input type="text" name="bankname" value="<?php echo e($bankwire->bankname); ?>">
          </div>
          <?php } ?>
        </div>
        <div class="add-coin-form-txtbox-cnt">
          <div class="add-coin-txtbox-label"><?php echo e(trans('app_lang.bankaddress')); ?><a style="color: red">*</a></div>
          <?php if($bankwire == NULL) { ?>
          <div class="add-coin-txtbox">
            <input type="text"  name="bankaddress" placeholder="<?php echo e(trans('app_lang.bankaddress')); ?>">
          </div>
          <?php } else { ?>
          <div class="add-coin-txtbox">
            <input type="text" name="bankaddress" value="<?php echo e($bankwire->bankaddress); ?>">
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="add-coin-form-row">
        <div class="sm-12 d-flex">
          <button type="submit" class="dsb-blue-btn mx-auto bankwire_sub"><?php echo e(trans('app_lang.submit_bankwire')); ?></button>
        </div>
      </div>
    </div>
		  </div>
  </div>
  <?php echo Form::close(); ?> </div>
<script>
  var require_field_cos = "<?php echo e(trans('app_lang.field_require')); ?>"; 
  var bankwire = "<?php echo e(trans('app_lang.submit_bankwire')); ?>"; 
  function curchange(val)
  {
  	 window.location.href = siteurl+'/bankwire/'+val;;
  }
</script>