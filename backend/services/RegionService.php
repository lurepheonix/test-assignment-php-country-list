<?php

namespace Services;

use Models\CityModel;
use Models\CountryLanguageModel;
use Models\CountryModel;

class RegionService
{
    protected $cities;
    protected $countries;
    protected $countryLanguages;

    protected function populateCountryData($countries, $cities, $languages): array
    {
        $populatedCountries = [];
        foreach ($countries as $key => $country) {
            $languageCount = 0;
            foreach ($languages as $language) {
                if ($country['Code'] === $language['CountryCode']) {
                    $languageCount = ++$languageCount;
                }
            }

            $cityCount = 0;
            foreach ($cities as $city) {
                if ($country['Code'] === $city['CountryCode']) {
                    $cityCount = ++$cityCount;
                }
            }

            $populatedCountries[$key] = $country;

            $populatedCountries[$key]['cityCount'] = $cityCount;
            $populatedCountries[$key]['languageCount'] = $languageCount;
        }
        return $populatedCountries;
    }

    protected function getRegionCountries($countries): array
    {
        $regionCountries = [];
        foreach ($countries as $country) {
            $regionCountries[$country['Region']][] = $country;
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
            $totalLifeExpectancy += $country['LifeExpectancy'];
            $totalPopulation += $country['Population'];
            $cityCount += $country['cityCount'];
            $languageCount += $country['languageCount'];
        }

        return [
            'continentName' => $regionCountries[0]['Continent'],
            'regionName' => $regionCountries[0]['Region'],
            'countryCount' => count($regionCountries),
            'avgLifeExpectancy' => round($totalLifeExpectancy / count($regionCountries), 2),
            'totalPopulation' => $totalPopulation,
            'cityCount' => $cityCount,
            'languageCount' => $languageCount
        ];
    }

    function getArray()
    {
        $cities = (new CityModel)->findAll();
        $countries = (new CountryModel)->findAll();
        $countryLanguages = (new CountryLanguageModel)->findAll();

        $populatedPountries = $this->populateCountryData($countries, $cities, $countryLanguages);
        $regionCountries = $this->getRegionCountries($populatedPountries);


        foreach ($regionCountries as $regionCountryData) {
            $regions[] = $this->getRegionData($regionCountryData);
        }
        return $regions;
    }
}
