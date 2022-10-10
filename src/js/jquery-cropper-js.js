function getImgSrc(image) {
    return new Promise(resolve => {
        let moxieImg = new window.moxie.image.Image();
        moxieImg.onload = function () {
            const img = '<img src="' + moxieImg.getAsDataURL() + '" id="image-cropper">'
            resolve(img)
        }
        moxieImg.load(image.getSource())
    })
}
const modalCropper = function (image) {
    let buttons = new Array()
    let imgSrc
    const props = 'type="button" class="btn btn-sm btn-dark" data-toggle="popover" data-trigger="hover" data-placement="top"'
    buttons = [
        '<span ' + props + 'data-method="setDragMode" data-option="move" data-content="' + i18n('move') +
        '"><i class="fas fa-arrows-alt"></i></span>',
        '<span ' + props + 'data-method="setDragMode" data-option="crop" data-content="' + i18n('crop') +
        '"><i class="fas fa-crop-alt"></i></span>',
        '<span ' + props + 'data-method="zoom" data-option="0.1" data-content="' + i18n('zoom in') +
        '"><i class="fas fa-search-plus"></i></span>',
        '<span ' + props + 'data-method="zoom" data-option="-0.1" data-content="' + i18n('zoom out') +
        '"><i class="fas fa-search-minus"></i></span>',
        '<span ' + props + 'data-method="move" data-option="-10" data-option="0" data-content="' +
        i18n('move left') + '"><i class="fas fa-arrow-left"></i></span>',
        '<span ' + props + 'data-method="move" data-option="10" data-option="0" data-content="' +
        i18n('move right') + '"><i class="fas fa-arrow-right"></i></span>',
        '<span ' + props + 'data-method="move" data-option="0" data-option="-10" data-content="' +
        i18n('move up') + '"><i class="fas fa-arrow-up"></i></span>',
        '<span ' + props + 'data-method="move" data-option="0" data-option="10" data-content="' +
        i18n('move down') + '"><i class="fas fa-arrow-down"></i></span>',
        '<span ' + props + 'data-method="rotate" data-option="-45" data-content="' + i18n('rotate left') +
        '"><i class="fas fa-undo-alt"></i></span>',
        '<span ' + props + 'data-method="rotate" data-option="45" data-content="' + i18n('rotate right') +
        '"><i class="fas fa-redo-alt"></i></span>',
        '<span ' + props + 'data-method="scaleX" data-option="-1" data-content="' + i18n('scale x') +
        '"><i class="fas fa-arrows-alt-h"></i></span>',
        '<span ' + props + 'data-method="scaleY" data-option="-1" data-content="' + i18n('scale y') +
        '"><i class="fas fa-arrows-alt-v"></i></span>',
        '<span ' + props + 'data-method="reset" data-content="' + i18n('reset') +
        '"><i class="fas fa-sync-alt"></i></span>',
    ]
    const cropActions = '<div id="cropper-button-group">' + buttons.join("\n") + '</div>'
    return getImgSrc(image).then(img => {
        return '<div class="container img-container">' + img + cropActions + '</div>'
    })
}

function cropImg(up, originalImg, aspectRatio, quality, modal) {
    return new Promise(resolve => {
        if (!originalImg) {
            resolve(false)
        }
        modal = modal || $('#modalUp')
        modal.modal('show')
        modal.find('.modal-body').html(originalImg)
        modal.find('.modal-dialog').addClass('modal-xl')
        const image = $('#image-cropper')
        if (!image || image.length === 0) {
            return
        }
        aspectRatio = aspectRatio || 1 / 1
        quality = quality || 1
        let imgSrc, result
        const options = {
            background: false,
            aspectRatio: aspectRatio,
            viewMode: 0,
            minContainerHeight: 300,
            minContainerWidth: 450,
        }
        $('[data-toggle="popover"]').popover()
        image.on({
            ready: function () {
                $(modal).on('click', 'span', function (event) {
                    event.preventDefault()
                    let $this = $(this)
                    let data = $this.data()
                    let cropper = image.data('cropper')
                    let cropped
                    let $target
                    if ($this.prop('disabled') || $this.hasClass('disabled')) {
                        return
                    }
                    if (cropper && data.method) {
                        data = $.extend({}, data)
                        if (typeof data.target !== 'undefined') {
                            $target = $(data.target);
                            if (typeof data.option === 'undefined') {
                                try {
                                    data.option = JSON.parse($target.val());
                                } catch (e) {}
                            }
                        }
                        cropped = cropper.cropped
                        switch (data.method) {
                            case 'rotate':
                                if (cropped && options.viewMode > 0) {
                                    image.cropper('clear')
                                }
                                break
                        }
                        result = image.cropper(data.method, data.option, data.secondOption)
                        switch (data.method) {
                            case 'rotate':
                                if (cropped && options.viewMode > 0) {
                                    image.cropper('crop')
                                }
                                break
                            case 'scaleX':
                            case 'scaleY':
                                $(this).data('option', -data.option)
                                break
                            case 'getCroppedCanvas':
                                if (result) {
                                    imgSrc = result.toDataURL('image/jpg', quality)
                                    modal.modal('hide')
                                    image.cropper('destroy')
                                    resolve(imgSrc ?? false)
                                }
                                break
                        }
                        if ($.isPlainObject(result) && $target) {
                            try {
                                $target.val(JSON.stringify(result))
                            } catch (e) {}
                        }
                    }
                })
            }
        }).cropper(options)
        modal.on('hidden.bs.modal', function (event) {
            if (!result) {
                resolve(false)
            }
        })
    })
 }
