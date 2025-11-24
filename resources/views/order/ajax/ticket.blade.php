<span>Tickets #</span>
@if(!is_null($order) && $order->supports->count() > 0)
    @php 
        $supports = $order->supports->where('status','!=','Solved');
    @endphp
    @foreach($supports as $support)
        @php 
        $orderChats = $support->chats->filter(function ($chat) {
            return $chat->user->role_id == 3;
        })->sortByDesc('created_at');
        if($orderChats->count() > 0){
            $message = $orderChats->first()->message;
                                        //kiểm tra $message là text không phải url
            $tc = "";
            if (!filter_var($message, FILTER_VALIDATE_URL)) {
                $tc = $message;
            } else {
            // $message là một URL
            // Thực hiện các hành động khác nếu cần
                $tc = "This is a URL ảnh";
            }
        }else{
            $tc = $support->subject;
        }
        @endphp
        <a href="/tickets/view/{{$support->id}}" style="color:red"  data-toggle="tooltip" data-placement="top" title="{{$tc}}">
            <strong>{{substr($tc, 0, 80)}}</strong>
        </a>
@endforeach
@endif
