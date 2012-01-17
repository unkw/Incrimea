
/**
 *
 * Простой jQuery плагин для автоматической отправки формы при изменении каких-либо полей формы
 *
 * Author: Smaylov Kemal
 * Date: 15 January 2012 16:38:14
 */

(function( $ ){

    $.fn.autoSubmit = function( options ){

        var settings = $.extend({

        }, options);

        return this.each(function(){

            var $this = $(this);

            $this.find('select').change(function(){
                $this.submit();
            });

        });

    };

})(jQuery);