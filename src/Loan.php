<?php

namespace App;

class Loan
{
    protected \DateTime $startDate;
    protected \DateTime $finishDate;

    /**
     * @param \DateTime $startDate
     * @param \DateTime $finishDate
     */
    public function __construct(\DateTime $startDate, \DateTime $finishDate)
    {
        $this->startDate = $startDate;
        $this->finishDate = $finishDate;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @return \DateTime
     */
    public function getFinishDate(): \DateTime
    {
        return $this->finishDate;
    }
}
