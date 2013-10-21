<?php

class ImportController extends BaseController {

	public function index()
	{
        $data['last_sunday'] = date('Y-m-d', strtotime('last Sunday'));
        
		return View::make('import.index', $data);
	}

	public function retrieve()
	{
        $start_date = Input::get('start_date');
        $end_date = date('Y-m-d', strtotime('+1 week', strtotime($start_date)));
        
        define('ga_email', '***REMOVED***');
        define('ga_password', '***REMOVED***');
        define('ga_profile_id', '***REMOVED***');

        $completions = new GoogleRequest(ga_email, ga_password);

        $completions->requestReportData(
            ga_profile_id,
            array('customVarValue1', 'customVarValue2', 'country', 'region', 'city', 'latitude', 'longitude'),
            array('pageviews', 'uniquePageviews', 'goal16Completions', 'goal14Completions'),
            array('region'),
            'pagePath=~material-profile',
            $start_date,
            $end_date
        );

        $results = $completions->getResults();

        $completions->requestReportData(
            ga_profile_id,
            array('hostname', 'customVarValue1', 'customVarValue2', 'country', 'region', 'city', 'latitude'),
            array('pageviews', 'uniquePageviews', 'goal16Completions', 'goal14Completions'),
            array('region'),
            'pagePath=~material-profile',
            $start_date,
            $end_date 
        );

        foreach($completions->getResults() as $k => $entry)
        {
            $entry->pushToDimensions(['customVarValue2' => $results[$k]->getDimensions()['customVarValue2']]);

            $metrics = $entry->getMetrics();
            $dimensions = $entry->getDimensions();

            $res[] = array_merge($metrics, $dimensions);
        }

        if( 1 == 1 )
        {
            return $this->test($results, $completions->getResults(), $res);
        }
        
        foreach($completions->getResults() as $k => $entry)
        {
            $compl = new Completion($res);
            $compl->start_of_week = $start_date;
			$compl->save();
        }

        return 'Imported successfully';
    }

    private function test($first, $second, $combined)
    {
        return View::make('import.test', ['first' => $first, 'second' => $second, 'combined' => $combined]);
    }

	//public function test()
	//{
    //    $start_date = '2013-09-01';
    //    $end_date = '2013-10-15';
    //    
    //    define('ga_email', '***REMOVED***');
    //    define('ga_password', '***REMOVED***');
    //    define('ga_profile_id', '***REMOVED***');

    //    $completions = new GoogleRequest(ga_email, ga_password);

    //    $completions->requestReportData(
    //        ga_profile_id,
    //        array('customVarValue1', 'customVarValue2', 'country', 'region', 'city', 'latitude', 'longitude'),
    //        array('pageviews', 'uniquePageviews', 'goal16Completions', 'goal14Completions'),
    //        array('region'),
    //        'pagePath=~material-profile',
    //        $start_date,
    //        $end_date
    //    );

    //    $results = $completions->getResults();

    //    $completions->requestReportData(
    //        ga_profile_id,
    //        array('hostname', 'customVarValue1', 'customVarValue2', 'country', 'region', 'city', 'latitude'),
    //        array('pageviews', 'uniquePageviews', 'goal16Completions', 'goal14Completions'),
    //        array('region'),
    //        'pagePath=~material-profile',
    //        $start_date,
    //        $end_date 
    //    );

    //    foreach($completions->getResults() as $k => $entry)
    //    {
    //        $entry->pushToDimensions(['longitude' => $results[$k]->getDimensions()['longitude']]);

    //        $metrics = $entry->getMetrics();
    //        $dimensions = $entry->getDimensions();

    //        $res[] = array_merge($metrics, $dimensions);
    //    }
    //    
    //    return View::make('import.test', ['first' => $results, 'second' => $completions->getResults(), 'combined' => $res]);
    //}
}
