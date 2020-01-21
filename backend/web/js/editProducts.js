$(document).ready( () => {
	$('a.buy').click( (e) => {
		let id = $(e.target).attr('data-id'),
			dataObj = {
				'id': id,
				'title': $(e.target).parents('div.product').find('[data-key="title"]').text()
			};

		$('ul[data-id='+ id +']').find('li').each( (i, elem) =>{
			let key = $(elem).attr('data-key'), val = $(elem).attr('data-value');
			dataObj[key] = val;
		});

		if( $('#edit').length > 0 ) return;
		let template = $('#block-edit')[0];
		let copyTempEdit = template.content.cloneNode(true);

		$(copyTempEdit).find('h3.title').text("Edit "+dataObj['title'] + "(id=" + id + ")");

		for(let key in dataObj) {
			if( key == 'id') continue;
			let input = $(copyTempEdit).find('form').find('input[name="'+key+'"]');
			$(input).val(dataObj[key]);
			if( key == 'title') continue;
			$( input ).on('input', e => {
				if( key == 'price') 
					$(e.target).val($(e.target).val().replace(/([^0-9.])/g, ''));
				else $(e.target).val($(e.target).val().replace(/\D/g, ''));
			});
		}

		$(copyTempEdit).find('form').find('input[type="text"]').on('change', e =>{
			if ( $(e.delegateTarget).val() != dataObj[$(e.delegateTarget).attr('name')] )
				$(e.delegateTarget).attr('data-edited', true);
			else $(e.delegateTarget).attr('data-edited', false);
		});

		$(copyTempEdit).find('form').on('change', e => {
			if ($(e.delegateTarget).find('input[data-edited="true"]').length > 0)
				$(e.delegateTarget).find('a.btn-change').removeAttr('disabled');
			else $(e.delegateTarget).find('a.btn-change').attr('disabled', true);
		});

		$(document).on("closeCurtain", (e) => {
			dataObj = {};
			$('#edit').remove();
			$(document).off("closeCurtain");
		});

		if( !$('#curtain').hasClass('show') ) 
			$('#curtain').addClass('show');

		$('body > div#curtain').append(copyTempEdit);

		$('#edit a.btn-change').one('click', (e) => {
			let acceptAction = confirm("Are you sure update item with id '"+ id +"' ?");
			if(acceptAction) {
				$("form[name='edit-product']").submit();
				$('#curtain').click();
			}
		});

		$('form[name="edit-product"]').on('submit', e => {
			let arrEdited = $(e.delegateTarget).find('input[data-edited=true]'),
				updateObj = {
					'id': dataObj['id']
				};

			$(arrEdited).each( (i, elem) => {
				updateObj[elem.name] = elem.value;
			});

			$.post('edit', updateObj)
				.done( response => {
					location.reload();
				})
				.fail( response => {
					
					switch(response.status) {
						case 404:
							alert('Product not found');
							break;
						case 520:
							alert('Update fail');
							break;
						default:
							alert('Unknow error');
							break;
					}
				});

		});

	});

	$('a.close-btn').click( (e) => {
		let id = $(e.delegateTarget).attr('data-id');
		let acceptAction = confirm("Are you sure delete item with id '"+ id +"' ?");
		if (acceptAction) {
			$.post('delete', {
				'id': id
			})
				.done( response => {
					$(e.delegateTarget).parents('.product-wrapper').remove()
					alert('Object deleted');
				})
				.fail( response => {
					switch(response.status) {
						case 404:
							alert('Object not found');
							break;
						case 520:
							alert('Delete fail');
							break;
						default:
							alert('Unknow error');
							break;
					}
				});
		}
	});

	$('div.product-add').on('click', e => {
		if( $('#edit').length > 0 ) return;
		let template = $('#block-edit')[0];
		let copyTempEdit = template.content.cloneNode(true);
		let dataObj = {
			'title': null,
			'length': null,
			'width': null,
			'height': null,
			'price': null
		};

		$(copyTempEdit).find('h3.title').text("Add new product");	
		$(copyTempEdit).find('a.buy').removeClass('btn-change').addClass('btn-add').text('Add');
		
		for(let key in dataObj) {
			let input = $(copyTempEdit).find('form').find('input[name="'+key+'"]');
			$(input).val(dataObj[key]);
			if( key == 'title') continue;
			$( input ).on('input', e => {
				if( key == 'price') 
					$(e.target).val($(e.target).val().replace(/([^0-9.])/g, ''));
				else $(e.target).val($(e.target).val().replace(/\D/g, ''));
			});
		}

		$(copyTempEdit).find('form').attr('name', 'add-product');

		$(copyTempEdit).find('form').find('input[type="text"]').on('change', e =>{
			if ( $(e.delegateTarget).val() != '' )
				$(e.delegateTarget).attr('data-edited', true);
			else $(e.delegateTarget).attr('data-edited', false);
		});

		$(copyTempEdit).find('form').on('change', e => {
			if ($(e.delegateTarget).find('input[data-edited="true"]').length == Object.keys(dataObj).length)
				$(e.delegateTarget).find('a.btn-add').removeAttr('disabled');
			else $(e.delegateTarget).find('a.btn-add').attr('disabled', true);
		});

		$(copyTempEdit).find('#edit a.btn-add').one('click', (e) => {
			let acceptAction = confirm("Are you sure add new item ?");
			if(acceptAction) {
				$("form[name='add-product']").submit();
				$('#curtain').click();
			}
		});

		$(copyTempEdit).find('form[name="add-product"]').on('submit', e => {
			let arrNew = $(e.delegateTarget).find('input[data-edited=true]');
			if (arrNew.length != Object.keys(dataObj).length){
				alert('Error');
				return;
			}

			$(arrNew).each( (i, elem) => {
				let key = $(elem).attr('name'),
					val = $(elem).val();
				if (key == 'title') dataObj[key] = val;
				else dataObj[key] = Number(val);
			});

			$.post('add', dataObj)
				.done( response => {
					location.reload();
				})
				.fail( response => {
					
					switch(response.status) {
						case 400:
							alert('Invalid data');
							break;
						case 520:
							alert('Delete fail');
							break;
						default:
							alert('Unknow error');
							break;
					}
				});
			return false;

		});
		

		$(document).on("closeCurtain", (e) => {
			$('#edit').remove();
			$(document).off("closeCurtain");
		});

		if( !$('#curtain').hasClass('show') ) 
			$('#curtain').addClass('show');

		$('body > div#curtain').append(copyTempEdit);
	});

});