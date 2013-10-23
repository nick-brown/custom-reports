<?php

class Event extends Eloquent {
	protected $table = 'ga_events';

	protected $guarded = ['id', 'updated_at'];
}
