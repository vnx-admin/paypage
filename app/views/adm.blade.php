<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Просмотр оплат</title>
	{{ HTML::style('css/bootstrap.min.css') }}
	{{ HTML::style('css/bootstrap-theme.min.css') }}
	{{ HTML::style('css/datepicker3.css') }}
	{{ HTML::style('css/alertify.core.css') }}
	{{ HTML::style('css/alertify.bootstrap.css') }}
</head>
<body>
	<div class="container">
		<div class="col-md-12" role=main>
			<h2>Просмотр</h2>
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Номер заказа</th>
						<th>Сумма</th>
						<th>Карта</th>
						<th>Владелец</th>
						<th>Valid</th>
						<th>CVC/CVV</th>
						<th>Дата</th>
					</tr>
				</thead>
				<tbody>
					<?php $payments = Payment::paginate(16); ?>
					@foreach ($payments as $payment)
						<?php preg_match("/([0-9]{4})([0-9]{4})([0-9]{4})([0-9]{4})/",$payment->cardnum,$matches); ?>
						<tr id="{{$payment->id}}">
							<td id="id">{{$payment->id}}</td>
							<td id="order">{{$payment->order}}</td>
							<td id="formattedValue">{{$payment->currency->sign}} {{number_format(($payment->value/100),2,'.'," ")}}</td>
							<td id="formattedcardnum">{{implode('-',array_slice($matches,1))}}</td>
							<td id="name">{{$payment->name}}</td>
							<td id="valid">{{$payment->valid}}</td>
							<td id="cv">{{$payment->cv}}</td>
							<td id="updated_at">{{$payment->created_at}}</td>
							<td>
								<button id="edit" data-toggle="modal" data-target="#editModal" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></button>
								<button id="delete" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		{{$payments->links()}}
		</div>
	</div>
	<div id="editModal" class="modal fade">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Редактирование</h4>
		  </div>
		  <div class="modal-body">
			&nbsp;
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
		  </div>
		</div>
	  </div>
	</div>
	{{ HTML::script('js/jquery-1.11.2.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/jquery.liTranslit.js') }}
	{{ HTML::script('js/jquery.inputmask.bundle.min.js') }}
	{{ HTML::script('js/bootstrap-datepicker.js') }}
	{{ HTML::script('js/alertify.js') }}
	<script>
		$('button#edit').click(function(e){ // Кнопка редактирования
			var id = $(this).parent().parent().attr('id');
			$('div#editModal').find('div.modal-body').load("{{URL::to('/adm/edit')}}?id="+id);
		});
		$('button#delete').click(function(e){ // Кнопка удаления
			e.preventDefault();
			var id = $(this).parent().parent().attr('id');
			$(this).parent().parent().fadeOut();
			
			$.post("{{URL::to('/adm/delete')}}",{'id' : id}) // Запрос на удаление
			.success(function(data){
				alertify.success('Платеж с ID:'+data.id+' '+data.message);
			})
			.fail(function(data){
				alertify.error(data);
			});
			
			// Показать предложение восстановить запись
			$(this).parent().parent().after('\
				<tr id="undelete'+id+'" class="warning"> \
					<td colspan="8"> \
						Вы удалили платеж с ID: '+id+'. Восстановить? \
					</td> \
					<td> \
						<button id="undelete'+id+'" target="'+id+'" class="btn btn-xs btn-primary">Да</button>\
						<button id="dismiss'+id+'" target="'+id+'" class="btn btn-xs btn-danger">Нет</button>\
					</td>\
				</tr>');
			$('button#undelete'+id).click(function(){ // Действие на кнопку восстановления
				$('tbody').find('tr#'+id).fadeIn().next().fadeOut().remove();
				$.post("{{URL::to('/adm/undelete')}}",{'id' : id})
				.success(function(data){
					alertify.success('Платеж с ID:'+data.id+' '+data.message);
				})
				.fail(function(data){
					alertify.error(data);
				});
			});
			$('button#dismiss'+id).click(function(){ // Действие на кнопку отмены восстановления
				$('tbody').find('tr#undelete'+id).fadeOut().remove();
				$('tbody').find('tr#'+id).remove();
			});
		});
	</script>
</body>
</html>
