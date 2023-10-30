<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\IntensityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

#[ORM\Entity(repositoryClass: IntensityRepository::class)]
class CarbonIntensity
{
    // Base URL for the API
    private $baseUrl = "https://api.carbonintensity.org.uk/regional";

    /**
     * Fetches data from a given URL and returns the required subset.
     * 
     * @param string $url The URL from which to fetch data.
     * @return array The required subset of the fetched data.
     * @throws Exception If there's an error fetching or decoding the data.
     */
    private function fetchDataFromUrl($url) {

        $response = @file_get_contents($url);

        if ($response === false) {
            throw new Exception("Failed to fetch data from URL: $url");
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Failed to decode JSON data from URL: $url. Error: " . json_last_error_msg());
        }

        return isset($data['data'][0]) ? $data['data'][0] : $data['data'];
    }

    /**
     * Fetches carbon intensity data based on parameters provided.
     * 
     * @param int $regionID The region ID. Defaults to 1.
     * @param string $from Starting datetime.
     * @param string $to Ending datetime.
     * @return array The fetched intensity data.
     */
    public function fetchIntensityData($regionID = 1, $from = '', $to = '')
    {
        $url = $this->baseUrl . "/regionid/{$regionID}";

        if (!empty($from) && !empty($to)) {
            $url = $this->baseUrl . "/intensity/{$from}/{$to}/regionid/{$regionID}";
        }

        return $this->fetchDataFromUrl($url);
    }

    /**
     * Aggregates carbon intensity data by date.
     * 
     * @param array $data The data to aggregate.
     * @return array The aggregated data.
     */
    public function aggregateDatabyDate($data)
    {
        $aggregatedData = [];

        foreach ($data as $entry) {
            $date = explode('T', $entry['from'])[0];

            if (!isset($aggregatedData[$date])) {
                $aggregatedData[$date] = [
                    'intensity' => [],
                    'generationmix' => [],
                ];
            }

            $aggregatedData[$date]['intensity'][] = $entry['intensity'];

            // Aggregate the generation mix by fuel type
            foreach ($entry['generationmix'] as $mix) {
                $fuelType = $mix['fuel'];

                if (!isset($aggregatedData[$date]['generationmix'][$fuelType])) {
                    $aggregatedData[$date]['generationmix'][$fuelType] = [
                        'count' => 0,
                        'totalPerc' => 0,
                    ];
                }

                $aggregatedData[$date]['generationmix'][$fuelType]['count'] += 1;
                $aggregatedData[$date]['generationmix'][$fuelType]['totalPerc'] += $mix['perc'];
            }
        }

        // Calculate the average for each fuel type
        foreach ($aggregatedData as $date => &$entry) {
            foreach ($entry['generationmix'] as $fuelType => &$mixData) {
                $mixData['averagePerc'] = round($mixData['totalPerc'] / $mixData['count'], 3);
            }
        }

        return $aggregatedData;
    }

    /**
     * Filters the provided data by energy type.
     * 
     * @param array $data The data to filter.
     * @param string $energyType The energy type to filter by.
     * @return array The filtered data.
     */
    public function filterByEnergyType($data, $energyType) 
    {
        $filteredData = [];
    
        foreach ($data as $date => $entry) {
            if (isset($entry['generationmix'][$energyType])) {
                $filteredData[$date][$energyType] = $entry['generationmix'][$energyType];
            }
        }
        return $filteredData;
    }

    /**
     * Calculates the average intensity for a given period and energy type.
     * 
     * @param array $data The data to calculate from.
     * @param string $energy The energy type.
     * @return float The average intensity.
     */
    public function avgForPeriod($data, $energy)
    {
        $cumulativeSum = 0;
        $count = 0;

        foreach ($data as $details) {
            if (isset($details[$energy])) {
                $cumulativeSum += $details[$energy]['averagePerc'];
                $count++;
            }
        }

        return ($count != 0) ? round( $cumulativeSum / $count, 3) : 0;
    } 
}
