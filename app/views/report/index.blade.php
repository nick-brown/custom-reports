@extends('layouts.master')

@section('scripts')
    {{ HTML::script('//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js') }}
    {{ HTML::script('js/app.js') }}
    <script>
        var analytics = { 
            completions: {{ $completions }},
            events: {{ $events }} 
        };
    </script>
@stop

@section('body')
    <div id="filters">
       Filter by: 
        {{ Form::select('start_of_week', $sundays) }} &amp;
        {{ Form::select('channelPartner') }} &amp;
        {{ Form::select('materialName') }}
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
