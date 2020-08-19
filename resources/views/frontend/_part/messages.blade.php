@if(count($chatObj['messages']))

    @foreach($chatObj['messages'] as $message)

        <div data-id="{{ $message->id }}" class="message @if($message->to_id == $currUser->id) in @else out @endif">

            <div class="content">

                {!! nl2br(htmlspecialchars($message->content)) !!}

                <div class="time">

                    {{ $message->created_at->format('d.m.Y h:i') }}

                </div>

            </div>


        </div>

    @endforeach
@endif