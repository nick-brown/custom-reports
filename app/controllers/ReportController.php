<?php

class ReportController extends BaseController {

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

        $start_date = new DateTime(max($oldest_record_dates), new DateTimeZone('America/Phoenix'));
        $end_date = new DateTime('last Sunday', new DateTimeZone('America/Phoenix'));

        $data['sundays'] = Helpers::get_sundays_between($start_date, $end_date);

        $data['analytics']['completions'] = $completions->toArray();
        $data['analytics']['events'] = $events->toArray();

	    return View::make('report.index', $data);
	}
}
