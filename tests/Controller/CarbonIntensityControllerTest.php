<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\CarbonIntensity;

class CarbonIntensityTest extends TestCase
{
    private $data;
    private $carbonIntensity;

    protected function setUp(): void
    {
        $this->data = [
            "2023-10-05" => ["nuclear" => ["count" => 7, "totalPerc" => 68.6, "averagePerc" => 9.8]],
            "2023-10-06" => ["nuclear" => ["count" => 48, "totalPerc" => 278.0, "averagePerc" => 5.792]],
            "2023-10-07" => ["nuclear" => ["count" => 48, "totalPerc" => 336.9, "averagePerc" => 7.019]],
            "2023-10-08" => ["nuclear" => ["count" => 48, "totalPerc" => 339.5, "averagePerc" => 7.073]],
            "2023-10-09" => ["nuclear" => ["count" => 48, "totalPerc" => 102.5, "averagePerc" => 2.135]],
            "2023-10-10" => ["nuclear" => ["count" => 48, "totalPerc" => 200.8, "averagePerc" => 4.183]],
            "2023-10-11" => ["nuclear" => ["count" => 48, "totalPerc" => 91.9, "averagePerc" => 1.915]],
            "2023-10-12" => ["nuclear" => ["count" => 48, "totalPerc" => 107.9, "averagePerc" => 2.248]],
            "2023-10-13" => ["nuclear" => ["count" => 48, "totalPerc" => 347.1, "averagePerc" => 7.231]],
            "2023-10-14" => ["nuclear" => ["count" => 48, "totalPerc" => 541.1, "averagePerc" => 11.273]],
            "2023-10-15" => ["nuclear" => ["count" => 41, "totalPerc" => 227.9, "averagePerc" => 5.559]]
        ];

        $this->carbonIntensity = new CarbonIntensity();
    }

    public function testAvgForPeriod()
    {
        $average = $this->carbonIntensity->avgForPeriod($this->data, 'nuclear');
        
        // Expected average based on the provided data:
        $expectedAverage = 5.839; // Rounded to three decimal places
        $this->assertEquals($expectedAverage, $average, "The average percentage is not as expected.");
    }
}
