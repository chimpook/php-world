<?php

/**
 * Class City
 * 
 * @property array $fields
 */
class City
{
    private $db;

    protected $fields = [
        'ID'           => 'number',
        'CountryName'  => 'string',
        'Name'         => 'string',
        'CountryCode'  => 'string',
        'District'     => 'string',
        'Population'   => 'number'
    ];

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Getting list of cities by the country code
     * 
     * @param string $countryCode
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function getCitiesByCountry($countryCode, $sort='SortByName', $dir='Desc')
    {
        $sortString = str_replace('SortBy', '', $sort);

        if (!array_key_exists($sortString, $this->fields)) {
            $sortString = 'Name';
        }
        if (strtoupper($dir) !== 'DESC') {
            $dir = 'ASC';
        }

        $this->db->query("
        select
            ct.*
            ,c.Name as `CountryName`
            ,c.Region as `Region`
        from City ct
        join Country c on c.Code = ct.CountryCode
        where ct.CountryCode = :countryCode;
        order by $sortString $dir
        ");

        $this->db->bind(':countryCode', $countryCode);
        
        $result = $this->db->resultSet();

        return $result;
    }

    /**
     * Getting list of cities by the region
     * 
     * @param string $region
     * @param string $sort
     * @param string $dir
     * 
     * @return array
     */
    public function getCitiesByRegion($region, $sort='SortByName', $dir='Desc')
    {
        $sortString = str_replace('SortBy', '', $sort);

        if (!array_search($sortString, array_column($this->fields, 'name'))) {
            $sortString = 'Name';
        }
        if (strtoupper($dir) !== 'DESC') {
            $dir = 'ASC';
        }

        $this->db->query("
        select 
        ct.ID
        , ct.Name as `Name`
        , ct.CountryCode as `CountryCode`
        , ct.District as `District`
        , ct.Population as `Population`
        , c.Name as `CountryName`
        , c.Region as `Region`
        from City ct
        join Country c on c.Code=ct.CountryCode
        where replace(c.Region, ' ', '') = :region
        order by $sortString $dir
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