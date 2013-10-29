@extends('layouts.master')

@section('head')
    {{ HTML::script('js/angular.min.js') }}
@stop

@section('body')
    <div id="filters">
       Filter by: {{ Form::select('weekNumber') }} &amp; {{ Form::select('channelPartner') }} &amp; {{ Form::select('materialName') }}
    </div>

    <h2>DLMPT Profile Pages Report for the week of $week</h2>
    <h3>For $channelPartner profile pages</h3>

    <br>

    <h3>Totals</h3>

    <div id="totals">
        DLMPT Leads: 
        <br>
        All Leads including DLMPT Leads:
        <br>
        Image Clicks:
        <br>
        Button Clicks:
        <br>
        Unique Visitors:
        <br>
        Pageviews:
    </div>
@stop
