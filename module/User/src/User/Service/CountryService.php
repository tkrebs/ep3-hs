<?php

namespace User\Service;

use Base\Service\AbstractService;

class CountryService extends AbstractService
{

    protected $countries = array();

    public function __construct()
    {
        $countryList = getcwd() . '/data/res/countries.csv';

        if (is_readable($countryList)) {
            $countryListContent = file_get_contents($countryList);
            $countryListRecords = explode("\r\n", $countryListContent);

            foreach ($countryListRecords as $countryListRecord) {
                $countryListData = explode(';', $countryListRecord);

                if (! (isset($countryListData[0]) && is_numeric($countryListData[0]))) {
                    continue;
                }

                $iso = $countryListData[4];

                $this->countries[$iso] = $countryListData;
            }
        }
    }

    public function getName($iso, $default = null)
    {
        if (isset($this->countries[$iso])) {
            return $this->countries[$iso];
        } else {
            return $default;
        }
    }

    public function getNames()
    {
        $names = array();

        foreach ($this->countries as $iso => $country) {
            $names[$iso] = $country[2];
        }

        asort($names);

        return $names;
    }

}