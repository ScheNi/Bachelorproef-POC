<?php

class CounterFactory implements iCounterFactory
{
    public function make()
    {
        $counterGroup = new CounterGroup();
        $counterGroup->average_c = new AverageCounter($counterGroup);
        $counterGroup->histogram_c = new HistogramCounter($counterGroup);
        $counterGroup->dual_c = new TotalDualCounter($counterGroup);
        $counterGroup->dual_c_succ = new SucceedDualCounter($counterGroup);
        $counterGroup->dual_c_fail = new FailDualCounter($counterGroup);
        return $counterGroup;
    }
}
