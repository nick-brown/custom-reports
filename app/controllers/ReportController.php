<?php

class ReportController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $data['completions'] = Completion::all();
        $data['events'] = TrackEvent::all();

        $start_date = new DateTime('2013-09-29', new DateTimeZone('America/Phoenix'));
        $end_date = new DateTime('last Sunday', new DateTimeZone('America/Phoenix'));

        $data['sundays'] = Helpers::get_sundays_between($start_date, $end_date);

        //var_dump($data['sundays']); die();

	    return View::make('report.index', $data);	
	}

}
