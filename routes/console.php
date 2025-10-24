<?php

use Illuminate\Support\Facades\Schedule;


Schedule::command('app:check-offline-sensors')->daily();
