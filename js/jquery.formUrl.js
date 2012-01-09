/* 
 * jQuery плагин, который при отправке формы GET запросом формирует более
 * наглядный url (в основном, когда форма содержит несколько или более
 * однотипных чекбоксов)
 *
 * Author: Smaylov Kemal
 * Date: 12 December 2011
 */

(function( $ ){

  $.fn.formUrl = function( options ) {

    // Create some defaults, extending them with any options that were provided
    var settings = $.extend( {
      checkboxSep: ',',
      bindChange: []
    }, options);

    return this.each(function() {

        var self = this;

        /** Отправка формы фильтров на onchange */
        for (var i = 0; i < settings.bindChange.length; i++) {
            $(this).find('[name="'+settings.bindChange[i]+'"]').change(function(){
                submitForm.call(self); return false;
            });
        }

        /** Отправка формы фильтров по сабмиту */
        $(this).submit(function(){
            submitForm.call(this); return false;
        });

        // Ссылка, позволяющая программным путем отправить форму
        var programSubmit = $('<a href="#">Показать</a>').click(function(){
            submitForm.call(self); return false;
        });

        /** Отправка формы по клику */
        var hider = null;
        $(this).find('input[type="checkbox"]').change(function(){
            if (hider) clearTimeout(hider);
            programSubmit.show();
            $(this).parent().parent().append(programSubmit);
            hider = setTimeout(function(){
                programSubmit.hide();
            }, 3000);
        });
    });

    /** Отправка формы */
    function submitForm() {
        
        var getArr = [];

        /** Тип контента */
        var type = $(this).find('input[name="type"]:checked');
        if (type.length)
            getArr.push(type.attr('name') + '=' + type.val());

        /** Обработка чекбоксов */
        var params = {};

        $(this).find('input[type="checkbox"]').filter(function(){
            if ($(this).prop('checked')) {
                var name = $(this).attr('name').replace(/\[\]/, '');
                if (params[name]) {
                    params[name].push($(this).val());
                }
                else {
                    params[name] = [$(this).val()];
                }
            }
        });

        for (var key in params) {
            getArr.push(key + '=' + params[key].join(settings.checkboxSep));
        }

        /** Отправляем GET запрос */
        document.location = $(this).attr('action') + ((getArr.length) ? '?' : '') + getArr.join('&');
    }

  };
})( jQuery );