@extends('layouts.master')

@section('scripts')
    <% HTML::script('//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js') %>
    <% HTML::script('//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular-resource.min.js') %>
    <% HTML::script('js/app.js') %>
    <script>
        app.service('storage', function() {
            this.analytics = <% json_encode($analytics) %>;
            this.sundays = <% json_encode($sundays) %>;
        });
    </script>
@stop

@section('body')
    <div ng-app="app">
        <div ng-controller="DropdownCtrl">
           Filter by:
            <select ng-options="week for week in sundays" ng-model="selected_week" ng-change="changeDate()">
                <option value="">Choose week...</option>
            </select>
            &amp;
            <select ng-options="partner for partner in channelPartners" ng-model="selected_partner" ng-change="changePartner()">
                <option value="">All channel partners...</option>
            </select>
            &amp;
            <select ng-options="material for material in materials" ng-model="selected_material" ng-change="changeMaterial()">
                <option value="">All Materials...</option>
            </select>

            <h2>DLMPT Profile Pages Report for the week of {{ selected_week || "No week selected" }}</h2>
            <h3>For {{ selected_partner || "No Channel Partner Selected" }} profile pages</h3>

            <br>

            <h3>Totals</h3>
            <div id="totals">
                DLMPT Leads: {{ stats.dlmptLeads }}
                <br>
                All Leads including DLMPT Leads: {{ stats.allLeads }}
                <br>
                Image Clicks: {{ stats.imageClicks }}
                <br>
                Button Clicks: {{ stats.buttonClicks }}
                <br>
                Unique Visitors: {{ stats.uniquePageviews }}
                <br>
                Pageviews: {{ stats.pageviews }}
            </div>
        </div>

        <br>

        <div ng-controller="MonthlyCtrl">
            <table>
                <tr>
                    <th>Month</th>
                    <th>{{ current_year || 2013 }} leads</th>
                    <th>{{ last_year || 2012 }} leads</th>
                </tr>
                <tr ng-repeat="month in months">
                    <td>{{ month }}</td>

                    <td>{{ leadsByMonth.thisYear[month] || 0 }}</td>

                    <td>{{ leadsByMonth.lastYear[month] || 0 }}</td>
                </tr>
            </table>
        </div>
    </div>
@stop
