<?php

namespace App;

interface InvestmentInterface
{
    public function getInterest(\DateTime $date): float;
}
