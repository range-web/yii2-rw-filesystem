var rwFileInput = {
    init: function() {
        $(document)
            .on('click', '.fileinput-remove', this.removeFiles)
            .on('click', '.remove-file', this.removeFile)
    },
    addFileInfo: function(e, data) {
        var el = $(e),
            parentElement = el.parents('.rw-file-input');

        if (data.id > 0) {
            parentElement.append('<input class="'+el.attr('id')+'" type="hidden" data-file="'+data.id+'" data-title="'+data.originalName+'" name="'+el.data('fieldname')+'" value="'+data.id+'">');
        }
    },
    uploadDone: function(e) {
        this.getInfoFiles($(e).parents('.rw-file-input'));
    },
    getInfoFiles: function(fileWrap) {
        var inputClass = fileWrap.find('.btn-file').find('input').attr('id'),
            files = fileWrap.find('.'+inputClass),
            fileInfo = fileWrap.find('.info-upload-files'),
            btnRemove = fileWrap.find('.fileinput-remove');

        btnRemove.show();
        if (files.length > 1) {
            fileWrap.find('.file-caption-name').text(files.length+' файла');
            fileInfo.empty().show();
            files.each(function(i, element) {
                var file = $(element);
                fileInfo.append('<div class="file-item" data-file-id="'+file.val()+'">'+file.data('title')+'<span class="remove-file">&times;</span></div>')
            });
        } else if (files.length == 1) {
            fileWrap.find('.file-caption-name').text(jQuery(files[0]).data('title'));
        } else {
            fileInfo.empty().hide();
            fileWrap.find('.fileinput-remove').hide();
            fileWrap.find('.file-caption-name').empty();
        }
    },
    removeFile: function() {
        var btn = $(this),
            parent = btn.parent(),
            fileId = parent.data('file-id'),
            parentElement = btn.parents('.rw-file-input');

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
    removeFiles: function() {
        var btn = $(this),
            parentElement = btn.parents('.rw-file-input'),
            fileInfo = parentElement.find('.info-upload-files'),
            inputClass = parentElement.find('.btn-file').find('input').attr('id'),
            files = parentElement.find('.'+inputClass),
            ids = [];

        files.each(function(i, element) {
            var file = $(element);
            ids.push(file.val());
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
                    parentElement.find('.file-caption-name').empty();
                }
            }
        });


    },
}
rwFileInput.init();