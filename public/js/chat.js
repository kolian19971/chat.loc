function hideNewMessCount() {

    $('ul.list-group li').each(function () {

        var link = $(this).find('a');

        if (link.hasClass('isActive')) {
            link.find('.badge.badge-primary.badge-pill').fadeOut(300);
        }

    });
}

function showLoading(time = 100) {
    $('.loadingBlock').fadeIn(time);
}


function hideLoading(time = 300) {
    $('.loadingBlock').fadeOut(time);
}


function scrollChatToBottom() {
    //scroll to the bottom
    var messageBlockDiv = $("#messageBlock");
    messageBlockDiv.scrollTop(messageBlockDiv.prop('scrollHeight'));
}


function updateMessages() {


    var lastShowMessage = $('#messagesArea .message'),
        lastShowMessageId = 0;

    if (typeof lastShowMessage !== 'undefined') {
        lastShowMessageId = lastShowMessage.last().data('id');
    }

    $.ajax({

        url: '/getMessUpdate',

        type: 'POST',

        data: {
            toUserId: $('meta[name=toUserId]').attr("content"),
            lastShowMessageId: lastShowMessageId
        },

        success: function (data) {

            if (!isEmpty(data.chatUsersHtml)) {
                $('#chatUsers').html(data.chatUsersHtml);
            }

            if (!isEmpty(data.messageHtml)) {
                $('#messagesArea').append(data.messageHtml);
                scrollChatToBottom();
            }

        },

        error: function (data) {
            window.location.reload;
        }

    });

}