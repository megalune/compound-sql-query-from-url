# Compound SQL query from URL Parameters
 
**See it in action**: https://www.iecmhc.org/evidence-mapping/

## Project

Users can perform an advanced search on a database of peer-reviewed literature. The search form consists of three multiple-select checklist groups and a radio group. The search needs to look for records that meet any of the items in each individual checklist group but all of the fields.

Staff maintains the database through FileMaker. Due to the limitations of the API, the database is cloned to mySQL where public searches are performed.

## Files

**page-evidence-mapping.php** => initial public search page

**page-evidence-mapping-results.php** => results page, performs a compound and/or/like search

**database-sync.php** => admin tool; purges the mysql table and replaces all data with new data entered by staff in FileMaker

*this code snippet is intended as a portfolio piece, not as a complete working package*