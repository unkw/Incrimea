
$(function(){

    /** Datepicker для даты создания и конца события */
    $('.edit-event-dates').datepicker({dateFormat: 'dd-mm-yy'});

    /** Инициализация загрузчика изображений для отеля */
    ObjectGallery.init();

});

var ObjectGallery = {

    inputFile: null,
    /** Контейнер изображений */
    imgWrapper: null,
    /** Кнопка отправляющая изображения на сервер */
    uploadSubmit: null,

    /** Инициализация */
    init: function(){
        
        var self = this;

        this.imgWrapper = $('#edit-gallery');
        this.uploadSubmit = $('#upload-submit').click(function(){
            self.inputFile.damnUploader('startUpload');
        });

        this.inputFile = $('input[name="edit-gallery"]').damnUploader({
            url: '/admin/object/upload',
            fieldName: 'edit-images',
            onSelect: function(file){

                self.displayImage(file);
                
                return false;
            },
            onLimitExceeded: function(){
            }
        });
    },

    displayImage: function(file){

        var self = this;

        var uploadId = this.inputFile.damnUploader('addItem', {
            file: file,
            onProgress: function(value) {
                progress.val(value);
                percents.text(value + '%');
            },
            onComplete: function(successfully, data, errorCode) {
                if(successfully) {
                    img.css('opacity', 1);
                } else {
                    alert('Ошибка при загрузке. Код ошибки: '+errorCode); // errorCode содержит код HTTP-ответа, либо 0 при проблеме с соединением
                }
            }
        });

        var li = $('<li></li>');

        var imgWrap = $('<div />').appendTo(li);
        var img = $('<img src="" alt="" width="120" />').appendTo(imgWrap);
        var progress = $('<progress value="0" style="width: 120px; display: block;"></progress>').appendTo(li);
        var percents = $('<span class="percents">0%</span>').appendTo(li);
        $('<a href="#" class="remove">Удалить</a>').click(function(){
            li.remove();
            self.inputFile.damnUploader('cancel', uploadId);
            return false;
        }).appendTo(li);

        this.imgWrapper.append(li);

        // Читаем файл и отображаем его
        var reader = new FileReader();

        reader.onload = (function(aImg) {
            return function(e) {
                aImg.css('opacity', 0.6);
                aImg.attr('src', e.target.result);
                aImg.attr('width', 120);
                this.imgSize += file.size;
            };
        })(img);

        reader.readAsDataURL(file);
    }
}