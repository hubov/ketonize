<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

trait DietPlanTimeline {
    public function getSubscriptionDaysLimit()
    {
        return 27;
    }

    public function getHistoryLimitDate()
    {
        return Carbon::today()->subDays(14);
    }

    public function getDateFormat()
    {
        return 'Y-m-d';
    }

    public function getToday()
    {
        return Carbon::today()->format($this->getDateFormat());
    }

    public function getLastSubscriptionDay()
    {
        return Carbon::parse($this->getSubscriptionDaysLimit().' days')->format($this->getDateFormat());
    }

    public function getSubscriptionDatesArray()
    {
        $period = CarbonPeriod::create('today', $this->getLastSubscriptionDay());

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format($this->getDateFormat());
        }

        return $dates;
    }
}
