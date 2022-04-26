<?php

/**
 * Class Cities in API mode
 */
class Cities extends ApiController
{
    public function __construct()
    {
        $this->cityModel = $this->model('City');
    }

    /**
     * Default action - getting cities list by the country code
     * 
     * @param string $countryCode
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function index($countryCode='', $sort='SortByName', $dir='Asc')
    {
        $this->listByCountry($countryCode, $sort, $dir);
    }

    /**
     * Getting cities list by the country code
     * 
     * @param string $countryCode
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function listByCountry($countryCode='', $sort='SortByName', $dir='Asc')
    {
        $cities = $this->cityModel->getCitiesByCountry($countryCode, $sort, $dir);
        $fields = $this->cityModel->getFields();
        $data = [
            'title' => 'Cities of the country: ' . $cities[0]->CountryName,
            'back' => ['title' => 'Countries of ' . $cities[0]->Region, 'url' => URLROOT . '/api/countries/' . $cities[0]->Region ],
            'apiUrl' => $this->getApiUrl(),
            'stdUrl' => $this->getStdUrl(),
            'sort' => $sort,
            'dir' => $dir,
            'columns' => [
                [ 
                    'title' => 'Country',
                    'field' => 'CountryName',
                    'type'  => $fields['CountryName']
                ],
                [ 
                    'title' => 'Name',
                    'field' => 'Name',
                    'type'  => $fields['Name']
                ],
                [ 
                    'title' => 'District',   
                    'field' => 'District',
                    'type'  => $fields['District']
                ],
                [ 
                    'title' => 'Population', 
                    'field' => 'Population',
                    'type'  => $fields['Population']
                ],
            ],
            'list' => $cities
        ];
        $this->response('list', $data);
    }

    /**
     * Getting cities list by the region
     * 
     * @param string $region
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function listByRegion($region='', $sort='SortByName', $dir='Asc')
    {
        $cities = $this->cityModel->getCitiesByRegion($region, $sort, $dir);
        $fields = $this->cityModel->getFields();
        $data = [
            'title' => 'Cities of the region: ' . $cities[0]->Region,
            'back' => ['title' => 'Regions of the World', 'url' => URLROOT . '/api/'],
            'apiUrl' => $this->getApiUrl(),
            'stdUrl' => $this->getStdUrl(),
            'sort' => $sort,
            'dir' => $dir,
            'columns' => [
                [ 
                    'title' => 'Country',
                    'field' => 'CountryName',
                    'type'  => $fields['CountryName']
                ],
                [ 
                    'title' => 'Name',
                    'field' => 'Name',
                    'type'  => $fields['Name']
                ],
                [ 
                    'title' => 'District',   
                    'field' => 'District',
                    'type'  => $fields['District']
                ],
                [ 
                    'title' => 'Population', 
                    'field' => 'Population',
                    'type'  => $fields['Population']
                ],
            ],
            'list' => $cities
        ];
        $this->response('list', $data);
    }
}