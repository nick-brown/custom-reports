@extends('layouts.master')

@section('body')
    {{ Form::open(['action' => 'ImportController@retrieve']) }}
        {{ Form::label('start_date', 'Start Date') }} 
		{{ Form::text('start_date', $last_sunday) }}
        <br>
        {{ Form::submit('Import') }}
    {{ Form::close() }}
@stop
