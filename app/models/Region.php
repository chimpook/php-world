<?php

/**
 * Class Region
 * 
 * @property array $fields
 * @property array $enums 
 */
class Region
{
    private $db;

    protected $fields = [
        'Id'            => 'integer',
        'Continent'     => 'enum',
        'Region'        => 'string',
        'Countries'     => 'number',
        'LifeDuration'  => 'number',
        'Population'    => 'number',
        'Cities'        => 'number',
        'Languages'     => 'number',
    ];

    protected $enums = [
        'Continent' => ['Asia', 'Europe', 'North America', 'Africa', 'Oceania', 'Antarctica', 'South America']
    ];

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Getting regions
     * 
     * @param string $sort
     * @param string $dir
     * 
     * @return array|false
     */
    public function getRegions($sort='SortByContinent', $dir='Asc')
    {
        $sortString = str_replace('SortBy', '', $sort);

        if (!array_key_exists($sortString, $this->fields)) {
            $sortString = 'Continent';
        }
        if (strtoupper($dir) !== 'DESC') {
            $dir = 'Asc';
        }

        $linkedSortString = $sortString === 'Continent' ? "Continent $dir, Region $dir" 
            : ($sortString === 'Region' ? "Region $dir, Continent $dir" : $sortString . ' ' . $dir);

        $this->db->query("
            select 
                p1.Continent as `Continent`
                , p1.Region as `Region`
                , p1.Countries as `Countries`
                , p1.LifeDuration as `LifeDuration`
                , p1.Population as `Population`
                , p2.Cities as `Cities`
                , p2.Languages as `Languages`
            from (
                select
                c.Continent as `Continent`
                ,c.Region as `Region`
                ,count(c.Code) as `Countries`
                ,round(avg(c.LifeExpectancy),2) AS `LifeDuration`
                ,sum(c.Population) as `Population`
                from Country c
                GROUP BY c.Continent, c.Region
            ) as p1 
            join (
                select
                    c.Region
                    ,count(distinct ct.Name) as `Cities`
                    ,count(distinct cl.Language) as `Languages`
                from Country c
                left join CountryLanguage as cl on cl.CountryCode=c.Code
                left join City as ct on ct.CountryCode=c.Code
                GROUP BY c.Continent, c.Region
            ) as p2 on (p1.Region = p2.Region)
            order by $linkedSortString
        ");
        
        $result = $this->db->resultSet();

        return $result;
    }

    /**
     * Fields getter
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Enums getter
     * 
     * @return array
     */
    public function getEnums()
    {
        return $this->enums;
    }
}