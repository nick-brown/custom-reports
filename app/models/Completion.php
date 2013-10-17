<?php

class Completion extends Eloquent {
	protected $table = 'ga_completions';

	protected $guarded = ['id', 'updated_at'];
}
