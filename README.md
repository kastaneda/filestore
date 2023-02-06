```sql

SELECT
  md5,
  COUNT(DISTINCT id) AS duplicates,
  GROUP_CONCAT(DISTINCT filename SEPARATOR ', ') AS names
FROM filenames
WHERE placeId = 1 
GROUP BY md5
HAVING duplicates > 1
ORDER BY duplicates DESC;

```
