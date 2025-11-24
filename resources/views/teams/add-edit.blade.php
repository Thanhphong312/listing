@extends('layouts.app')

@section('page-title', __('Roles'))
@section('page-heading', $edit ? $team->name : __('Create New Role'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('roles.index') }}">@lang('Roles')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

@include('partials.messages')

@if ($edit)
    {!! Form::open(['route' => ['teams.update', $team], 'method' => 'PUT', 'id' => 'team-form']) !!}
@else
    {!! Form::open(['route' => 'teams.store', 'id' => 'team-form']) !!}
@endif
<div class="element-box">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Team Details')
                    </h5>
                    <p class="text-muted">
                        @lang('A general team information.')
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="name"
                               name="name"
                               placeholder="@lang('Team Name')"
                               value="{{ $edit ? $team->name : old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="link_page">@lang('Link page')</label>
                        <input type="text"
                               class="form-control input-solid"
                               id="link_page"
                               name="link_page"
                               placeholder="@lang('Link page')"
                               value="{{ $edit ? $team->link_page : old('Link page') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">
        {{ __($edit ? 'Update Team' : 'Create Team') }}
    </button>
</div>


@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Team\UpdateTeamsRequest', '#team-form') !!}
    @else
        {!! JsValidator::formRequest('Vanguard\Http\Requests\Team\CreateTeamRequest', '#team-form') !!}
    @endif
@stop
