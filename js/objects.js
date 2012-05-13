
var ObjPreview = {
    
    css: {
        wrap: '.obj-item'
    }
};

var ObjPreviewGallery = {
    
    css: $.extend(ObjPreview.css, {
        currentImg: '.current',
        ulThumbs: '.small-thumbs'
    }),
    
    options: {
        event: 'hover' // Событие, по-которому следует менять изображение
    },
    
    init: function() {
        
        var self = this;
        
        $(this.css.wrap + ' ' + this.css.ulThumbs + ' li img')
            .bind(this.options.event, function(){
            
                var parentWrap = $(this).parents(self.css.wrap);
                var newCurrentImg = $(this).attr('src');
                $(parentWrap).find(self.css.currentImg).attr('src', newCurrentImg);
            });
    }
};

var ObjPreviewIcons = {};
