<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Страница оплаты</title>
	{{ HTML::style('css/bootstrap.min.css') }}
	{{ HTML::style('css/bootstrap-theme.min.css') }}
	{{ HTML::style('css/datepicker3.css') }}
</head>
<body>
	<div class="container">
		<div class="col-sm-6 col-sm-offset-3" role=main>
			<div class="col-xs-12">
				<h2>Страница оплаты</h2><br/>
			</div>
			<form id="payform" method="post" action="">
				<div class="col-xs-6">
					<div id="orderNum" class="form-group has-feedback">
						<div class="input-group">
							<div class="input-group-addon"><span class=" glyphicon glyphicon-barcode"></span></div>
							<input id="orderNum" class="form-control" type="text" name="orderNum" placeholder="Номер заказа">
						</div>
						<span id="orderNumWarning" class="form-control-feedback glyphicon" style="display:none;"></span>
					</div>
				</div>
				<div class="col-xs-6">
					<div id="orderValue" class="form-group has-feedback">
						<div class="input-group">
							<input id="orderValue" class="form-control" type="text" name="orderValue" placeholder="Сумма">
							<input id="orderCurrency" type="hidden" name="orderCurrency" value="rub">
							<div class="input-group-btn" role="group">
								<button id="rub" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-ruble"></span></button>
								<button id="usd" type="button" class="btn btn-default"><span class="glyphicon glyphicon-usd"></span></button>
							</div>
						</div>
						<span id="orderValueWarning" style="width:200px;" class="form-control-feedback glyphicon" style="display:none;"></span>
					</div>
				</div>
				<div class="col-xs-12">
					<div id="cardNum" class="form-group has-feedback">
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></div>
							<input id="cardNum" class="form-control" type="text" name="cardNum" placeholder="Номер карты">
						</div>
						<span id="cardNum" style="width:100px;" class="form-control-feedback" style="display:none;" aria-hidden="true"><img></span>
						<span id="cardNumWarning" class="form-control-feedback glyphicon" style="display:none;"></span>
					</div>
				</div>
				<div class="col-xs-12">
					<div id="cardHolder" class="form-group has-feedback">
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
							<input id="cardHolder" class="form-control" type="text" name="cardHolder" placeholder="Владелец">
						</div>
						<span id="cardHolderWarning" class="form-control-feedback glyphicon" style="display:none;"></span>
					</div>
				</div>
				<div class="col-xs-6">
					<div id="validDate" class="form-group has-feedback">
						<div class="input-group date">
							<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
							<input type="text" id="validDate" name="validDate" class="form-control" placeholder="Срок действия">
						</div>
							<span id="validDateWarning" class="form-control-feedback glyphicon" style="display:none;"></span>
					</div>
				</div>
				<div class="col-xs-6">
					<div id="cardCv" class="form-group has-feedback">
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
							<input id="cardCv" class="form-control" type="password" name="cardCv" placeholder="CVC/CVV" size="3" maxlength="3">
						</div>
						<span id="cardCvWarning" class="form-control-feedback glyphicon" style="display:none;"></span>
					</div>
				</div>
				<div class="col-xs-4 col-xs-offset-4">
					<button class="form-control btn-primary" type="submit">Оплатить</button>
				</div>
				
			</form>
		</div>
	</div>
	{{ HTML::script('js/jquery-1.11.2.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/jquery.liTranslit.js') }}
	{{ HTML::script('js/jquery.inputmask.bundle.min.js') }}
	{{ HTML::script('js/bootstrap-datepicker.js') }}
	{{ HTML::script('js/fieldmask.js')}}
	{{ HTML::script('js/payform.js')}}
	<script>
		// Проверка полей
		$('input.form-control').focusout(function() {
			return checkFields[$(this).attr('id')]($(this));
		})
		.keydown(function(){ // Скритие фидбека при изменении
			hideFeedback($(this).attr('id'));
		});	
		$('form#payform').submit(function(e){ // Сабмит формы
			$('div.form-group').each(function(){
				if (!checkFields[$(this).attr('id')]($(this).find('input.form-control'))) {
					e.preventDefault();
				}
			});
		});
	</script>
</body>
</html>
