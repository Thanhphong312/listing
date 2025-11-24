<!-- resources/views/yourmodel/_state_buttons.blade.php -->

<div class="btn-group">
    <button type="button" class="btn btn-sm btn-primary @if($transition) dropdown-toggle @endif" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ $model->state::${'label'} }}
    </button>
    @if($transition)
    <div class="dropdown-menu">
        @foreach ($model->state->all() as $name => $state)
        @if ($model->state::${'name'} != $name && $state != $model->getCasts()['state'] && $model->state->canTransitionTo($state))
        <form class="dropdown-item mb-0" method="post" action="{{ route($stateRoute, ['model' => $model, 'state' => $state::${'name'}]) }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-link">{{ $state::${'label'} }}</button>
        </form>
        @endif
        @endforeach
    </div>
    @endif
</div>