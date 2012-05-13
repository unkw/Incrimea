//Object.prototype.isEmpty = function() {
//    for (var prop in this) {
//        if (this.hasOwnProperty(prop)) return false;
//    }
//    return true;
//};

$(function(){

    /** Инициализация мест отдыха (для работы с картой) */
//    ResortManager.init();

    FilterManager.init();
});