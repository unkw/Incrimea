
(function( $ ){

  $.fn.html5_uploader = function( options ) {

    // Create some defaults, extending them with any options that were provided
    var settings = $.extend( {

        url: '/admin/object/test',
        maxFileSize: '2000',
        onSelect: function(){}
        


    }, options);

    return this.each(function() {

        $(this).change(function() {

            settings.onSelect.call(this, this.files);

            for (var i = 0; i < this.files.length; i++) {

                var file = this.files[i];

                var reader = new FileReader();

                var_dump(this.files[i], 'a');

                var data = {};

                $.ajax({
                   url: '/admin/object/test',
                   type: 'POST',
                   data: data,
                   beforeSend: function(xhr){

                   },
                   complete: function(data){
                       trace('Success ' + data.responseText);
                   }
                });
            }
        });
    });

  };
})( jQuery );