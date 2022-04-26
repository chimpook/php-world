<?php

/**
 * Class Language
 * 
 * @property array $fields
 */
class Language
{
    private $db;

    protected $fields = [
        'Id'           => 'integer',
        'Region'       => 'string',
        'CountryCode'  => 'number',
        'CountryName'  => 'string',
        'Language'     => 'string',
        'IsOfficial'   => 'string',
        'Percentage'   => 'number'
    ];

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Getting languages by the country code
     * 
     * @param string $countryCode
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function getLanguagesByCountry($countryCode, $sort='SortByCountryName', $dir='Desc')
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
            c.Region as `Region`
            , cl.CountryCode as `CountryCode`
            , c.Name as `CountryName`
            , cl.Language as `Language`
            , cl.IsOfficial as `IsOfficial`
            , cl.Percentage as `Percentage`
        from CountryLanguage cl
        join Country c on c.Code = cl.CountryCode
        where cl.CountryCode = :countryCode
        order by $sortString $dir 
        ;
        ");

        $this->db->bind(':countryCode', $countryCode);
        
        $result = $this->db->resultSet();

        return $result;
    }

    /**
     * Getting languages by the region
     * 
     * @param string $region
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function getLanguagesByRegion($region, $sort='SortByCountryName', $dir='Desc')
    {
        $sortString = str_replace('SortBy', '', $sort);

        if (!array_key_exists($sortString, $this->fields)) {
            $sortString = 'Name';
        }
        if (strtoupper($dir) !== 'DESC') {
            $dir = 'ASC';
        }

        $linkedSortString = $sortString === 'CountryName' ? "CountryName $dir, Language $dir" 
            : ($sortString === 'Language' ? "Language $dir, CountryName $dir" : $sortString . ' ' . $dir);

        $this->db->query("
        select 
            c.Region as `Region`
            , cl.CountryCode as `CountryCode`
            , c.Name as `CountryName`
            , cl.Language as `Language`
            , cl.IsOfficial as `IsOfficial`
            , cl.Percentage as `Percentage`
        from CountryLanguage cl
        join Country c on c.Code=cl.CountryCode
        where replace(c.Region, ' ', '') = :region
        order by $linkedSortString
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