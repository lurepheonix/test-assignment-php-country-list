<?php

namespace Services;

use Models\CityModel;
use Models\CountryLanguageModel;
use Models\CountryModel;
use Utils\CaseChanger;

class RegionService
{

    protected function populateCountryData($countries, $cities, $languages): array
    {
        $populatedCountries = [];
        foreach ($countries as $country) {
            $languageCount = 0;
            foreach ($languages as $language) {
                if ($country['code'] === $language['countryCode']) {
                    $languageCount = ++$languageCount;
                }
            }

            $cityCount = 0;
            foreach ($cities as $city) {
                if ($country['code'] === $city['countryCode']) {
                    $cityCount = ++$cityCount;
                }
            }

            $populatedCountries[$country['code']] = $country;

            $populatedCountries[$country['code']]['cityCount'] = $cityCount;
            $populatedCountries[$country['code']]['languageCount'] = $languageCount;
        }
        return $populatedCountries;
    }

    protected function getRegionCountries($countries): array
    {
        $regionCountries = [];
        foreach ($countries as $country) {
            $regionCountries[$country['region']][] = $country;
        }
        return $regionCountries;
    }

    protected function getRegionData($regionCountries): array
    {
        $totalLifeExpectancy = 0;
        $totalPopulation = 0;
        $cityCount = 0;
        $languageCount = 0;

        foreach ($regionCountries as $country) {
            $totalLifeExpectancy += $country['lifeExpectancy'];
            $totalPopulation += $country['population'];
            $cityCount += $country['cityCount'];
            $languageCount += $country['languageCount'];
        }

        return [
            'continentName' => $regionCountries[0]['continent'],
            'regionName' => $regionCountries[0]['region'],
            'countryCount' => count($regionCountries),
            'avgLifeExpectancy' => round($totalLifeExpectancy / count($regionCountries), 2),
            'totalPopulation' => $totalPopulation,
            'cityCount' => $cityCount,
            'languageCount' => $languageCount
        ];
    }

    protected function applyFilters(array $regions, array $filters)
    {
        $tmp = $regions;

        if (isset($filters['orderBy'])) {
            $field = CaseChanger::snakeToCamel($filters['orderBy']['field']);
            $direction = $filters['orderBy']['order'];
            usort($tmp, function ($a, $b) use ($field, $direction) {
                if ($direction === 'asc') {
                    if (gettype($a[$field]) === 'string') {
                        return strcmp($a[$field], $b[$field]);
                    } else if (in_array(gettype($a[$field]), ['integer', 'float'])) {
                        return $a[$field] > $b[$field];
                    }
                } else if ($direction === 'desc') {
                    if (gettype($a[$field]) === 'string') {
                        return strcmp($b[$field], $a[$field]);
                    } else if (in_array(gettype($a[$field]), ['integer', 'float'])) {
                        return $a[$field] < $b[$field];
                    }
                }
            });
        }

        if (isset($filters['limit'])) {
            $tmp = array_slice($tmp, $filters['offset'], $filters['limit']);
        }

        return $tmp;
    }

    public function getArray($filters = []): array
    {
        /**
         * Notice
         * 
         * Currently, I've implemented logic like this:
         * 1. Pull all data required (minimal).
         * 2. Construct the list of _all_ regions.
         * 3. Apply offset, limit and sort to that list.
         * 
         * This approach has a major drawback: we need to pull data
         * on all regions from the table and then construct arrays from it.
         * For now, it's OK. But provided we have a much longer list
         * of regions, that'll be a performace bottleneck.
         * 
         * We can do something like:
         * `SELECT DISTINCT c.Continent 
         * FROM country_list.Country
         * AS c LIMIT X OFFSET Y;`
         * 
         * Or use deferred joins to boost performance further.
         * However, we'd better restructure the database if we need that;
         * e.g. move regions to the dedicated `Regions` table.
         */

        // Apply some filters, retrieve only the data we actually need
        $cities = (new CityModel)->findAll([
            'select' => [
                'CountryCode as countryCode'
            ],
        ]);
        $countries = (new CountryModel)->findAll([
            'select' => [
                'Code as code',
                'Continent as continent',
                'Region as region',
                'Population as population',
                'LifeExpectancy as lifeExpectancy'
            ]
        ]);
        $countryLanguages = (new CountryLanguageModel)->findAll([
            'select' => [
                'CountryCode as countryCode'
            ]
        ]);

        $populatedPountries = $this->populateCountryData($countries, $cities, $countryLanguages);
        $regionCountries = $this->getRegionCountries($populatedPountries);

        foreach ($regionCountries as $regionCountryData) {
            $regions[] = $this->getRegionData($regionCountryData);
        }

        if (count($filters) > 0) {
            $regions = $this->applyFilters($regions, $filters);
        }

        return $regions;
    }
}
