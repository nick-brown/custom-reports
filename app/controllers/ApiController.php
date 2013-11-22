<?php

class ApiController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function data()
	{
        $completions = Completion::orderBy('start_of_week')->get();
        $events = TrackEvent::orderBy('start_of_week')->get();

        // Get the oldest of each record type
        $oldest_record_dates = [
            $completions->first()->start_of_week,
            $events->first()->start_of_week
        ];

        $start_date = new DateTime(max($oldest_record_dates), new DateTimeZone('America/Phoenix'));
        $end_date = new DateTime('last Sunday', new DateTimeZone('America/Phoenix'));

        $data['sundays'] = Helpers::get_sundays_between($start_date, $end_date);

        // Get unique list of channel partners
        $data['partners'] = array_unique(array_merge($completions->lists('customVarValue1'), $events->lists('customVarValue1')));
        sort($data['partners']);

        // Get unique list of materials
        $data['materials'] = array_unique($completions->lists('customVarValue2'));
        sort($data['materials']);

        $data['analytics']['completions'] = $completions->toArray();
        $data['analytics']['events'] = $events->toArray();

	    return Response::json($data);
	}

    public function dates()
    {
        /**
         * Get the oldest of each record type
         * Could be refactored to a join statement
         */
        $oldest_record_dates[] = Completion::min('start_of_week');
        $oldest_record_dates[] = TrackEvent::min('start_of_week');

        $start_date = new DateTime(min($oldest_record_dates), new DateTimeZone('America/Phoenix'));
        $end_date = new DateTime('last Sunday', new DateTimeZone('America/Phoenix'));

        $sundays = Helpers::get_sundays_between($start_date, $end_date);

        return Response::json($sundays);
    }
}
