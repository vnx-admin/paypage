<?php
	$payment = Payment::find(Input::get('id'));
	preg_match("/([0-9]{4})([0-9]{4})([0-9]{4})([0-9]{4})/",$payment->cardnum,$matches);
	$paymentCard = implode('-',array_slice($matches,1));
?>

<form id="editform" method="post" action="">
	<div class="col-xs-12">
		<div id="orderNum" class="form-group has-feedback">
			<div class="input-group">
				<div class="input-group-addon"><span class=" glyphicon glyphicon-barcode"></span></div>
				<input id="orderNum" class="form-control" type="text" name="orderNum" placeholder="Номер заказа" value="{{$payment->order}}">
			</div>
			<span id="orderNumWarning" class="form-control-feedback glyphicon" style="display:none;"></span>
		</div>
	</div>
	<div class="col-xs-12">
		<div id="orderValue" class="form-group has-feedback">
			<div class="input-group">
				<div class="input-group-btn" role="group">
					<button id="rub" type="button" class="btn @if ($payment->currency->name == 'rub') btn-warning @else btn-default @endif"><span class="glyphicon glyphicon-ruble"></span></button>
					<button id="usd" type="button" class="btn @if ($payment->currency->name == 'usd') btn-warning @else btn-default @endif"><span class="glyphicon glyphicon-usd"></span></button>
				</div>
				<input id="orderCurrency" type="hidden" name="orderCurrency" value="{{$payment->currency->name}}">
				<input id="orderValue" class="form-control" type="text" name="orderValue" placeholder="Сумма" value="{{$payment->value/100}}">
			</div>
			<span id="orderValueWarning" class="form-control-feedback glyphicon" style="display:none;"></span>
		</div>
	</div>
	<div class="col-xs-12">
		<div id="cardNum" class="form-group has-feedback">
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></div>
				<input id="cardNum" class="form-control" type="text" name="cardNum" placeholder="Номер карты" value="{{$paymentCard}}">
			</div>
			<span id="cardNum" style="width:86px;" class="form-control-feedback" style="display:none;" aria-hidden="true"><img></span>
			<span id="cardNumWarning" class="form-control-feedback glyphicon" style="display:none;"></span>
		</div>
	</div>
	<div class="col-xs-12">
		<div id="cardHolder" class="form-group has-feedback">
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
				<input id="cardHolder" class="form-control" type="text" name="cardHolder" placeholder="Владелец" value="{{$payment->name}}">
			</div>
			<span id="cardHolderWarning" class="form-control-feedback glyphicon" style="display:none; width:56px;"></span>
		</div>
	</div>
	<div class="col-xs-6">
		<div id="validDate" class="form-group has-feedback">
			<div class="input-group date">
				<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
				<input type="text" id="validDate" name="validDate" class="form-control" placeholder="Годен до" value="{{$payment->valid}}">
			</div>
				<span id="validDateWarning" class="form-control-feedback glyphicon" style="display:none; width:56px;"></span>
		</div>
	</div>
	<div class="col-xs-6">
		<div id="cardCv" class="form-group has-feedback">
			<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
				<input id="cardCv" class="form-control" type="text" name="cardCv" placeholder="CVC/CVV" size="3" maxlength="3" value="{{$payment->cv}}">
			</div>
			<span id="cardCvWarning" class="form-control-feedback glyphicon" style="display:none;"></span>
		</div>
	</div>
	<div class="col-xs-4">
		<button id="save" type="button" class="btn btn-primary">Сохранить</button>
	</div>
</form>
&nbsp;</br>
&nbsp;
{{ HTML::script('js/fieldmask.js')}}
{{ HTML::script('js/payform.js')}}
<script>
	// Скрытие фидбека при изменении поля
	$('input.form-control').keydown(function(){
		hideFeedback($(this).attr('id'));
	});
	$('button#save').click(function(e){
		var err = false;
		e.preventDefault;
		$('div.form-group').each(function(){ //Проверяем поля
			if (!checkFields[$(this).attr('id')]($(this).find('input.form-control'))) {
				err = true;
			}
		});

		if (!err) { //Если ошибок нет
			$('div.has-error').removeClass('has-error');
			$('span.glyphicon-alert').removeClass('glyphicon-alert');
			var param = new Object();
			param['id'] = {{$payment->id}}
			$('div.form-group').find('input').each(function(){
				param[$(this).attr('name')]=$(this).val();
			});
			$.post("{{URL::to('/adm/edit')}}",param)
			.success(function(data){
				if (data.message) {
					alertify.success(data.message);
					$('div#editModal').modal('hide').find('div.modal-body').html('');
					$('tbody').find('tr#{{$payment->id}}').find('td').each(function(){
						$(this).html(data.payment[$(this).attr('id')]);
					});
				}
				else {
					var msg = '';
					$.each(data.err,function(index,value){
						msg+='['+value.code+'] '+value.message+'</br>';
					});
					alertify.error(msg);
				}
			})
			.fail(function(data){
				alertify.error('Ошибка');
			});
		}

	});
</script>
