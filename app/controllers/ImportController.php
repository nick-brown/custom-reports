<?php

class ImportController extends BaseController {

	public function index()
	{
		return View::make('import.index');
	}

	public function retrieve()
	{
        define('ga_email', '***REMOVED***');
        define('ga_password', '***REMOVED***');
        define('ga_profile_id', '***REMOVED***');

        $ga = new GoogleRequest(ga_email, ga_password);

        $ga->requestReportData(
            ga_profile_id,
            array('customVarValue1', 'customVarValue2', 'country', 'region', 'city', 'latitude', 'longitude'),
            array('pageviews', 'uniquePageviews', 'goal16Completions', 'goal14Completions'),
            array('region'),
            'pagePath=~material-profile'
        );

        $results = $ga->getResults();

        $ga->requestReportData(
            ga_profile_id,
            array('customVarValue1', 'customVarValue2', 'country', 'region', 'city', 'latitude', 'longitude'),
            array('pageviews', 'uniquePageviews', 'goal16Completions', 'goal14Completions'),
            array('region'),
            'pagePath=~material-profile'
        );

        foreach($ga->getResults() as $k => $entry)
        {
            $entry->pushToDimensions(['customVarValue2' => $results[$k]->getDimensions()['customVarValue2']]);

            $metrics = $entry->getMetrics();
            $dimensions = $entry->getDimensions();

            $res = array_merge($metrics, $dimensions);

			$compl = new Completion($res);
			$compl->save();
        }
    }
}
