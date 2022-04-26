<?php

/**
 * Class Regions in API mode
 */
class Regions extends ApiController
{
    public function __construct()
    {
        $this->regionModel = $this->model('Region');
    }

    /**
     * Default action - getting regions list
     * 
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function index($sort='SortByContinent', $dir='Asc')
    {
        $regions = $this->regionModel->getRegions($sort, $dir);
        $fields = $this->regionModel->getFields();
        $data = [
            'title' => 'Regions of the World',
            'apiUrl' => $this->getApiUrl(),
            'stdUrl' => $this->getStdUrl(),
            'sort' => $sort,
            'dir' => $dir,
            'columns' => [
                [ 
                    'title' => 'Continent',       
                    'field' => 'Continent', 
                    'type' => $fields['Continent']
                ],
                [ 
                    'title' => 'Region',          
                    'field' => 'Region', 
                    'type' => $fields['Region']
                ],
                [ 
                    'title' => 'Countries', 
                    'field' => 'Countries',
                    'type' => $fields['Countries'],
                    'link' => [ 'template' => 'countries', 'filter' => 'Region' ]
                ],
                [ 
                    'title' => 'Life Duration',   
                    'field' =>'LifeDuration',
                    'type' => $fields['LifeDuration']
                ],
                [ 
                    'title' => 'Population',
                    'field' => 'Population',
                    'type' => $fields['Population']
                ],
                [ 
                    'title' => 'Cities',
                    'field' => 'Cities',
                    'type' => $fields['Cities'],
                    'link' => [ 'template' => 'cities/listByRegion', 'filter' => 'Region' ]
                ],
                [ 
                    'title' => 'Languages',
                    'field' => 'Languages',
                    'type' => $fields['Languages'],
                    'link' => [ 'template' => 'languages/listByRegion', 'filter' => 'Region' ]
                ]
            ],
            'enums' => $this->regionModel->getEnums() ? : [],
            'list' => $regions
        ];
        $this->response('list', $data);
    }
}