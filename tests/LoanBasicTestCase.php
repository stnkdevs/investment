<?php

namespace Tests;

use App\Exceptions\InvestmentLimitExhaustedException;
use App\Investor;
use App\Loan;
use App\Tranche;
use PHPUnit\Framework\TestCase;

class LoanBasicTestCase extends TestCase
{
    protected static Loan $loan;
    protected static Tranche $trancheA;
    protected static Tranche $trancheB;

    public static function setUpBeforeClass(): void
    {
        static::$loan = new Loan(
            new \DateTime('2015-10-01'),
            new \DateTime('2015-11-15')
        );
        static::$trancheA = new Tranche(static::$loan, 1000, 3);
        static::$trancheB = new Tranche(static::$loan, 1000, 6);
    }

    /**
     * @return Investor
     * @throws InvestmentLimitExhaustedException
     * @throws \App\Exceptions\DateUnavailableException
     */
    public function testFirstInvestment()
    {
        $investor = new Investor(2000);
        static::$trancheA->acceptInvestment($investor, 1000, new \DateTime('2015-10-03'));
        $this->assertTrue(true);

        return $investor;
    }

    /**
     * @return Investor
     * @throws \App\Exceptions\DateUnavailableException
     * @throws \App\Exceptions\InvestmentLimitExhaustedException
     */
    public function testSecondInvestment()
    {
        $this->expectException(InvestmentLimitExhaustedException::class);

        $investor = new Investor(2000);
        static::$trancheA->acceptInvestment($investor, 1, new \DateTime('2015-10-04'));
    }

    /**
     * @return Investor
     * @throws InvestmentLimitExhaustedException
     * @throws \App\Exceptions\DateUnavailableException
     */
    public function testThirdInvestment()
    {
        $investor = new Investor(2000);
        static::$trancheB->acceptInvestment($investor, 500, new \DateTime('2015-10-10'));
        $this->assertTrue(true);

        return $investor;
    }

    /**
     * @return Investor
     * @throws \App\Exceptions\DateUnavailableException
     * @throws \App\Exceptions\InvestmentLimitExhaustedException
     */
    public function testFourthInvestment()
    {
        $this->expectException(InvestmentLimitExhaustedException::class);

        $investor = new Investor(2000);
        static::$trancheA->acceptInvestment($investor, 1100, new \DateTime('2015-10-25'));
    }

    /**
     * @depends testFirstInvestment
     * @depends testThirdInvestment
     */
    public function testInterestCalculation(Investor $investorA, Investor $investorB)
    {
        $date = new \DateTime('2015-11-01');
        $this->assertEquals(28.06, $investorA->getInterest($date));
        $this->assertEquals(21.29, $investorB->getInterest($date));
    }
}
