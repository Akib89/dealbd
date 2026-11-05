<?php
use Illuminate\Support\Facades\Schedule;
Schedule::command('deals:expire')->hourly();
