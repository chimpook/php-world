<?php

/**
 * Class Countries in API mode
 */
class Countries extends ApiController
{
    public function __construct()
    {
        $this->countryModel = $this->model('Country');
    }

    /**
     * Default action - getting countries list by the region
     * 
     * @param string $region
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function index($region='', $sort='SortByCode', $dir='Asc')
    {
        $countries = $this->countryModel->getCountries($region, $sort, $dir);
        $fields = $this->countryModel->getFields();
        $data = [
            'title' => 'Countries of the region: ' . $countries[0]->Region,
            'back' => ['title' => 'Regions of the World', 'url' => URLROOT . '/api/'],
            'apiUrl' => $this->getApiUrl(),
            'stdUrl' => $this->getStdUrl(),
            'sort' => $sort,
            'dir' => $dir,
            'columns' => [
                [ 
                    'title' => 'Code',
                    'field' => 'Code',
                    'type'  => $fields['Code']
                ],
                [ 
                    'title' => 'Name',
                    'field' => 'Name',
                    'type'  => $fields['Name']
                ],
                [ 
                    'title' => 'Life Expectancy', 
                    'field' => 'LifeExpectancy',
                    'type'  => $fields['LifeExpectancy']
                ],
                [ 
                    'title' => 'Population',
                    'field' => 'Population',
                    'type'  => $fields['Population']
                ],
                [ 
                    'title' => 'Cities',
                    'field' => 'Cities',
                    'type'  => $fields['Cities'],
                    'link' => [ 'template' => 'cities/listByCountry', 'filter' => 'Code' ]
                ],
                [ 
                    'title' => 'Languages',
                    'field' => 'Languages',
                    'type'  => $fields['Languages'],
                    'link' => [ 'template' => 'languages/listByCountry', 'filter' => 'Code' ]
                ],
            ],
            'list' => $countries
        ];
        $this->response('list', $data);
    }
}