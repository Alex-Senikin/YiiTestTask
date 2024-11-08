
var sortDirection = "ASC";
var sortAs = "id";
//Сохранение изменений из модалки в бд
$('#form').on('beforeSubmit', function(event) {
	var offerCount = $('.offerblock').length;
    // Проверка на наличие ID и перенаправленияна нужный route
    if ($('form input[name="Offers[id]"]').val()>0) {
        // Если ID есть обновляем данные
        url = '/offers/update';
        data = {
            id: $('form input[name="Offers[id]"]').val(),
            offerName: $('form input[name="Offers[offerName]"]').val(),
            email: $('form input[name="Offers[email]"]').val(),
            phoneNumber: $('form input[name="Offers[phoneNumber]"]').val(),
            createdAt: $('form input[name="Offers[createdAt]"]').val(),
        }
    } else {
        // Если ID нет создаем новую запись
        url = '/offers/create'
        data = {
            offerName: $('form input[name="Offers[offerName]"]').val(),
            email: $('form input[name="Offers[email]"]').val(),
            phoneNumber: $('form input[name="Offers[phoneNumber]"]').val(),
        }
    };
	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'json',
		data: data,
		success: function(res) {
			if (res.success) {
                // Если сервер вернул success показываем уведомление что всё успешно 
                //и вносим изменения на страницу
				var id = res.data.id;
				$('.notifications').css({'border': '1px solid green', 'color':'green', 
					'opacity':'1', 'transition':'2'});
				setTimeout(() => {
					$('.notifications').css({'opacity':'0'})
				}, 3000);
				if ($("#"+id).length) {
                    //если существует элемент с таким ID изменяем его
					$('.notifications').text('Успешно изменено');
					$("#"+id).html(res.html);
				} else {
                    //Если нет добавляем новый при условии что на странице еще не максимум записей
					$('.notifications').text('Успешно добавлено');
					if (offerCount < 10) {
						$('.offers-index').append(res.html)
					}
				}
                //Скрываем модалку
				$('#modalWin').modal('hide');
			} else {
                // Если сервер вернул ошибку показываем уведомление с ошибкой
				$('.notifications').css({'border': '1px solid red', 'color':'red', 
					'opacity':'1', 'transition':'2'});
				$('.notifications').text(res.error.email[0]);
				setTimeout(() => {
					$('.notifications').css({'opacity':'0'})
				}, 3000);
			}
			
		},
		error: function(){
			alert('Error!');
		}
	});
    // Возвращаем false чтобы страница не перезагружалась
	return false;
});
//Открытие модалки для редактирования оффера
function view(id) {
    //Открываем модалку и запрашиваем данные об оффере из БД
	$('#modalWin').modal('show');
	$.ajax({
		url: '/offers/view',
		type: 'POST',
		dataType: 'json',
		data: {
			id: id,
		},
		success: function(res) {
            // Заполняем форму данными оффера
			$('form').trigger('reset'); // Сбрасываем форму
			$('form input[name="Offers[id]"]').val(res.data.id);
			$('form input[name="Offers[offerName]"]').val(res.data.offerName);
			$('form input[name="Offers[email]"]').val(res.data.email);
			$('form input[name="Offers[phoneNumber]"]').val(res.data.phoneNumber);
			$('form input[name="Offers[createdAt]"]').val(res.data.createdAt);
		},
		error: function(xhr, status, error) {
			console.error('AJAX error:', status, error);
			alert('Ошибка при получении данных оффера: ' + xhr.responseText);
		}
	})
}
//Удаление оффера
function del(id) {
    //Запрос на подтверждение удаления
	var result = confirm('Вы уверены что хотите удалить оффер под ID:' + id);
	if (result == true) {
		$.ajax({
			url: '/offers/delete',
			type: 'POST',
			dataType: 'json',
			data: {
				id: id,
			},
			success: function(res) {
                //После удаления из бд убираем элемент со страницы
				$('#' + res.data.id).remove();
			},
			error: function(xhr, status, error) {
				console.error('AJAX error:', status, error);
				alert('Ошибка при получении данных оффера: ' + xhr.responseText);
			}
		})
	};
};
// Открытие модалки для создания оффер
$('.create').on('click', function() {
    // Открываем модалку и очищаем все поля
	$('#modalWin').modal('show');
	$('#form').trigger('reset');
	$('form input[name="Offers[id]"]').val('');
	$('form input[name="Offers[createdAt]"]').val('');
});
// Сортировка по ID
$('#id').on('click', function() {
    //Меняем направление сортировки
	if (sortDirection == 'DESC') {
		sortDirection = 'ASC';
	} else {
		sortDirection = 'DESC';
	};
	sortAs = "id";
    // Берем из url номер страницы
	const url = window.location.href;
	const lastPart = url.split('/').pop();
	const lastDigit = parseInt(lastPart.split('.').pop()).toString().charAt(0);
	$.ajax({
		url: '/offers/sort',
		type: 'POST',
		dataType: 'json',
		data: {
			sortAs: $(this)[0].id,
			sortDirection: sortDirection,
			page : lastDigit,
		},
		success: function(res) {
            //Применяем сортировку на страницу
			$('.offers').replaceWith(res.html);
		},
		error: function(xhr, status, error) {
			console.error('AJAX error:', status, error);
			alert('Ошибка при получении данных оффера: ' + xhr.responseText);
		}
	})
});

// Сортировка по названию оффера
$('#offerName').on('click', function() {
    //Меняем направление сортировки
	if (sortDirection == 'DESC') {
		sortDirection = 'ASC';
	} else {
		sortDirection = 'DESC';
	};
	sortAs = "offerName";
    // Берем из url номер страницы
	const url = window.location.href;
	const lastPart = url.split('/').pop();
	const lastDigit = parseInt(lastPart.split('.').pop()).toString().charAt(0);
	$.ajax({
		url: '/offers/sort',
		type: 'POST',
		dataType: 'json',
		data: {
			sortAs: $(this)[0].id,
			sortDirection: sortDirection,
			page : lastDigit,
		},
		success: function(res) {
            //Применяем сортировку на страницу
			$('.offers').replaceWith(res.html);
		},
		error: function(xhr, status, error) {
			console.error('AJAX error:', status, error);
			alert('Ошибка при получении данных оффера: ' + xhr.responseText);
		}
	})
});
// фильтрация офферов по Email и Названию оффера
$('.filter').on('input', function() {
    //Отправляем введенные данные в input на сервер
	$.ajax({
		url: '/offers/find',
		type: 'POST',
		dataType: 'json',
		data: {
			findParam: $('.filter').val(),
		},
		success: function(res) {
            //Обновляем страницу с найденными данными
			$('.offers').replaceWith(res.html);
		},
		error: function(xhr, status, error) {
			console.error('AJAX error:', status, error);
			alert('Ошибка при получении данных оффера: ' + xhr.responseText);
		}
	})
})