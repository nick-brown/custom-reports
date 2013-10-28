<?php

class TrackEvent extends Eloquent {
	protected $table = 'ga_events';

	protected $guarded = ['id', 'updated_at'];
}
