<?php

namespace App;

use App\Exceptions\InsufficientFundsException;

class Investor
{
    /** @var InvestmentInterface[] */
    protected array $investments;

    protected $balance = 0;

    /**
     * @param int $balance
     */
    public function __construct($balance = 0)
    {
        return $this->balance = $balance;
    }

    /**
     * @param InvestmentInterface $investment
     */
    public function addInvestment(InvestmentInterface $investment)
    {
        $this->investments[] = $investment;
    }

    /**
     * @param \DateTime $date
     * @return float
     */
    public function getInterest(\DateTime $date): float
    {
        $total = 0;

        foreach ($this->investments as $investment) {
            $total += $investment->getInterest($date);
        }

        return $total;
    }

    /**
     * @param $value
     * @return bool
     */
    public function fundsIsAvailable($value): bool
    {
        return $this->balance >= $value;
    }

    /**
     * @throws InsufficientFundsException
     */
    public function withdraw($value)
    {
        if (!$this->fundsIsAvailable($value)) {
            throw new InsufficientFundsException();
        }

        $this->balance = $this->balance - $value;
    }
}
