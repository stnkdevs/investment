<?php

namespace App;

use Carbon\Carbon;

class LoanInvestment implements InvestmentInterface
{
    protected Tranche $tranche;
    protected float $amount;
    protected \DateTime $startDate;

    /**
     * @param Tranche $tranche
     * @param float $amount
     * @param \DateTime $startDate
     */
    public function __construct(Tranche $tranche, float $amount, \DateTime $startDate)
    {
        $this->tranche = $tranche;
        $this->amount = $amount;
        $this->startDate = $startDate;
    }

    /**
     * Calculate interest for previous month
     * @param \DateTime $date
     * @return float
     */
    public function getInterest(\DateTime $date): float
    {
        $end = Carbon::instance($date)->startOfMonth();
        $startOfMonth = Carbon::create($end)->subDay()->startOfMonth();
        $start = Carbon::create($this->startDate)->startOfDay();
        $days = $end->diffInDays($start > $startOfMonth ? $start : $startOfMonth);

        $interest = $this->amount * $this->tranche->getPercentage() * 0.01;
        return round($interest * $days / $start->daysInMonth, 2);
    }
}
