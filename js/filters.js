
var FilterManager = {

    formId: '#filters-form',

    cssSelectors: {
        titles: '.filters-title',
        map: '.small-map'
    },

    init: function() {

        /** Отправка формы фильтров */
        $(this.formId).formUrl({
            bindChange: ['type'],
            bindFilters: ['resorts[]'],
            withoutSubmit: ['resorts[]']
        });

        /** Сворачиваемые блоки дополнительных фильтров */
        $(this.formId + ' ' + this.cssSelectors.titles).each(function(){

            var box = $(this).next();

            if (!box.find('input[type="checkbox"]:checked').length)
                box.hide();

            $(this).click(function(){
                box.toggle();
                return false;
            });
        });

        /** Карта Крыма в сплывающем окне */
        $(this.formId + ' ' + this.cssSelectors.map)
            .colorbox({
                inline: true,
                width: '870px',
                close: 'Закрыть',
                scrolling: false,
                opacity: 0
            });

        /** Слайдер диапазона цен */
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

    }
};