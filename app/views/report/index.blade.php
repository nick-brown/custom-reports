@extends('layouts.master')

@section('scripts')
    <% HTML::script('//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js') %>
    <% HTML::script('//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular-resource.min.js') %>
    <% HTML::script('js/app.js') %>
@stop

@section('body')
    <div ng-app="app">
        <div id="filters" ng-controller="DropdownCtrl">
           Filter by:
            <select ng-options="sundays in dates">
                <option value="">Choose week...</option>
            </select>
            &amp;
            <select ng-options="partner in channelPartners">
                <option value="">Choose week...</option>
            </select>
            &amp;
            <select ng-options="material in materials">
                <option value="">Choose week...</option>
            </select>
            &amp;
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
    </div>
@stop
