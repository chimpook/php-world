<?php

/**
 * Class Country
 * 
 * @property array $fields
 */
class Country
{
    private $db;

    protected $fields = [
        'Id'               => 'integer',
        'Region'           => 'string',
        'Code'             => 'string',
        'Name'             => 'string',
        'Continent'        => 'string',
        'Region'           => 'string',
        'SurfaceArea'      => 'number',
        'IndepYear'        => 'number',
        'Population'       => 'number',
        'LifeExpectancy'   => 'number',
        'GNP'              => 'number',
        'GNPOld'           => 'number',
        'LocalName'        => 'string',
        'GovernmentForm'   => 'string',
        'HeadOfState'      => 'string',
        'Capital'          => 'string',
        'Code2'            => 'string',
        'Cities'           => 'number',
        'Languages'        => 'number'
    ];

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Getting countries by the region
     * 
     * @param string $region
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function getCountries($region, $sort='SortByCode', $dir='Desc')
    {
        $sortString = str_replace('SortBy', '', $sort);

        if (!array_key_exists($sortString, $this->fields)) {
            $sortString = 'Code';
        }
        if (strtoupper($dir) !== 'DESC') {
            $dir = 'ASC';
        }

        $this->db->query("
        select
          c.*
          ,count(distinct ct.Name) as `Cities`
          ,count(distinct cl.Language) as `Languages`
        from Country c
            left join CountryLanguage as cl on cl.CountryCode=c.Code
            left join City as ct on ct.CountryCode=c.Code
        where replace(c.Region, ' ', '') = :region
        group by c.Code, c.Id
        order by $sortString $dir
        ;
        ");

        $this->db->bind(':region', $region);
        
        $result = $this->db->resultSet();

        return $result;
    }

    /**
     * Fields getter
     * 
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

}