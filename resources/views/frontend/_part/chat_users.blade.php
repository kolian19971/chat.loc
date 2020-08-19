@foreach($chatUsers as $chatUser)
    <li style="list-style: none">
        <a href="/chat/{{ $chatUser->id }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center @if(isset($chatObj['toUser']->id) && $chatObj['toUser']->id == $chatUser->id) list-group-item-secondary isActive @endif">
            {{ $chatUser->email }}
            @if($chatUser->newCount > 0)
                <span class="badge badge-primary badge-pill">{{ $chatUser->newCount }}</span>
            @endif
        </a>
    </li>
@endforeach