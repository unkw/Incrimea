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
      checkboxSep: ',', // Разделитель значений
      bindChange: [], // Элементы формы, при изменении которых сразу отправляем форму
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
        var programSubmit = $('<div class="program-submit-wrap"></div>')
            .append(
                $('<a href="#" class="program-submit">Показать</a>').click(function(){
                    submitForm.call(self);
                    return false;
                })            
            ).append('<span class="count"></span>')

        $(this).data('programSubmit', programSubmit);

        // Флаг, существует ли setTimeout
        $(this).data('hider', null);
        
        // Добавление ссылки "Показать" при изменение чекбокса
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
        // Получить $_GET параметры формы
        var getArr = getQueryString.call(form);
        // Преобразование параметров для отправки на сервер
        var data = getArr.join('&');
        $.get('filter/get_form', data, function(json){
            
            // Обновление обычных полей формы фильтров
            var count = 0;
            for (var key in json) {
                
                var name;
                switch (key) {
                    case 'beachs':name = 'beachs[]';break;
                    case 'room':name = 'room[]';break;
                    case 'infrastructure':name = 'infr[]';break;
                    case 'entertainment':name = 'entment[]';break;
                    case 'service':name = 'service[]';break;
                    case 'for_children':name = 'child[]';break;
                }

                var inputs = $(form).find('input[name="'+name+'"]');
                for (var i = 0, len = json[key].length; i < len; i++) {

                    // Подсчитываем кол-во ожидаемого контента для отображения в подсказке
                    if (key == 'resorts')
                        count += parseInt(json[key][i].count, 10);

                    inputs.each(function(){
                        
                       if ($(this).val() == json[key][i].url_name)
                           $(this).prop('disabled', json[key][i].count > 0 ? false : true);
                    });
                }
            }
            
            // Обновление фильтра "Места отдыха"
            ResortManager.updateOnFilterFormChange(json.resorts);
            
            programSubmit.find('.count').html(count);
        }, 'json');

        hider = setTimeout(function(){
            programSubmit.hide();
        }, 4000);
        $(form).data('hider', hider);
    }

    /** Отправка формы */
    function submitForm(onChange) {

        var getArr = getQueryString.call(this, onChange);

        // Отправляем GET запрос
        document.location = $(this).attr('action') + ((getArr.length) ? '?' : '') + getArr.join('&');
    }
    
    function getQueryString(onChange) {
        
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
            
            // Только фильтры для всех типов контента будут добавлены в $_GET
            if (onChange && $.inArray($(this).attr('name'), settings.bindFilters) == -1)
                return;

            var elmType = $(this).attr('type');
            
            /** Обработка чекбоксов */
            if (elmType == 'checkbox') {
                if ($(this).prop('checked')) {
                    name = $(this).attr('name').replace(/\[\]/, '');
                }
            }

            /** Обработке текстовых полей */
            else if (elmType == 'text') {
                if (/^\s*\d+\s*$/.test($(this).val()))
                    name = $(this).attr('name');
            }
            
            /** Обработка hidden элементов */
            else if (elmType == 'hidden') {
                name = $(this).attr('name').replace(/\[\]/, '');
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

        return getArr;
    }

  };
})( jQuery );