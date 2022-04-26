<?php

/**
 * Class Languages in standard mode
 */
class Languages extends StdController
{
    public function __construct()
    {
        $this->languageModel = $this->model('Language');
    }

    /**
     * Default action - getting languages list by the country code
     * 
     * @param string $countryCode
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function index($countryCode='', $sort='SortByLanguage', $dir='Asc')
    {
        $this->listByCountry($countryCode, $sort, $dir);
    }

    /**
     * Getting languages list by the country code
     * 
     * @param string $countryCode
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function listByCountry($countryCode='', $sort='SortByLanguage', $dir='Asc')
    {
        $languages = $this->languageModel->getLanguagesByCountry($countryCode, $sort, $dir);
        $fields = $this->languageModel->getFields();
        $data = [
            'title' => 'Languages of the country: ' . $languages[0]->CountryName,
            'back' => ['title' => 'Countries of ' . $languages[0]->Region, 'url' => 'countries/' . $languages[0]->Region ],
            'apiUrl' => $this->getApiUrl(),
            'sort' => $sort,
            'dir' => $dir,
            'columns' => [
                [ 
                    'title' => 'Country',     
                    'field' => 'CountryName',
                    'type'  => $fields['CountryName']
                ],
                [ 
                    'title' => 'Language',
                    'field' => 'Language',
                    'type'  => $fields['Language']
                ],
                [ 
                    'title' => 'Is Official', 
                    'field' => 'IsOfficial',
                    'type'  => $fields['IsOfficial']
                ],
                [ 
                    'title' => 'Percentage',  
                    'field' => 'Percentage',
                    'type'  => $fields['Percentage']
                ],
            ],
            'list' => $languages
        ];
        $this->view('list', $data);
    }

    /**
     * Getting languages list by the region
     * 
     * @param string $region
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function listByRegion($region='', $sort='SortByLanguage', $dir='Asc')
    {
        $languages = $this->languageModel->getLanguagesByRegion($region, $sort, $dir);
        $fields = $this->languageModel->getFields();
        $data = [
            'title' => 'Languages of the region: ' . $languages[0]->Region,
            'back' => ['title' => 'Regions of the World', 'url' => '/'],
            'apiUrl' => $this->getApiUrl(),
            'sort' => $sort,
            'dir' => $dir,
            'columns' => [
                [ 
                    'title' => 'Country',     
                    'field' => 'CountryName',
                    'type'  => $fields['CountryName']
                ],
                [ 
                    'title' => 'Language',
                    'field' => 'Language',
                    'type'  => $fields['Language']
                ],
                [ 
                    'title' => 'Is Official', 
                    'field' => 'IsOfficial',
                    'type'  => $fields['IsOfficial']
                ],
                [ 
                    'title' => 'Percentage',  
                    'field' => 'Percentage',
                    'type'  => $fields['Percentage']
                ],
            ],
            'list' => $languages
        ];
        $this->view('list', $data);
    }
}