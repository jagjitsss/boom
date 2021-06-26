@extends('wallet.layouts/admin')
<div class="loader" id="myLoad">
  <div class="spinner">
    <div class="double-bounce1"></div>
    <div class="double-bounce2"></div>
  </div>
</div>
@section('content')
<?php if (Session::has('success')) {?>
<div role="alert" class="alert alert-success" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Success!</strong><?php echo Session::get('success'); ?> </div>
<?php }?>
<?php if (Session::has('error')) {?>
<div role="alert" class="alert alert-danger" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Oh!</strong><?php echo Session::get('error'); ?> </div>
<?php }?>

<ul class="breadcrumb cm_breadcrumb">
  <li><a href="#">Home</a></li>
</ul>
<div class="mainWrapper">
  <div class="cardsTopSec mb-20">
    <div class="row">
      @foreach($allcurr as $curr)
    
      <div class="col-md-4 col-sm-6">
        <div class="cardsBlk cardsClr4">
          <p>Wallet {{$curr['symbol']}} Balance </p>
          <div class="midSec">
           {{ getadminBalance($curr['symbol']) }} 
          </div>
        </div>
      </div>
     @endforeach
    </div>
  </div>
  <ul id="draggablePanelList" class="list-unstyled">
    <li class="panel">
    </li>
    <li class="panel">
    </li>
    <li class="panel">
    </li>
  </ul>
</div>

<script type="text/javascript">

$('.showresult').click(function(){
$('.searc_drop').css('display','block');
});

$(document).mouseup(function(e)
{
var container = $(".searc_drop");

if (!container.is(e.target) && container.has(e.target).length === 0)
{
 $('.searc_drop').css('display','none');
}
});



$(document).ready(function(e) {
$('.setting_drp input').lc_switch();


$('body').delegate('.lcs_check', 'lcs-statuschange', function() {
var status = ($(this).is(':checked')) ? 'checked' : 'unchecked';
console.log('field changed status: '+ status );
});


$('body').delegate('.lcs_check', 'lcs-on', function() {
console.log('field is checked');
});


$('body').delegate('.lcs_check', 'lcs-off', function() {
console.log('field is unchecked');
});
});

</script>
<script>
jQuery(function($) {
    var panelList = $('#draggablePanelList');

    panelList.sortable({

        handle: '.panel-heading',
        update: function() {
            $('.panel', panelList).each(function(index, elem) {
                 var $listItem = $(elem),
                     newIndex = $listItem.index();
            });
        }
    });
});
</script>
<script>
(function () {
    var Message;
    Message = function (arg) {
        this.text = arg.text, this.message_side = arg.message_side;
        this.draw = function (_this) {
            return function () {
                var $message;
                $message = $($('.message_template').clone().html());
                $message.addClass(_this.message_side).find('.text').html(_this.text);
                $('.messages').append($message);
                return setTimeout(function () {
                    return $message.addClass('appeared');
                }, 0);
            };
        }(this);
        return this;
    };
    $(function () {
        var getMessageText, message_side, sendMessage;
        message_side = 'right';
        getMessageText = function () {
            var $message_input;
            $message_input = $('.message_input');
            return $message_input.val();
        };
        sendMessage = function (text) {
            var $messages, message;
            if (text.trim() === '') {
                return;
            }
            $('.message_input').val('');
            $messages = $('.messages');
            message_side = message_side === 'left' ? 'right' : 'left';
            message = new Message({
                text: text,
                message_side: message_side
            });
            message.draw();
            return $messages.animate({ scrollTop: $messages.prop('scrollHeight') }, 300);
        };
        $('.send_message').click(function (e) {
            return sendMessage(getMessageText());
        });
        $('.message_input').keyup(function (e) {
            if (e.which === 13) {
                return sendMessage(getMessageText());
            }
        });
        sendMessage('Hello Philip! :)');
        setTimeout(function () {
            return sendMessage('Hi Sandy! How are you?');
        }, 1000);
        return setTimeout(function () {
            return sendMessage('I\'m fine, thank you!');
        }, 2000);
    });
}.call(this));
</script>
<script src="{{asset('/').('public/admin_assets/js/moment.min.js')}}"> </script>

<script type = "text/javascript">
  setTimeout(function(){
     document.getElementById("myLoad").style.display="none";
  }, 3000);
</script>

@stop