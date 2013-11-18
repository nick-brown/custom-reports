<?php

class ApiController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $data['completions'] = $completions = Completion::orderBy('start_of_week')->get();
        $data['events'] = $events = TrackEvent::orderBy('start_of_week')->get();

        // Get the oldest of each record type
        $oldest_record_dates = [
            $completions->first()->start_of_week,
            $events->first()->start_of_week
        ];

        // Get unique list of channel partners
        $data['channel_partners'] = array_unique(array_merge($completions->lists('customVarValue1'), $events->lists('customVarValue1')));
        sort($data['channel_partners']);

        // Get unique list of materials
        $data['materials'] = array_unique($completions->lists('customVarValue2'));
        sort($data['materials']);



	    return View::make('report.index', $data);
	}

    public function sundays()
    {
        // Get the oldest of each record type
        $oldest_record_dates = [
            Completion::orderBy('start_of_week')->first()->start_of_week,
            TrackEvent::orderBy('start_of_week')->first()->start_of_week
        ];

        $start_date = new DateTime(max($oldest_record_dates), new DateTimeZone('America/Phoenix'));
        $end_date = new DateTime('last Sunday', new DateTimeZone('America/Phoenix'));

        echo Helpers::get_sundays_between($start_date, $end_date);
    }
}
