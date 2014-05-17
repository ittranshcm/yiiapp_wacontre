function view(){
	return false;
}
function update(){
	return false;
}
jQuery(document).ready(function(){
	jQuery('.view').live('click', function(){
		href = jQuery(this).attr('href');
		jQuery.ajax({
			url: href,
			type: "GET",
			success: function(data){
				jQuery('#quickview .modal-body').html(data);
				jQuery('#quickview').modal('show');
			}
		});
	});
	
	jQuery('.edit').live('click', function(){
		href = jQuery(this).attr('href');
		jQuery.ajax({
			url: href,
			type: "GET",
			success: function(data){
				jQuery('#quickview .modal-body').html(data);
				jQuery('#quickview').modal('show');
			}
		});
	});
	
	jQuery('.doupdate').live('click', function(){
		href = jQuery('#users-form').attr('action');
		jQuery.ajax({
			url: href,
			type: "POST",
			data:jQuery('#users-form').serialize(),
			success: function(data){
				jQuery("a[href='"+href+"']").parent().next().remove();
			}
		});
	});
});