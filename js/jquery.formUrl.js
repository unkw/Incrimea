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
      bindChange: [],
      bindFilters: [], // Фильтры для всех типов контента
      withoutSubmit: []
    }, options);

    return this.each(function() {

        var self = this;

        /** Отправка формы фильтров на onchange */
        for (var i = 0; i < settings.bindChange.length; i++) {
            $(this).find('[name="'+settings.bindChange[i]+'"]').change(function(){
                submitForm.call(self, true);
                return false;
            });
        }

        // Отправка формы фильтров по сабмиту
        $(this).submit(function(){
            submitForm.call(this);
            return false;
        });

        /** Ссылка, позволяющая программным путем отправить форму */
        var programSubmit = $('<a href="#" class="program-submit">Показать</a>').click(function(){
            submitForm.call(self);
            return false;
        });
        $(this).data('programSubmit', programSubmit);

        // Флаг, существует ли setTimeout
        $(this).data('hider', null);
        
        // Отправка формы по клику
        $(this).find('input[type="checkbox"]').change(function(){
            if ( $.inArray($(this).attr('name'), settings.withoutSubmit) != -1)
                return;
            addCustomSubmitLink.call(this, self);
        });

        // Добавление ссылки "Показать" при вводе символов
        $(this).find('input[type="text"]').change(function(){
            addCustomSubmitLink.call(this, self);
        });
    });

    /** Добавить произвольную ссылку отправления формы около элемента */
    function addCustomSubmitLink(form) {
        
        var hider = $(form).data('hider');
        var programSubmit = $(form).data('programSubmit');

        if (hider) clearTimeout(hider);
        programSubmit.show();
        $(this).parent().parent().append(programSubmit);

        hider = setTimeout(function(){
            programSubmit.hide();
        }, 4000);
        $(form).data('hider', hider);
    }

    /** Отправка формы */
    function submitForm(onChange) {

        onChange = onChange || false;

        var getArr = [];

        /** Тип контента */
        var type = $(this).find('input[name="type"]:checked');
        if (type.length)
            getArr.push(type.attr('name') + '=' + type.val());

        /** URL параметры */
        var params = {};

        // Обработка полей
        $(this).find('input').filter(function(){

            var name = null;

            if (onChange && $.inArray($(this).attr('name'), settings.bindFilters) == -1)
                return;

            /** Обработка чекбоксов */
            if ($(this).attr('type') == 'checkbox') {
                if ($(this).prop('checked')) {
                    name = $(this).attr('name').replace(/\[\]/, '');
                }
            }

            /** Обработке текстовых полей */
            else if ($(this).attr('type') == 'text') {
                if (/^\s*\d+\s*$/.test($(this).val()))
                    name = $(this).attr('name');
            }

            if (name) {
                if (params[name]) {
                    params[name].push($(this).val());
                }
                else {
                    params[name] = [$(this).val()];
                }
            }
         
        });

        // Формируем url
        for (var key in params) {
            getArr.push(key + '=' + params[key].join(settings.checkboxSep));
        }

        // Отправляем GET запрос
        document.location = $(this).attr('action') + ((getArr.length) ? '?' : '') + getArr.join('&');
    }

  };
})( jQuery );