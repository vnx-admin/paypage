<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Результат</title>
	{{ HTML::style('css/bootstrap.min.css') }}
	{{ HTML::style('css/bootstrap-theme.min.css') }}
</head>
<body>
	<div class="container">
		<div class="col-sm-6 col-sm-offset-3" role=main>
			@if (isset($err))
				@foreach ($err as $e)
					[{{$e['code']}}] {{$e['message']}} <br/>
				@endforeach
			@else
				<h2>Спасибо</h2>
				Заказ №{{$orderNum}} {{$message}}.
			@endif
		</div>
	</div>
	{{ HTML::script('js/jquery-1.11.2.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
</body>
</html>
