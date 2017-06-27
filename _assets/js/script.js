// Own scripts
// override jquery validate plugin defaults
if ($.validator) {
	$.validator.setDefaults({
	    highlight: function(element) {
	    	$(element).closest('.form-group').addClass('has-error');
	    },
	    unhighlight: function(element) {
	        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	    	if( $(element).hasClass('select2-hidden-accessible') ) {
	    		error.insertAfter(element.next('span.select2'));
	    		console.log('select2', element)
	    	} else {
	    		if(element.parent('.input-group').length) {
		            error.insertAfter(element.parent());
		        } else {
		            error.insertAfter(element);
		        }
	    	}	        
	    }
	});
}

// document ready
$(function() {
	// li.error -> add fa-cancel and text-danger class
	$.each($('li.error'), function(i, val) {
		 $(this).addClass('text-danger').prepend('<span class="glyphicon glyphicon-remove"></span> ');
	});

	// li.success -> add fa-check and text-success class
	$.each($('li.success'), function(i, val) {
		 $(this).addClass('text-success').prepend('<span class="glyphicon glyphicon-ok"></span> ');
	});

	// li.warning -> add fa-check and text-warning class
	$.each($('li.warning'), function(i, val) {
		 $(this).addClass('text-warning').prepend('<span class="glyphicon glyphicon-alert"></span> ');
	});

	// li.info -> add fa-check and text-info class
	$.each($('li.info'), function(i, val) {
		 $(this).addClass('text-info').prepend('<span class="glyphicon glyphicon-info-sign"></span> ');
	});

	$.each($('.alert-temp'), function(index, val) {
		var time  = ($(this).data('time') !== undefined) ? $(this).data('time') : 5000;
		var t = parseFloat(parseFloat(time) / 1000) + 's';
		var alert = $(this);
		var bar   = $(this).find('.progress-bar');
		
		$(bar).css({
    		transition: parseFloat(parseFloat(time) / 1000) + 's',
    		width: '100%'
    	});
		setTimeout(function() {
			$(alert).fadeOut('400', function() {
				// callback if required
			});
		},
			time
		);
	});

	function fill_bar(selector, time)
	{
		var t = time/100;
		var i = 0;
		var counterBack = setInterval(function () {
		  i++;
		  if (i < 100) {
		    selector.css('width', i + '%');
		    console.log( + ' -> ' + i)
		  } else {
		    clearInterval(counterBack);
		  }

		}, t);
	}

})