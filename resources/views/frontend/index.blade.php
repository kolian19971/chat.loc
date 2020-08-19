@extends('layouts.app')

@section('meta')
    <meta name="toUserId" content="{{ isset($chatObj['toUser']->id) ? $chatObj['toUser']->id : 0 }}"/>
@endsection

@section('content')


    <div class="container">
        <div class="row mt-5">

            @if(count($chatUsers))

                <div class="col-md-3">

                    <div class="card">

                        <ul class="list-group" id="chatUsers">

                            @foreach($chatUsers as $chatUser)
                                <li style="list-style: none">
                                    <a href="/chat/{{ $chatUser->id }}"
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center @if(isset($chatObj['toUser']->id) && $chatObj['toUser']->id == $chatUser->id) list-group-item-secondary isActive @endif">
                                        {{ $chatUser->email }}
                                        @if($chatUser->newCount > 0)
                                            <span class="badge badge-primary badge-pill">{{ $chatUser->newCount }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach

                        </ul>

                    </div>

                </div>

            @endif


            <div class=" @if(count($chatUsers)) col-md-9 @else offset-md-2 col-md-8 @endif  ">
                <div class="card">
                    <div class="card-header">
                        <strong>
                            {{ Lang::get('chat.messages') }}
                        </strong>
                    </div>

                    <div class="card-body messageArea @if(isset($chatObj['toUser']->id)) withMessages @endif ">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(isset($chatObj['toUser']->id))




                            <div class="messageBlock" id="messageBlock">

                                <div id="messagesArea">
                                    @if(count($chatObj['messages']))

                                        @foreach($chatObj['messages'] as $message)

                                            <div data-id="{{ $message->id }}"
                                                 class="message @if($message->to_id == $currUser->id) in @else out @endif">

                                                <div class="content">

                                                    {!! nl2br(htmlspecialchars($message->content)) !!}

                                                    <div class="time">

                                                        {{ $message->created_at->format('d.m.Y h:i') }}

                                                    </div>

                                                </div>


                                            </div>

                                        @endforeach
                                    @endif
                                </div>

                                <form method="post" class="sendMessage" action="/sendMessage">

                                    {!! csrf_field() !!}

                                    <input type="hidden" name="to_id" value="{{ $chatObj['toUser']->id }}">

                                    <div class="form-group">
                                        <textarea required name="content" class="form-control"
                                                  placeholder="{{ Lang::get('chat.message') }}"></textarea>
                                    </div>

                                    <div class="text-right">
                                        <button class="btn btn-primary" id="sendMessage"
                                                type="submit">{{ Lang::get('chat.send') }}</button>
                                    </div>

                                </form>


                            </div>


                        @else
                            {{ Lang::get('chat.choseDialogue') }}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="loadingBlock">
        <div class="loadingio-spinner-spinner-s97e25y6s1m">
            <div class="ldio-3t5vu773qpj">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    @include('layouts._errors_form')

@endsection



@section('styles')
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
@endsection

@section('scripts')

    <script src="{{ asset('js/validate.js') }}"></script>
    <script src="{{ asset('js/chat.js') }}"></script>

    <script>

        $(document).ready(function () {

            var toUserId = $('meta[name=toUserId]').attr("content");

            hideLoading();

            setTimeout(function () {
                hideNewMessCount();
            }, 1000);

            if (toUserId > 0) {

                scrollChatToBottom();

                setTimeout(function () {

                    $("#messageBlock").scroll(function () {

                        var scrollTop = $(this).scrollTop();

                        if (scrollTop == 0) {

                            showLoading();

                            var firstMessage = $('#messagesArea .message').first(),
                                firtMessageId = firstMessage.data('id');

                            $.ajax({

                                url: '/loadMessages',

                                type: 'POST',

                                data: {
                                    toUserId: $('meta[name=toUserId]').attr("content"),
                                    toMessageId: firtMessageId,
                                    count: 20
                                },

                                success: function (data) {

                                    if (!isEmpty(data.messageHtml)) {

                                        $('#messagesArea').prepend(data.messageHtml);

                                        $("#messageBlock").scrollTop($('#messagesArea .message[data-id="' + firtMessageId + '"]').offset().top);

                                    }

                                    hideLoading();

                                },

                                error: function (data) {
                                    window.location.reload;
                                }

                            });


                        }


                    });


                }, 200);


            }


            $('#sendMessage').click(function (e) {

                e.preventDefault();

                var form = $(this).closest('form'),
                    messageTextObj = form.find('textarea[name="content"]');

                if (isEmpty(messageTextObj.val())) {

                    var error = ['{{ Lang::get("chat.contentNull") }}'];
                    printErrorMsg(error);

                } else {

                    $.ajax({

                        url: form.attr('action'),

                        type: 'POST',

                        data: form.serialize(),

                        success: function (data) {

                            showLoading();

                            //clear old message
                            messageTextObj.val('');

                            updateMessages();

                            hideLoading();

                        },

                        error: function (data) {
                            window.location.reload;
                        }

                    });

                }


            });


        });

        setInterval(function () {
            updateMessages();
        }, 10000);

    </script>

@endsection