
$(function(){

    /** Отправка формы фильтров */
    $('#filters-form').formUrl({
        bindChange: ['type']
    });

    /** Сворачиваемые блоки дополнительных фильтров */
    $('#filters-form .filters-title').each(function(){

        var box = $(this).next();

        if (!box.find('input[type="checkbox"]:checked').length)
            box.hide();

        $(this).click(function(){
            box.toggle();
            return false;
        });
    });

});