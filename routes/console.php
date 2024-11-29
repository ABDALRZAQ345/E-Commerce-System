<?php

Schedule::call(function () {
    dispatch(new \App\Jobs\DeleteExpiredCodes);
})->everyTenMinutes();
