
var FilterManager = {

    form: null, // jQuery объект формы фильтров
    programSubmit: null, // Кнопка "Показать"
    timer: null, // Таймер для кнопки "Показать"
    ajaxloader: $('<div class="ajaxloader" />'),
    
    /** ID формы фильтров */
    formId: '#filters-form',

    /** Селекторы css классов */
    cssSelectors: {
        titles: '.filters-title', // Заголовки разделов фильтров
        map: '.small-map' // Мини-карта Крыма
    },
    
    /** Конфиги формы */
    config: {
        checkboxSep: ',', // Разделитель значений параметра
        submitOnChange: ['type'], // Элементы формы, при изменении которых сразу отправляем форму
        commonFilters: ['type', 'resorts[]'] // Фильтры для всех типов контента 
    },
    
    /**
     * Инициализация формы фильтров
     */
    init: function() {

        this.form = $(this.formId);

        /** Инициализация элементов формы */
        this._initFormElements();
        
        /** Сворачиваемые блоки дополнительных фильтров */
        this._initFoldingBlocks();
        
        /** Карта Крыма в сплывающем окне */
        this._initMiniMap();

        /** Слайдер диапазона цен */
        this._initPriceSlider();
    },
    
    /**
     * Инициализация формы для отправки фильтров
     */
    _initFormElements: function() {
        
        var self = this;
        var form = this.form;
        
        // Отправка формы фильтров по обычному сабмиту
        form.submit(function(){
            self.formSubmit();
            return false;
        });
        
        // Отправка формы фильтров на onchange
        for (var i = 0; i < this.config.submitOnChange.length; i++) {
            form.find('[name="'+this.config.submitOnChange[i]+'"]').change(function(){
                self.formSubmit();
            });
        }

        /** Ссылка, позволяющая программным путем отправить форму */
        this.programSubmit = $('<div class="program-submit-wrap"></div>')
            .append(
                $('<a href="#" class="program-submit">Показать</a>').click(function(){
                    self.formSubmit();
                    return false;
                })            
            ).append('<div class="count"></div>').appendTo(form);

        // Добавление ссылки "Показать" при изменении элементов формы
        form.find('input').change(function(){
            if ($.inArray($(this).attr('name'), self.config.submitOnChange) != -1)
                return;
            self.showProgramSubmit($(this));
        });
    },
    
    /** 
     * Установить сворачиваемость блоков фильтров по клику на их заголовоки.
     * Т.е. show/hide режимы
     */
    _initFoldingBlocks: function() {
        
        $(this.formId + ' ' + this.cssSelectors.titles).each(function(){

            var box = $(this).next();

            if (!box.find('input[type="checkbox"]:checked').length)
                box.hide();

            $(this).click(function(){
                box.toggle();
                return false;
            });
        });        
    },
    
    /** 
     * Установить обработчик для открытия по клику карты Крыма
     * в специальном popup-окне плагина Colorbox
     */
    _initMiniMap: function() {

        $(this.formId + ' ' + this.cssSelectors.map)
            .colorbox({
                inline: true,
                close: 'Закрыть',
                scrolling: false,
                opacity: 0,
                onOpen: function() {}
            });
    },
    
    /**
     * Иницализация слайдера цен
     */
    _initPriceSlider: function() {
        
        var priceSlider = $(this.formId + ' #slider-price-range');
        var pMin = $('input[name="p-min"]').keyup(function(){
            if ($(this).val() > priceSlider.slider('values', 1))
                priceSlider.slider('values', 0, priceSlider.slider('values', 1));
            else
                priceSlider.slider('values', 0, $(this).val());
        });
        var pMax = $('input[name="p-max"]').keyup(function(){
            if ($(this).val() < priceSlider.slider('values', 0))
                priceSlider.slider('values', 1, priceSlider.slider('values', 0));
            else
                priceSlider.slider('values', 1, $(this).val());
        });
        priceSlider.slider({
            range: true,
            min: 0,
            max: 300,
            step: 5,
            values: [pMin.val() ? pMin.val() : 0, pMax.val() ? pMax.val() : 800],
            slide: function(e, ui) {
                var max = $(this).slider('option', 'max');
                if (ui.values[0] < 0) ui.values[0] = 0;
                if (ui.values[0]!=pMin.val()) pMin.val(ui.values[0]);
                if (ui.values[1]!=max || pMax.val()!='') pMax.val(ui.values[1]);
            },
            stop: function(e, ui) {pMax.change();}
        });        
    },
    
    /**
     * Отправка формы
     */
    formSubmit: function() {
        
        var params = this.getQueryString();
        var getArr = [];
        for (var key in params) {
            getArr.push(key + '=' + params[key]);
        }

        document.location = this.form.attr('action') + ((getArr.length) ? '?' : '') + getArr.join('&');
    },

    /**
     * Получение $_GET параметров формы для отправки на сервер
     */
    getQueryString: function() {
        
        var data = this.form.serializeArray();
        
        var params = {};
        for (var i = 0, len = data.length; i < len; i++) {
            
            if ( $.trim(data[i].value) ) {
                
                var name = data[i].name.replace(/\[\]/, '');

                if (params[name]) {
                    params[name] += ',' + data[i].value;
                } else {
                    params[name] = data[i].value;
                }
            }
        }

        return params;
    },
    
    /**
     * Показать кнопку "Показать" отправки формы
     */
    showProgramSubmit: function(elm){
        
        var self = this;
        var data = this.getQueryString();
        
        if (!data.type)
            return;

        if (this.timer) 
            clearTimeout(this.timer);
        
        var pos = elm.position();
        this.programSubmit
            .css({top: pos.top - 8})
            .show();
        this.programSubmit.find('.count').html(this.ajaxloader);
            
        // Отобразить кол-во контента
        this.showContentCount(data);

        this.timer = setTimeout(function(){
            self.programSubmit.hide();
        }, 4000);
    },
    
    /**
     * Показать кол-во выводимого контента,
     * если отправить форму фильтров
     */
    showContentCount: function(data){
        
        var self = this;
        
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

                var inputs = self.form.find('input[name="'+name+'"]');
                for (var i = 0, len = json[key].length; i < len; i++) {

                    // Подсчитываем кол-во ожидаемого контента для отображения в подсказке
                    if (key == 'resorts') {
                        var resorts = null;
                        if (data.resorts) {
                            resorts = resorts ? resorts : data.resorts.split(',');
                            if ($.inArray(json[key][i].url_name, resorts) > -1) {
                                count += parseInt(json[key][i].count, 10);
                            }
                        } else { 
                            count += parseInt(json[key][i].count, 10);
                        }
                    }
                        
                    inputs.each(function(){
                        
                       if ($(this).val() == json[key][i].url_name)
                           $(this).prop('disabled', json[key][i].count > 0 ? false : true);
                    });
                }
            }
            
            // Обновление фильтра "Места отдыха"
            ResortManager.updateOnFilterFormChange(json.resorts);
            
            self.programSubmit.find('.count').text(count);
        }, 'json');
    },
    
    updateOnChange: function(){
        
    }
};