
$(function(){
    $('#filters-form').submit(function(){

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
            getArr.push(key + '=' + params[key].join(','));
        }

        /** Отправляем GET запрос */
        document.location = 'filter' + ((getArr.length) ? '?' : '') + getArr.join('&');

        return false;
    });
});