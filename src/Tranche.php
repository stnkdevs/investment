<?php

namespace App;

use App\Exceptions\DateUnavailableException;
use App\Exceptions\InvestmentLimitExhaustedException;

class Tranche
{
    protected float $availableInvestmentValue;
    protected float $percentage;
    protected Loan $loan;

    /**
     * @param Loan $loan
     * @param $maxAmount
     * @param $percentage
     */
    public function __construct(Loan $loan, $maxAmount, $percentage)
    {
        $this->loan = $loan;
        $this->availableInvestmentValue = $maxAmount;
        $this->percentage = $percentage;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->loan->getStartDate();
    }

    /**
     * @return \DateTime
     */
    public function getFinishDate(): \DateTime
    {
        return $this->loan->getFinishDate();
    }

    /**
     * @param Investor $investor
     * @param float $amount
     * @param $investmentDate
     * @throws DateUnavailableException
     * @throws Exceptions\InsufficientFundsException
     * @throws InvestmentLimitExhaustedException
     */
    public function acceptInvestment(Investor $investor, float $amount, $investmentDate)
    {
        if ($this->availableInvestmentValue < $amount) {
            throw new InvestmentLimitExhaustedException();
        }

        if ($this->getStartDate() > $investmentDate || $this->getFinishDate() < $investmentDate) {
            throw new DateUnavailableException();
        }

        $investor->withdraw($amount);
        $this->availableInvestmentValue -= $amount;
        $investor->addInvestment(new LoanInvestment($this, $amount, $investmentDate));
    }

    /**
     * @return float
     */
    public function getPercentage(): float
    {
        return $this->percentage;
    }
}
