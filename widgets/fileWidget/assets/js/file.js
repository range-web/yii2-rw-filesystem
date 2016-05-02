var rwFileInput = {
    hideFileInfo: false,
    init: function() {
        $(document)
            .on('click', '.fileinput-remove', this.removeFiles)
            .on('click', '.remove-file', this.removeFile)
    },
    callBackAfterDelete: function() {},
    addFileInfo: function(e, data) {

        var el = $(e),
            parentElement = el.parents('.rw-file-input'),
            requiredClass = '';
        if (data.id > 0) {
            parentElement.append('<input class="'+el.attr('id')+' uploaded-file form-control create-file" type="hidden" data-url="'+data.url+'" data-file="'+data.id+'" data-title="'+data.originalName+'" name="'+el.data('fieldname')+'" value="'+data.id+'">');
        }
    },
    uploadDone: function(e) {
        var parentElement = $(e).parents('.rw-file-input');
        parentElement.find('.progress').hide();
        if (!rwFileInput.hideFileInfo) {
            parentElement.find('.file-caption-name').show();
        }
        this.getInfoFiles(parentElement);
    },
    progress: function(e, data) {
        var parentElement = $(e).parents('.rw-file-input'),
            progressBar = parentElement.find('.progress'),
            progress = parseInt(data.loaded / data.total * 100, 10);

        parentElement.find('.file-caption-name').hide();
        progressBar.show();
        progressBar.find('.progress-bar').css(
            'width',
            progress + '%'
        );

        progressBar.attr('data-percent', progress + '%');
    },
    getInfoFiles: function(fileWrap) {
        var inputClass = fileWrap.find('.btn-file').find('input').attr('id'),
            files = fileWrap.find('.'+inputClass),
            fileInfo = fileWrap.find('.info-upload-files'),
            btnRemove = fileWrap.find('.fileinput-remove');
            if (!rwFileInput.hideFileInfo) {
                btnRemove.show();
            }
        if (files.length > 1) {
            fileWrap.find('.file-caption-name').text(files.length+' '+this.declinationWords(files.length, 'файл', 'файла', 'файлов'));

            if (!rwFileInput.hideFileInfo) {
                fileInfo.empty().show();
            }

            files.each(function(i, element) {
                var file = $(element);
                fileInfo.append('<div class="file-item" data-file-id="'+file.val()+'">'+file.data('title')+'<span class="remove-file">&times;</span></div>')
            });

        } else if (files.length == 1) {
            fileInfo.empty().hide();
            fileWrap.find('.file-caption-name').text(jQuery(files[0]).data('title'));
        } else {
            fileInfo.empty().hide();
            fileWrap.find('.fileinput-remove').hide();

            var captionName = fileWrap.find('.file-caption-name');
            if (captionName.data('placeholder').length > 0) {
                captionName.text(captionName.data('placeholder'));
            } else {
                captionName.empty();
            }

            rwFileInput.callBackAfterDelete();
        }
    },
    removeFile: function() {
        var btn = $(this),
            parent = btn.parent(),
            fileId = parent.data('file-id'),
            fieldDeleteName = parent.parent().data('field-delete-name'),
            parentElement = btn.parents('.rw-file-input'),
            issetUpdateField = $('.update-file[data-file='+fileId+']').length;
            if (issetUpdateField) {
                parentElement.append('<input class="form-control delete-file" type="hidden" name="'+fieldDeleteName+'" value="'+fileId+'">');
            }
            $.ajax({
            url: parentElement.find('.fileinput-remove').data('remove-url'),
            type: 'post',
            dataType: 'json',
            data: {id:fileId},
            success: function(data) {
                if (data.status) {
                    parent.remove();
                    parentElement.find('input[data-file='+fileId+']').remove();
                    rwFileInput.getInfoFiles(parentElement);
                }
            }
            });
    },
    removeFileById: function(id) {
        $.ajax({
            url: $('[data-remove-url]').data('remove-url'),
            type: 'post',
            dataType: 'json',
            data: {id:id},
            success: function(data) {
                rwFileInput.callBackAfterDelete();
            }
        });
    },
    removeFiles: function() {
        
        if (!confirm('Вы действительно хотите удалить?')) return false;

            var btn = $(this),
                parentElement = btn.parents('.rw-file-input'),
            fileInfo = parentElement.find('.info-upload-files'),
            inputClass = parentElement.find('.btn-file').find('input').attr('id'),
            files = parentElement.find('.'+inputClass),
            ids = [],
            fieldDeleteName = $('.info-upload-files').data('field-delete-name');

        files.each(function(i, element) {
            var file = $(element);
            ids.push(file.val());
            issetUpdateField = $('.update-file[data-file='+file.val()+']').length;
            if (issetUpdateField) {
                parentElement.append('<input class="form-control delete-file" type="hidden" name="'+fieldDeleteName+'" value="'+file.val()+'">');
            }
        });

        $.ajax({
            url: parentElement.find('.fileinput-remove').data('remove-url'),
            type: 'post',
            dataType: 'json',
            data: {id:ids},
            success: function(data) {
                if (data.status) {
                    files.remove();
                    fileInfo.empty().hide();
                    btn.hide();
                    var captionName = parentElement.find('.file-caption-name');
                    captionName.empty();
                    //if (captionName.data('placeholder').length > 0) {
                    //    captionName.text(captionName.data('placeholder'));
                    //} else {
                    //    captionName.empty();
                    //}

                    rwFileInput.callBackAfterDelete();
                }
            }
        });
    },
    declinationWords: function(number, one, two, five) {
        number = Math.abs(number);
        number %= 100;
        if (number >= 5 && number <= 20) {
            return five;
        }
        number %= 10;
        if (number == 1) {
            return one;
        }
        if (number >= 2 && number <= 4) {
            return two;
        }
        return five;
    },
}
rwFileInput.init();