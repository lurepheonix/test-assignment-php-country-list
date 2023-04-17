<?php

namespace Services;

use Models\CityModel;
use Models\CountryLanguageModel;
use Models\CountryModel;

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

    function getArray(): array
    {
        // Apply some filters, retrieve only the data we actually need
        $cities = (new CityModel)->findAll([
            'select' => [
                'CountryCode as countryCode'
            ]
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
        return $regions;
    }
}
