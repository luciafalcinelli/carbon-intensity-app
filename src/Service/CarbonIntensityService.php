<?php
namespace App\Service;

class CarbonIntensityService {

    private $regions = [
        1 => 'North Scotland',
        2 => 'South Scotland',
        3 => 'North West England',
        4 => 'North East England',
        5 => 'Yorkshire',
        6 => 'North Wales',
        7 => 'South Wales',
        8 => 'West Midlands',
        9 => 'East Midlands',
        10 => 'East England',
        11 => 'South West England',
        12 => 'South England',
        13 => 'London',
        14 => 'South East England',
        15 => 'England',
        16 => 'Scotland',
        17 => 'Wales'
    ];

    private $energies = [
        'biomass' => 'Biomass',
        'coal' => 'Coal',
        'imports' => 'Imports',
        'gas' => 'Gas',
        'nuclear' => 'Nuclear',
        'other' => 'Other',
        'hydro' => 'hydro',
        'solar' => 'Solar',
        'wind' => 'wind'
    ];


    public function getRegions() {
        return $this->regions;
    }

    public function getEnergies() {
        return $this->energies;
    }

}
