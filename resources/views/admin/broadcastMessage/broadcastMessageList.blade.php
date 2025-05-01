@extends('admin.broadcastMessage.default')

@section('broadcastMessage')
<div class="message-list">
    <div class="message-list-header">
        <div>日付</div>
        <div>メッセージ</div>
    </div>
    <div class="message-list-body">
        @foreach ($broadcastMessages as $messages)
                <div class="message-item">
                    <div class="message-header">
                        <div class="message-date">{{ $messages[0]->created_at->format("Y-m-d H:i") }}</div>
                        <div>
                            <span class="toggle-icon">▶</span>
                            {{ $messages[0]->resource_type == "broadcast_text" ? 
                            (mb_strlen($messages[0]->resource) > 100 ? mb_substr($messages[0]->resource, 0, 100) . '...' : $messages[0]->resource) 
                            : "画像" }}
                        </div>
                    </div>
                    <div class="message-content">
                        <div class="chat-messages">
                            @foreach ($messages as $message)
                                <div class="chat-bubble message-sent">
                                    @if ($message->resource_type === "broadcast_text")
                                            <p>{{$message->resource}}</p>
                                    @elseif($message->resource_type === "broadcast_img")
                                            <img src="{{ Storage::disk('s3')->url('images/' . $message->resource) }}" alt="" class="broadcast_message_img">
                                    @endif
                                    <span class="message-time">{{$message->created_at->format("H:i")}}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
        @endforeach
    </div>
    @if(isset($paginator))
        {{ $paginator->links('pagination::custom') }}
    @endif
</div>
@endsection


