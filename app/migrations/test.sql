create database world;

select * from Country;

select
	 c.Continent
    ,c.Region
    ,count(DISTINCT c.Code) as 'Countries'
    ,round(avg(distinct c.LifeExpectancy),2) AS 'LifeDuration'
    ,sum(case when c.Code = 'AUS' then c.Population else 0 end) -- Пока не получается..
--    ,count(distinct ct.Name) as 'Cities'
--    ,count(distinct cl.Language) as 'Languages'
from Country c
-- left join CountryLanguage as cl on cl.CountryCode=c.Code
-- left join City as ct on ct.CountryCode=c.Code
GROUP BY c.Continent, c.Region
 ORDER BY c.Continent,c.Region
;

-- 1 part

select
	 c.Continent
    ,c.Region
    ,count(c.Code) as 'Countries'
    ,round(avg(c.LifeExpectancy),2) AS 'LifeDuration'
    ,sum(c.Population) -- Пока не получается..
from Country c
GROUP BY c.Continent, c.Region
 ORDER BY c.Continent,c.Region
;

-- 2 часть
select
	 c.Continent
    ,c.Region
    ,count(distinct ct.Name) as 'Cities'
    ,count(distinct cl.Language) as 'Languages'
from Country c
left join CountryLanguage as cl on cl.CountryCode=c.Code
left join City as ct on ct.CountryCode=c.Code
GROUP BY c.Continent, c.Region
 ORDER BY c.Continent,c.Region
;

SELECT COUNT(distinct cl.`Language`), GROUP_CONCAT(distinct cl.`Language`)  FROM CountryLanguage cl
JOIN Country AS c ON c.Code=cl.CountryCode
WHERE c.Region='Baltic Countries';

SELECT COUNT(distinct cl.`Language`), GROUP_CONCAT(distinct cl.`Language`)  FROM CountryLanguage cl
JOIN Country AS c ON c.Code=cl.CountryCode
WHERE c.Region='British Islands';

SELECT Language  FROM CountryLanguage cl
JOIN Country AS c ON c.Code=cl.CountryCode
WHERE c.Region='Caribbean'; -- с distinct - 10, без - 46, как в примере

-- Вывод - в примере ошибка выборки языков

-- Итоговый запрос
select p1.Continent, p1.Region, p1.Countries, p1.LifeDuration, p1.Population, p2.Cities, p2.Languages from (
  select
	 c.Continent
    ,c.Region
    ,count(c.Code) as 'Countries'
    ,round(avg(c.LifeExpectancy),2) AS 'LifeDuration'
    ,sum(c.Population) as 'Population'
from Country c
GROUP BY c.Continent, c.Region
) as p1 join (
select
    c.Region
    ,count(distinct ct.Name) as 'Cities'
    ,count(distinct cl.Language) as 'Languages'
from Country c
left join CountryLanguage as cl on cl.CountryCode=c.Code
left join City as ct on ct.CountryCode=c.Code
GROUP BY c.Continent, c.Region
) as p2 on (p1.Region = p2.Region)
order by p1.Continent,p1.Region;