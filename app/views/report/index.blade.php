@extends('layouts.master')

@section('scripts')
    <% HTML::script('//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js') %>
    <% HTML::script('//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular-resource.min.js') %>
    <% HTML::script('js/app.js') %>
@stop

@section('body')
    <div ng-app="app">
        <div ng-controller="DropdownCtrl">
           Filter by:
            <select ng-options="week for week in sundays" ng-model="selected_week" ng-change="changeDate()">
                <option value="">Choose week...</option>
            </select>
            &amp;
            <select ng-options="partner for partner in channelPartners" ng-model="selected_partner">
                <option value="">Choose channel partner...</option>
            </select>
            &amp;
            <select ng-options="material for material in materials" ng-model="selected_material">
                <option value="">Choose material...</option>
            </select>

            <h2>DLMPT Profile Pages Report for the week of {{ selected_week || "No week selected" }}</h2>
            <h3>For {{ selected_partner || "No Channel Partner Selected" }} profile pages</h3>

            <br>

            <h3>Totals</h3>
            <div id="totals">
                DLMPT Leads: {{ stats.dlmptLeads }}
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
    </div>
@stop
