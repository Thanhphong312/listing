<div class="row">
    @foreach($users as $user)
        <div class="col-12 d-flex align-items-center mb-2">
            <div class="col-auto">
                <input type="checkbox" id="check_user_{{$user->id}}" onclick="accepttemp('{{$user->id}}','{{$id}}')" {{checkaccept($user->id, $id)?'checked':''}}>
            </div>
            <div class="col">
                <span>{{$user->username}}</span>
            </div>
            <div class="col">
                
            </div>
        </div>
    @endforeach
</div>

<style>
    .row .col-auto input[type="checkbox"] {
        transform: scale(1.2);
    }

    .row .col span {
        font-size: 1rem;
        font-weight: 500;
    }
</style>