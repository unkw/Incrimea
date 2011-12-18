/** Загрузка файлов с помощью HTML5 */

$(function(){
    SingleUpload.init();
});

/** Загрузка единичного файла */
var SingleUpload = {

    sizeInfo: null,
    fileInput: null,
    imgSize: 0,
    imgContainer: null,
    imgMsg: null,
    imgSrc: null,
    imgRemove: null,
    imgFolder: null,
    img: null,

    /** Инициализация загрузчика */
    init: function(){

        var self = this;

        this.sizeInfo = $('#img-size');

        this.fileInput = $('input[name="upload-image"]');

        this.imgContainer = $('#img-container');

        this.imgMsg = $('#img-msg');

        this.imgSrc = $('input[name="edit-image"]');

        this.imgThumb = $('input[name="edit-thumb"]');

        this.imgFolder = 'images/' + $('input[name="module-name"]').val() + '/';

        this.fileInput.bind({
            change: function(){
                self.displayThumb(this.files[0]);
            }
        });

        this.imgSubmit = $("#img-upload").click(function(){
            self.upload();
        });

        this.imgRemove = $('#img-remove').click(function(){
            $(this).hide();
            self.imgContainer.find('#img-main').remove();
            self.imgContainer.find('.progress').remove();
            self.showUploadInputs();
            self.imgSrc.val('');
            self.imgThumb.val('');
        });

        if (this.imgThumb.val()) {
            this.hideUploadInputs();
        } else {
            this.imgRemove.hide();
        }

    },

    showUploadInputs: function(){
        this.fileInput.show();
        this.imgSubmit.show();
    },

    hideUploadInputs: function(){
        this.fileInput.hide();
        this.imgSubmit.hide();
    },

    displayMsg: function(msg, type){

        var color = type == 'success' ? 'green' : 'red';

        this.imgMsg.text(msg).css('color', color);
    },

    /** Отображение превью файла перед его загрузкой на сервер */
    displayThumb: function(file){

        var self = this;

        var imageType = /image.*/;

        // Отсеиваем не картинки
        if (!file.type.match(imageType)) {
            this.displayMsg('Недопустимый тип файла: `'+file.name+'` (тип '+file.type+')');
            return;
        }

        if (file.name.match(/[А-Яа-я]+/)) {
            this.displayMsg('Русские символы в названии файла недопустимы');
            return;
        }

        // Очищаем контейнер сообщения
        this.imgMsg.text('');

        // Картинка
        var img = $('<img id="img-main" />').appendTo(this.imgContainer);
        // Прогресс-бар
        $('<div/>').addClass('progress').attr('rel', '0').text('0%').appendTo(this.imgContainer);

        this.imgContainer.get(0).file = file;

        // Создаем объект FileReader и по завершении чтения файла, отображаем миниатюру и обновляем
        // инфу обо всех файлах
        var reader = new FileReader();
        reader.onload = (function(aImg) {
            return function(e) {
                // Прячем input file
                self.fileInput.hide();
                // Показать ссылку удаления изображения
                self.imgRemove.show();

                aImg.attr('src', e.target.result);
                aImg.attr('width', 100);
                this.imgSize += file.size;
            };
        })(img);

        reader.readAsDataURL(file);
    },

    updateProgress: function(bar, value){
        var width = bar.width();
        var bgrValue = -width + (value * (width / 100));
        bar.attr('rel', value).css('background-position', bgrValue+'px center').text(value+'%');
    },

    /** Загрузка файла на сервер */
    upload: function(){

        var self = this;

        var uploadItem = this.imgContainer.get(0);
        var pBar = $(uploadItem).find('.progress');

        new uploaderObject({
            file:       uploadItem.file,
            url:        '/admin/article/upload',
            fieldName:  'uploadimg',

            onprogress: function(percents) {
                self.updateProgress(pBar, percents);
            },

            oncomplete: function(done, data) {
                if(done) {
                    var msg = '';
                    if (data == 'ok') {
                        msg = 'Файл загружен успешно';
                        // Прячем кнопку загрузки
                        $('#img-upload').hide();
                        // Заполняем hidden поля данными
                        self.setSrc(uploadItem.file.name);
                    }
                    else
                        msg = data;
                    self.displayMsg(msg, 'ok' ? 'success' : 'error');
                    self.updateProgress(pBar, 100);
                } else {
                    self.displayMsg('При загрузке файла произошла ошибка');
                }
            }
        });
    },

    /** Заполняем инпуты для сохранения в бд */
    setSrc: function(filename){
        var name = unescape(encodeURIComponent(filename));
        this.imgSrc.val(this.imgFolder + name);
        var nameArr = name.split('.');
        var ext = nameArr.pop();
        this.imgThumb.val(this.imgFolder + nameArr.join('.') + '_thumb.' + ext);
    }
}