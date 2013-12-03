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
        $end_date = date('Y-m-d', strtotime('+6 days', strtotime($start_date)));

        // Check if the supplie date range has already been imported into the database
        $completions = new Completion;
        $overlaps = $completions::where('start_of_week', '>=', $start_date)->count();

        if($overlaps > 0)
        {
            App::abort(500, 'Date range already imported into the database');
        }

        // Define GA account constants
        define('ga_email', '***REMOVED***');
        define('ga_password', '***REMOVED***');
        define('ga_profile_id', '***REMOVED***');

        if(Input::get('test') == 1)
        {
            return $this->getCompletions($start_date, $end_date);
        }
        else
        {
            $this->getCompletions($start_date, $end_date);
        }
        $this->getEvents($start_date, $end_date);
        
        return 'Imported successfully';
    }


    // Private

    private function test($first, $second, $combined)
    {
        return View::make('import.test', ['first' => $first, 'second' => $second, 'combined' => $combined]);
    }

    private function getCompletions($start_date, $end_date) 
    {
        // Create a new Request object and retrieve our first 7 dimensions
        $completions = new GoogleRequest(ga_email, ga_password);

        $completions->requestReportData(
            ga_profile_id,
            array('customVarValue1', 'customVarValue2', 'country', 'region', 'city', 'hostname', 'longitude'),
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
        if(Input::get('test') == 1)
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
    }

    private function getEvents($start_date, $end_date) 
    {
        // Create a new Request object and retrieve our first 7 dimensions
        $events = new GoogleRequest(ga_email, ga_password);

        $events->requestReportData(
            ga_profile_id,
            array('customVarValue1', 'customVarValue2', 'country', 'region', 'city', 'hostname', 'eventCategory'),
            array('totalEvents'),
            array('region'),
            'pagePath=~material-profile;ga:eventCategory=~Image Thumbnail,ga:eventCategory=~Call to Action Buttons',
            $start_date,
            $end_date
        );

        var_dump($events->getResults());

        $results = $events->getResults();

        // If we're still running at this point, instatiate and save our events
        foreach($results as $entry)
        {
            $metrics = $entry->getMetrics();
            $dimensions = $entry->getDimensions();

            $combined[] = array_merge($metrics, $dimensions);
        }

        // If this is a test run, pass data to the test method and return the resulting view
        if(Input::get('test') == 1)
        {
            return;
        }

        foreach($combined as $event)
        {
            $ev = new TrackEvent($event);
            $ev->start_of_week = $start_date;
			$ev->save();
        }
    }
}
