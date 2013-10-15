<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index()
	{
        define('ga_email', '***REMOVED***');
        define('ga_password', '***REMOVED***');
        define('ga_profile_id', '***REMOVED***');

        $ga = new GoogleRequest(ga_email, ga_password);

        $ga->requestReportData(
            ga_profile_id,
            array('customVarValue1', 'customVarValue2', 'hostname', 'latitude', 'longitude'),
            array('pageviews', 'uniquePageviews'),
            NULL,
            'campaign=~DLMPT'
        );

        $results = $ga->getResults();

        $ga->requestReportData(
            ga_profile_id,
            array('customVarValue1', 'customVarValue2', 'hostname', 'country', 'region', 'city', 'latitude'),
            array('pageviews', 'uniquePageviews'),
            NULL,
            'campaign=~DLMPT'
        );

        foreach($ga->getResults() as $k => $entry)
        {
            $entry->pushToDimensions(['longitude' => $results[$k]->getDimensions()['longitude']]);
        }

        foreach($ga->getResults() as $entry)
        {
            $metrics = $entry->getMetrics();
            $dimensions = $entry->getDimensions();

            $res = array_merge($metrics, $dimensions);

//		var_dump($entry->getMetrics());
//		var_dump($entry->getDimensions());
        }
        echo "<pre>";
        print_r($res);
        echo "</pre>";
        die();
    }
}