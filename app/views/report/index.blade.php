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
    <style type="text/css">
        #table { width:300px; border-right:1px solid #000;  }
        #table > div.col { width:100px; margin:0; padding:0; float:left; text-align:center; border:1px solid #000; border-right:0px; border-top:0px; box-sizing:border-box; }
        div.col.last { border-right:1px solid #000 !important; }
        div.col > div { border-top:1px solid #000; }
    </style>
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

            <br>

            <div ng-controller="MonthlyCtrl">
                <div id="table">
                    <div class="col">
                        <div class="header">Month</div>
                        <div ng-repeat="month in months">{{ month }}</div>
                    </div>

                    <div class="col">
                        <div class="header">{{ current_year || 2013 }} leads</div>
                        <div ng-repeat="leads in currLeads">{{ leads || "n/a" }}</div>
                    </div>

                    <div class="last col">
                        <div class="header">{{ last_year || 2012 }} leads</div>
                        <div ng-repeat="leads in lastLeads">{{ leads || "n/a" }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
