$( document ).ready( () => {

	$('#curtain').click( (e) => {
		$(e.target).removeClass('show');
		document.dispatchEvent(new CustomEvent("closeCurtain", {
			detail: { name: "close" }
		}));
	});

	if(location.href.split("?").length > 1) {
		let getParams = location.href.split("?")[1].split("&");
		$(getParams).each((i, item) => {
			let param = item.split('=')[0],
				val = item.split('=')[1];
			if(item.indexOf('order') != -1) 
				$("#orderBtn").attr('data-order', val);
			$('form[name="filters"]').find('input[name="'+ param +'"]').val(val);
		});

		if(Object.values($('form[name="filters"] input')).slice(0,-2).filter( elem => {return elem.value != '' } ).length > 0 && $('#resetFilter').hasClass('disabled') ) $('#resetFilter').removeClass('disabled');
	}

	$("#orderBtn").click( (e) => {
		let regExr = /order=[0-9]/g;
		let replaceOrder = regExr.exec(location.href);
		if( replaceOrder !== null ) 
			if(replaceOrder[0].split('=')[1]=='0') 
				location.href = location.href.replace(regExr, 'order=1')
			else location.href = location.href.replace(regExr, 'order=0'); 
		else {
			if(location.href.split("?").length > 1) 
				location.href = location.href + "&order=1";
			else location.href = location.href + "?order=1";
		}
	});

	$("#open-sort").click( (e) => {
		if(!$('#curtain').hasClass('show')) $('#curtain').addClass('show');
		let elem = $('ul.list-sort');
		$(elem).attr('aria-show')==='False'? $(elem).attr('aria-show', 'True') : $(elem.attr('aria-show', 'False'));
		$(document).on("closeCurtain", (e) => {
			$(elem).attr('aria-show', "False");
			$(document).off("closeCurtain");
		});
	});

	$('.list-sort > li > a.sort').click( (e) => {
		let href = location.href.replace(new RegExp('sortby=[\\w]+[&]?', 'g'),'');
		href = href.replace(new RegExp('\\&$'),'');
		if( href.split("?").length>1 && href.split("?")[1] != '')
			location.href = href + "&sortby=" + $(e.target).attr('data-sort');
		else if(href.indexOf("?") == -1) 
			location.href = href + "?sortby=" + $(e.target).attr('data-sort');
		else location.href = href + "sortby=" + $(e.target).attr('data-sort');
	});

	$("#open-list").click( (e) => {
		if(!$('#curtain').hasClass('show')) $('#curtain').addClass('show');
		let elem = $('ul.list-filters');
		$(elem).attr('aria-show')==='False'? $(elem).attr('aria-show', 'True') : $(elem.attr('aria-show', 'False'));
		$(document).on("closeCurtain", (e) => {
			$(elem).attr('aria-show', "False");
			$(document).off("closeCurtain");
		});
	});

	$('.list-filters > li > a[data-toggle="tab"]').click( (e) => {
		$('.list-filters').attr('aria-show', 'False');
		$('#curtain').click();
	} );

	$('div.filt-tab input[type="text"]').on('input', (e)=> {
		$(e.target).val($(e.target).val().replace(/\D/g, ''));
		
	});

	$('form[name="filters"]').on('change', (e) => {
		$('form[name="filters"] button[type="submit"]').attr('aria-show', 'True');
		$('form[name="filters"] button[type="submit"]').prop('disabled', false);
		if(Object.values($('form[name="filters"] input')).slice(0,-2).filter( elem => {return elem.value != '' } ).length > 0)
			if( $('#resetFilter').hasClass('disabled') ) $('#resetFilter').removeClass('disabled');
		else	
			if( !$('#resetFilter').hasClass('disabled') ) $('#resetFilter').addClass('disabled');
	});

	$('form[name="filters"]').on('submit', (e) => {
		handlerFilterForm();
		return false;
	});

	$('form[name="filters"]').on('reset', (e) => {
		
		e.preventDefault();
		$('form[name="filters"] input[type="text"]').each( (i, el) => {el.value = ''} );
		
		handlerFilterForm();
		
	});

	function handlerFilterForm() {
		let inputNames = Object.values($('form[name="filters"] input')).slice(0,-2).map( elem => {return elem.name} );
		let getParams = location.href.match(/(?!\&|\?)[A-Za-z]+(?=\=)/g) || [];
		let href = location.href;

		getParams.forEach( (item, i) => {
			if(inputNames.filter( (elem) => {return elem == item} ).length != 0) {
				href = href.replace(new RegExp(item + '=[\\d]+[&]?'),'');
			}
		});
		href = href.replace(new RegExp('&$'),'');

		let fillFilt = Object.values($('form[name="filters"] input')).slice(0,-2).filter( elem => {return elem.value != '' } ),
			urlFilters = '';
		fillFilt.forEach((item, i) => {
			if(i != 0) urlFilters += "&";
			urlFilters += item.name + "=" + item.value;
		});

		
		

		if(href.split("?").length>1 && href.split("?")[1] != '' && urlFilters!='')
			location.href = href + "&" + urlFilters;
		else if(href.split("?").length>1 && href.split("?")[1] == '' && urlFilters!='')
			location.href = href + urlFilters;
		else if(urlFilters!='') 
			location.href = href + "?" + urlFilters;
		else location.href = href.replace(new RegExp('\\?$'),'');
	}

	$('#resetFilter').on('click', (e) => {$('form[name="filters"]')[0].reset()})
})