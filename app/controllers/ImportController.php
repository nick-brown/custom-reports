<?php

class ImportController extends BaseController {

	public function index()
	{
        $data['last_sunday'] = date('Y-m-d', strtotime('last Sunday'));
        
		return View::make('import.index', $data);
	}

	public function retrieve()
	{
        // Get user-defined start date
        $start_date = Input::get('start_date');

        // Set end date a week from the start date
        // $end_date = date('Y-m-d', strtotime('+1 week', strtotime($start_date)));
        $end_date = date('Y-m-d'); 

        // Define GA account constants
        define('ga_email', '***REMOVED***');
        define('ga_password', '***REMOVED***');
        define('ga_profile_id', '***REMOVED***');

        // Create a new Request object and retrieve our first 7 dimensions
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

        // Save our dimensions
        $results = $completions->getResults();

        // Request a new set of data so we can add hostname
        $completions->requestReportData(
            ga_profile_id,
            array('hostname', 'customVarValue1', 'customVarValue2', 'country', 'region', 'city', 'latitude'),
            array('pageviews', 'uniquePageviews', 'goal16Completions', 'goal14Completions'),
            array('region'),
            'pagePath=~material-profile',
            $start_date,
            $end_date 
        );

        // Iterate over completions, combining the data into one array
        foreach($completions->getResults() as $k => $entry)
        {
            $entry->pushToDimensions(['longitude' => $results[$k]->getDimensions()['longitude']]);

            $metrics = $entry->getMetrics();
            $dimensions = $entry->getDimensions();

            $combined[] = array_merge($metrics, $dimensions);
        }

        // If this is a test run, pass data to the test method and return the resulting view
        if( Input::get('test') == 1 )
        {
            return $this->test($results, $completions->getResults(), $combined);
        }
        
        // If we're still running at this point, instatiate and save our completions
        foreach($combined as $completion)
        {
            $compl = new Completion($completion);
            $compl->start_of_week = $start_date;
			$compl->save();
        }

        return 'Imported successfully';
    }

    private function test($first, $second, $combined)
    {
        return View::make('import.test', ['first' => $first, 'second' => $second, 'combined' => $combined]);
    }
}
