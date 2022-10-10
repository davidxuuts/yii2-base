const language = {
    'en_US': {
        'crop': 'Crop',
        'move': 'Move',
        'zoom in': 'Zoom In',
        'zoom out': 'Zoom Out',
        'move left': 'Move Left',
        'move right': 'Move Right',
        'move up': 'Move Up',
        'move down': 'Move Down',
        'rotate left': 'Rotate Left',
        'rotate right': 'Rotate Right',
        'scale x': 'Scale X',
        'scale y': 'Scale Y',
        'reset': 'Reset',
        'Loading ...': 'Loading ...'
    },
    'zh_CN': {
        'crop': '裁剪',
        'move': '移动',
        'zoom in': '放大',
        'zoom out': '缩小',
        'move left': '左移',
        'move right': '右移',
        'move up': '上移',
        'move down': '下移',
        'rotate left': '向左旋转',
        'rotate right': '向右旋转',
        'scale x': '横向放大',
        'scale y': '纵向放大',
        'reset': '复位',
        'Loading ...': '加载中 ...'
    }
}
function i18n(name, lang) {
    lang = lang || 'zh_CN'
    if (language[lang] === undefined || language[lang][name] === undefined) {
        return 'undefined'
    }
    return language[lang][name]
}
