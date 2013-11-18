@extends('layouts.master')

@section('body')
    <% Form::open(['action' => 'ImportController@retrieve']) %>
        <% Form::label('start_date', 'Start Date') %> 
		<% Form::text('start_date', $last_sunday) %>
        <br>
        <% Form::label('test', 'Test Run?') %>
        <% Form::checkbox('test', '1', true) %>
        <br>
        <% Form::submit('Import') %>
    <% Form::close() %>
@stop
