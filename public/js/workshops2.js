	jQuery(document).ready(function(){
  
    jQuery('.entry').on('click', '.more_button button', function(){
        
        var self = jQuery(this);
        self.html('Loading');
        
        setTimeout(function() {
          self.parent().remove();
        }, 1000);
        
        var page = jQuery(this).data('page');
        var type = jQuery(this).data('type');
        var curation = jQuery(this).data('curation');
        var size = jQuery(this).data('size');
        var user = jQuery(this).data('user');
        var sort_by = jQuery(this).data('sort_by');
        var date_range = jQuery(this).data('date_range');
        var limit = jQuery(this).data('limit');
        
        var ajaxurl = '/wp-admin/admin-ajax.php',
        data =  {'contraption_page': page,
        		 'type' : type,
        		 'curation' : curation,
        		 'size' : size,
        		 'user' : user,
        		 'sort_by' : sort_by,
        		 'date_range' : date_range,
        		 'limit' : limit
        		 };
      
        jQuery.post(ajaxurl, data, function (response) {
            // Response div goes here.
            var html = response;
            jQuery(html).appendTo('.cmworkshops').hide().fadeIn(800);
            //jQuery('.cmworkshops').append(response).fadeIn(5000);
            //alert(response);
        });
        
        
    });

});
