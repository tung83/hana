$('form#subscribe').on('submit', function (e) {
	e.preventDefault();

	var $form = this;
	var email = $form.email.value.trim();
	var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

	if (!email) return alert("Bạn phải nhập email!");
	if (!regex.test(email)) return alert('Email không đúng!');

	$.ajax({
		type: 'POST',
		url: '/ajax/subscribe',
		data: { email: email },
		success: function() {
			alert("Cám ơn bạn đã đăng ký nhận tin.");
			$form.reset();
		},
		fail: function() {
			alert("Có lỗi xảy ra. Bạn vui lòng thử lại lần sau.");
		}
	})
})

$(document).on('click', '.btn-more', function (e) {
	if (!this.dataset.url) return;

	e.preventDefault();

	var $btn = $(this);
	var url = $btn.data('url');
	var pageCount = $btn.data('pages');
	var page = ($btn.data('page') || 1) + 1;
	var url = '/ajax/' + $btn.data('url') + '&page=' + page;

	$btn.hide();

	$.ajax({
		type: 'GET',
		url: url,
		dataType: 'json',
		success: function(result) {
			console.log(result);
			renderProducts(result.products);
			if (page <= pageCount) {
				$btn.show();
				$btn.prop('page', page);
			}
		},
		fail: function() {
			$btn.show();
		}
	})
})

function renderProducts(products) {
	$list = $('.product-cards');
	for (var i = 0; i < products.length; i++) {
		var prod = products[i];
		var img = prod.img ? '<img src="' + prod.img + '">' : '<img data-src="holder.js/350x350">';
		$item = '\
			<div class="col-sm-3">\
				<div class="product-card card">\
					<a class="thumb" href="' + prod.url + '">\
						' + img + '\
					</a>\
					<div class="info">\
						<a class="title" href="' + prod.url + '">' + prod.title + '</a>\
						<div class="price">\
							<span>' + prod.price + '</span>\
							<a href="/gio-hang/add?id=' + prod.id + '" class="btn-order"></a>\
						</div>\
					</div>\
				</div>\
			</div>\
		';
		$list.append($item);
	}
	Holder.run();
}
