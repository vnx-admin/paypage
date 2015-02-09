var showSuccess = function (id) { // Показать, что поле проверено
	$('div#'+id).removeClass('has-error').addClass('has-success');
	$('span#'+id+'Warning').removeClass('glyphicon-alert').addClass('glyphicon-ok').show();
}
var showError = function (id) {
	$('div#'+id).removeClass('has-success').addClass('has-error'); // Показать ошибку
	$('span#'+id+'Warning').removeClass('glyphicon-ok').addClass('glyphicon-alert').show();
}

var hideFeedback = function (id) { // Скрытие фидбека
	$('div#'+id).removeClass('has-success').removeClass('has-error');
	$('span#'+id+'Warning').removeClass('glyphicon-ok').removeClass('glyphicon-alert').hide();
}

var checkFields = new Object();

checkFields.orderNum = function(elem) {
	var id = elem.attr('id');
	if (elem.val()!=='0' && elem.val()!=='') {
		showSuccess(id);
		return true;
	}
	else {
		showError(id);
		return false;
	}
};
checkFields.orderValue = function(elem) {
	var id = elem.attr('id');
	console.log(elem.val());
	if (elem.val()!=='0.00' && elem.val()!=='') {
		showSuccess(id);
		return true;
	}
	else {
		showError(id);
		return false;
	}
};
checkFields.cardHolder = function(elem) {
	var id = elem.attr('id');
	if (elem.val()!=='' && elem.val().search(/[0-9]/)) {
		showSuccess(id);
		return true;
	}
	else {
		showError(id);
		return false;
	}
};
checkFields.cardCv = function(elem) {
	var id = elem.attr('id');
	if (elem.val()!=='' && elem.val().length===3) {
		showSuccess(id);
		return true;
	}
	else {
		showError(id);
		return false;
	}
};
checkFields.validDate = function(elem) {
	var id = elem.attr('id');
	if (elem.val()!=='' && elem.val().search(/(0[1-9]|1[012]) \/ [0-9]{2}/)!==-1) {
		showSuccess(id);
		return true;
	}
	else {
		showError(id);
		return false;
	}
};
checkFields.cardNum = function(elem) { // Функция проверки номера карты по алгоритму Луна.
	var id = elem.attr('id');
	var value = elem.val().replace(/-/g,'',1);
	if (value.search(/^[0-9]{16}$/)===-1) {
		showError(id);
		return false;
	}
	var res=0;
	for (var i=0;i<=15;i++) {
		if (i%2) {
			res+=parseInt(value[i]);
		}
		else {
			var tmp=value[i]*2;
			if (tmp>9) {
				tmp-=9;
			}
			res+=tmp;
		}
	}
	if (res%10) {
		showError(id);
		return false;
	}
	else {
		showSuccess(id);
		return true;
	}
};

