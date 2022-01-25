function successNotification(message) {
    $.amaran({
        'theme' : 'colorful',
        'content' : {
            bgcolor : '#336699',
            color : '#fff',
            message : '<i class="fa fa-check-circle"></i> ' + message
        },
        'position' : 'top right',
        'outEffect' : 'slideBottom'
    });
}

function deleteNotification(message) {
    $.amaran({
        'theme' : 'colorful',
        'content' : {
            bgcolor : '#c9302c',
            color : '#fff',
            message : '<i class="fa fa-trash"></i> ' + message
        },
        'position' : 'top right',
        'outEffect' : 'slideBottom'
    });
}

function errorNotification(message) {
    $.amaran({
        'theme' : 'colorful',
        'content' : {
            bgcolor : '#c9302c',
            color : '#fff',
            message : '<i class="fa fa-ban"></i> ' + message
        },
        'position' : 'top right',
        'outEffect' : 'slideBottom'
    });
}

function warningNotification(message) {
    $.amaran({
        'theme' : 'colorful',
        'content' : {
            bgcolor : '#f39c12',
            color : '#fff',
            message : '<i class="fa fa-warning"></i> ' + message
        },
        'position' : 'top right',
        'outEffect' : 'slideBottom'
    });
}
