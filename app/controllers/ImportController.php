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
        define('ga_email', 'universallasers@gmail.com');
        define('ga_password', 'notanotherteenmovie');
        define('ga_profile_id', '10496354');

        $this->getCompletions($start_date, $end_date);
        $this->getCompletions($start_date, $end_date);

        $this->getEvents($start_date, $end_date);
        
        return 'Imported successfully';
    }


    // Private

    private function getCompletions($start_date, $end_date)
    {
        // Create a new Request object and retrieve our first 7 dimensions
        $completions = new GoogleRequest(ga_email, ga_password);

        $completions->requestReportData(
            ga_profile_id,
            array('customVarValue1', 'customVarValue2', 'hostname', 'city', 'region', 'country'),
            array('pageviews', 'uniquePageviews', 'goal16Completions', 'goal14Completions', 'goalCompletionsAll'),
            array('customVarValue1'),
            'pagePath=~material-profile',
            $start_date,
            $end_date
        );

        var_dump($completions->getResults());

        $results = $completions->getResults();

        // If we're still running at this point, instatiate and save our events
        foreach($results as $entry)
        {
            $metrics = $entry->getMetrics();
            $dimensions = $entry->getDimensions();

            $combined[] = array_merge($metrics, $dimensions);
        }

        foreach($combined as $completion)
        {
            $comp = new Completion($completion);
            $comp->start_of_week = $start_date;
            $comp->save();
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

        foreach($combined as $event)
        {
            $ev = new TrackEvent($event);
            $ev->start_of_week = $start_date;
			$ev->save();
        }
    }
}
