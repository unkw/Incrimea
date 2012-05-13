
var ResortManager = {
    
    /** Коллекция мест отдыха */
    collection: [],
    /** $_GET параметры */
    $_GET: null,
    /** Места отдыха включенные в выборку фильтров */
    enabledItems: [],
    /** Html карты Крыма */
    mapHtml: null,
    
    /** Инициализация */
    init: function() {
        
        var self = this;
        
        // Отобразить чекбоксы и метки мест отдыха
        var resortsForm = $('#crimea-map .resorts #resorts-form').submit(function(){
            self.addToFilters($(this).serializeArray());
            return false;
        });
        // Карта Крыма
        this.mapHtml = $('#crimea-map .map');
        
        for (var i = 0, len = this.collection.length; i < len; i++) {
            
            resortsForm.append(this.collection[i].html.checkbox);
            this.mapHtml.append(this.collection[i].html.mark);
            
            // Отобразить включенные фильтры
            if (this.collection[i].checked) 
                this.collection[i].insert();
        }
        
        resortsForm.append('<input type="submit" value="Добавить фильтры" />');
    },
    
    addToFilters: function(data) {
        
        var enableItems = [];
        
        for (var i = 0, len = data.length; i < len; i++) {
            for (var j=0,l=this.collection.length; j<l; j++) {
                if (data[i].value == this.collection[j].url_name) {
                    enableItems.push(this.collection[j]);
                    break;
                }
            }
        }
        
        for (i = 0, len = this.enabledItems.length; i < len; i++) {
            
            var enable = false;
            for (j=0,l=enableItems.length; j<l; j++) {
                if (this.enabledItems[i].url_name == enableItems[j].url_name) {
                    enable = true;
                    enableItems.splice(j, 1);
                    break;
                }
            }
            
            if (!enable) {
                this.enabledItems[i].remove(i);
                i--;
                len--;
            }
        }
        
        for (i = 0, len = enableItems.length; i < len; i++) {
            enableItems[i].insert();
            this.enabledItems.push(enableItems[i]);
        }
        
        $.colorbox.close();
        
        // Обновление формы фильтров
        var map = $(FilterManager.formId + ' ' + FilterManager.cssSelectors.map);
        FilterManager.showProgramSubmit(map);
    },
    
    /**
     * Обновление формы карты Крыма при изменение параметров в фильтрах
     * @param data (array) Массив данных о всех местах отдыха
     */
    updateOnFilterFormChange: function(data) {
        
        var len = this.collection.length;
        for (var i = 0; i < len; i++) {
            
            for (var j = 0, l = data.length; j < l; j++)
                if (this.collection[i].url_name == data[j].url_name) {
                    
                    if (data[j].count > 0) {
                        this.collection[i].enable();
                    } else {
                        this.collection[i].disable();
                    }
                    data.splice(j, 1);
                    break;
                }
        }
    }
}

/**
 * Экземпляр одного места отдыха
 * @param data (array) - Данные текущего места отдыха
 */
function ResortInstance(data) {
    
    /** Человеческое название */
    this.name = data.name;
    
    /** Машинное имя */
    this.url_name = data.url_name;
    
    /** Флаг: добавлено ли в фильтры */
    this.checked = $.inArray(this.url_name, ResortManager.$_GET) > -1 ? true : false;
    
    /** Включен или выключен фильтр */
    this.disabled = false;
    
    /** Кол-во контента */
    this.count = parseInt(data.count, 10);

    this.html = {
        enabled: this._createEnabledHtml(), // Html в форме фильтров (когда добавлено в фильтры)
        mark: this._createMarkHtml(), // Метка на большой карте в popup-окне
        checkbox: this._createCheckboxHtml() // Чекбоксы в popup-окне
    }
    
    if (this.checked)
        ResortManager.enabledItems.push(this);
    
    if (this.count == 0)
        this.disable();
}

ResortInstance.prototype = {
    
    /**
     * Добавить в форму фильтров
     */
    insert: function(){
        
        this.checked = true;
        $('#filters-form .resorts-selected').append(this.html.enabled);
    },
    
    /**
     * Удаление из формы фильтров
     * @param index (int) - Индекс в коллекции активных элементов
     */
    remove: function(index){
        
        this.uncheck();
        
        this.html.enabled.detach();
        ResortManager.enabledItems.splice(index, 1);
    },

    check: function(){

        this.checked = true;
        this.html.checkbox.find('input[type="checkbox"]').prop('checked', true);
        this.html.mark.find('.mark').addClass('checked-mark');
    },
    
    uncheck: function(){
        
        this.checked = false;
        this.html.checkbox.find('input[type="checkbox"]').prop('checked', false);
        this.html.mark.find('.mark').removeClass('checked-mark');
    },
    
    enable: function(){
        
        this.disabled = false;
        this.html.checkbox.find('input[type="checkbox"]').prop('disabled', false);
    },

    disable: function(){
        
        this.disabled = true;
        this.html.checkbox.find('input[type="checkbox"]').prop('disabled', true);
    },
    
    toggleMark: function(){
        
        if (this.disabled)
            return;
        
        if (this.checked) {
            this.uncheck();
        } else {
            this.check();
        }
    },
    
    /** Создание отображения в форме фильтров */
    _createEnabledHtml: function() {
        
        var self = this;
        
        var html = $('<div class="item" />').text(this.name);
        
        $('<a href="#" class="remove" title="Отключить фильтр">&times;</a>')
        .click(function(){
            var index = $(this).parent().index();
            self.remove(index);
            return false;
        }).appendTo(html);
            
        $('<input type="hidden" name="resorts[]" />').val(this.url_name).appendTo(html);
            
        return html;
    },
    
    /** Создание отображения для большой карты в popup-окне */
    _createMarkHtml: function() {
        
        var self = this;
        
        var html = $('<div class="map-item" id="' + this.url_name + '-item" />');
        
        var mark = $('<div class="mark" />')
            .appendTo(html)
            .click(function(){
                self.toggleMark();
            });
        
        if (this.checked)
            mark.addClass('checked-mark');
        
        $('<div class="name" />').text(this.name).appendTo(html);
        
        return html;
    },
    
    /** Создание отображения чекбоксов в popup-окне */
    _createCheckboxHtml: function() {
        
        var self = this;
        
        var html = $('<div class="checkbox-item" />');
        
        var label = $('<label />').appendTo(html);
        $('<input type="checkbox" name="select-resorts[]" >')
            .val(this.url_name)
            .prop('checked', this.checked)
            .appendTo(label)
            .click(function() {
                self.toggleMark();
            });
            
            
        $('<span> '+this.name+'</span>').appendTo(label);
        
        return html;        
    }
}