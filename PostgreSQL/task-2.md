**1. Identify potential duplicates**

Find entries with identical or very similar names (case-insensitive) + show occurence count: 
- SELECT company.name, COUNT(*)  FROM companies company JOIN normalized_companies normalized ON LOWER(company.name) = LOWER(normalized.name) GROUP BY company.name HAVING COUNT(*) > 1;

**Explanation:** Selecting company FROM companies and JOIN normalized. And displaying the occurrence from the name.

List the sources linked to the company
- SELECT name, STRING_AGG(DISTINCT source, ', ') AS sources_linked FROM companies GROUP BY name;


**Note:** STRING_AGG is a used aggregate function that combines a list of strings into one, with a specified separator between them. Found source code on the internet via: Stackoverflow and read more about it on POSTGRESQL website.

-------------------------------------------------------------------------------------------------------------

**2. Normalize the data**

- INSERT INTO normalized_companies (name, canonical_website)
SELECT
    -- CASE expression goes through conditions and returns a value when the first condition is met (like an if-then-else statement).
    -- (CASE for name)
    CASE 
        WHEN company.source = 'MANUAL' THEN company.name
        WHEN company.source = 'API_1' AND NOT EXISTS (SELECT 1 FROM companies WHERE name = company.name AND source = 'MANUAL') THEN company.name
        WHEN company.source = 'SCRAPER_2' AND NOT EXISTS (SELECT 1 FROM companies WHERE name = company.name AND source IN ('MANUAL', 'API_1')) THEN company.name
    END AS name,

    -- (CASE for website)
    CASE 
        WHEN company.source = 'MANUAL' THEN company.website
        WHEN company.source = 'API_1' AND NOT EXISTS (SELECT 1 FROM companies WHERE name = company.name AND source = 'MANUAL') THEN company.website
        WHEN company.source = 'SCRAPER_2' AND NOT EXISTS (SELECT 1 FROM companies WHERE name = company.name AND source IN ('MANUAL', 'API_1')) THEN company.website
    END AS canonical_website
FROM companies company
WHERE company.source IN ('MANUAL', 'API_1', 'SCRAPER_2')
-- Ensuring only unique companies are inserted
AND NOT EXISTS (
    SELECT 1 FROM normalized_companies normalized WHERE normalized.name = company.name
);

**Explanation:** Using CASE to pick the most reliable source for name and then website FROM companies column. The NOT EXISTS part ensures that if 'MANUAL' already provides data for the company. We won’t override it with 'API_1' or 'SCRAPER_2'.

**Note:** During development, I referred to resources such as Stack Overflow, W3Schools and PostgreSQL documentation to figure out how to correctly implement the functionality. It wasn’t about making the code “perfect” but about getting it to work as expected. This was a valuable learning experience and I’m still improving my skills.

-------------------------------------------------------------------------------------------------

**3. Get statistics on sources**

- SELECT source, COUNT(*)  FROM companies GROUP BY source HAVING COUNT(*) > 1 ORDER BY source DESC

**Optional:** I could add 'WHERE source IN ('MANUAL', 'API_1', 'SCRAPER_2')' to the query, to filter the sources. But to write light-weight code I decided to not add it.

**Explanation:** At the first task (1.) I made a similar code, I only changed the row names such as 'name' to 'source' and added after (COUNT(*)> 1) 'ORDER BY source DESC' to get the exact results.

**Note:** I recall using DESC and ASC frequently during school and I still find myself using them in my projects today. It's great to see those concepts coming back into play!
