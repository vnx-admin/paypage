// Валюта операции
$('button#rub').click(function (e){
	e.preventDefaut;
	$(this).addClass('btn-warning').removeClass('btn-default');
	$('button#usd').addClass('btn-default').removeClass('btn-warning');
	$('input#orderCurrency').val($(this).attr('id'));
	return ;
});
$('button#usd').click(function (e){
	e.preventDefaut;
	$(this).addClass('btn-warning').removeClass('btn-default');
	$('button#rub').addClass('btn-default').removeClass('btn-warning');
	$('input#orderCurrency').val($(this).attr('id'));
	return ;
});
// Маски для полей
$('input#orderNum').inputmask({'alias': 'decimal',rightAlign: false}); // Маска для поля номера заказа
$('input#orderValue').inputmask({
	'alias': 'numeric',
	'groupSeparator': ' ',
	'autoGroup': true,
	'digits': 2,
	'digitsOptional': false,
	'placeholder': '0',
	'rightAlign': false
});

$('input#validDate').inputmask({mask:'99 / 99'});

// Datepicker для поля срока годности карты
$('div.input-group.date').datepicker({ 
	format: "mm / yy",
	startDate: "1",
	minViewMode: 1,
	language: "ru",
	startView: 2
});

// Маска ввода карты и проверка суммы
$('input#cardNum').inputmask({mask:'9999-9999-9999-9999',oncomplete:function(){
	checkFields.cardNum($(this));
},
onincomplete:function (){
	showError($(this).attr('id'));
}});
// Тип карты (VISA/MasterCard)	
$('input#cardNum').keyup(function (){
	if ($(this).val()[0]==='5') {
		$('span#cardNum').show().find('img').attr('src','img/mc-color.png');
	}
	else if ($(this).val()[0]==='4') {
		$('span#cardNum').show().find('img').attr('src','img/visa-color.png');
	}
	else {
		$('span#cardNum').hide();
	}
	return ;
});
// Автотранслит и автоформат имени/фамилии
$('input#cardHolder').liTranslit({
	caseType	:	'upper',
	reg			:	'"1"="","2"="","3"="","4"="","5"="","6"="","7"="","8"="","9"="","0"=""'
});
