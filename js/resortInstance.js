
var ResortManager = {

    /** CSS ID карты */
    mapSelector: '#crimea-map .map',

    /** Курорты */
    collection: [],

    /** Флаг инициализации */
    inited: false,

    /** Инициализация инстансов */
    init: function(){

        if (this.inited)
            return;
        
        this.inited = true;

        var self = this;

        $('input[name="resorts[]"]').each(function(i){

            var data = {
                url_name: $(this).val(),
                checkbox: $(this),
                checked: $(this).prop('checked')
            };

            self.collection.push(new ResortInstance(data));
        });

        // Применить фильтр
        $('#crimea-map #resorts-add').click(function(){
            $.colorbox.close();
            return false;
        });

        // Выбрать всё
        $('#crimea-map #resorts-select').click(function(){
            self.checkAll();
            return false;
        });

        // Сбросить фильтр мест отдыха
        $('#crimea-map #resorts-reset').click(function(){
            self.reset();
            return false;
        });
    },

    checkAll: function(){
        for (var i = 0, l = this.collection.length; i < l; i++)
            if (!this.collection[i].checked)
                this.collection[i].check();
    },

    /** Очистить */
    reset: function(){
        
        for (var i = 0, l = this.collection.length; i < l; i++)
            if (this.collection[i].checked)
                this.collection[i].uncheck();
    }
};

function ResortInstance(data) {

    var self = this;

    /** Место отдыха латиницей */
    this.url_name = data.url_name;

    /** Выбран или нет */
    this.checked = data.checked;

    /** Label чекбокса */
    this.label = $('label[for="resort-'+this.url_name+'"]').hover(function(){
        self._hover();
    }, function(){
        self._unhover();
    });

    /** Место отдыха */
    this.name = this.label.text();

    /** Html чекбокса */
    this.checkbox = data.checkbox.change(function(){
        if ($(this).prop('checked'))
            self.check();
        else
            self.uncheck();
    });

    /** Html курорта на карте */
    this.tooltip = $('<div class="resort-tooltip">'+this.name+'</div>');
    this.html = $('<div id="label-'+this.url_name+'" class="resort-label"></div>')
        .appendTo($(ResortManager.mapSelector)).addClass(this.checked ? 'checked-label' : '')
        .hover(function(){
            var pos = $(this).position();
            $(this).before(self.tooltip.css({
                top: pos.top - 10,
                left: pos.left + 5
            }));
        }, function(){
            self.tooltip.remove();
        })
        .click(function(){
            if (self.checked)
                self.uncheck();
            else
                self.check();
        });
};

ResortInstance.prototype = {
    
    check: function(){
        this.checkbox.prop('checked', true);
        this.html.addClass('checked-label');
        this.checked = true;
    },
    
    uncheck: function(){
        this.checkbox.prop('checked', false);
        this.html.removeClass('checked-label');
        this.checked = false;
    },

    _hover: function(){
        if (!this.checked)
            this.html.addClass('checked-label');
    },

    _unhover: function(){
        if (!this.checked)
            this.html.removeClass('checked-label');
    }
};