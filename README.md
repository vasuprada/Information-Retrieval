# Information-Retrieval
Emulating a search engine for University

Steps Followed

1) Crawled websites of the University using Java Implementation of Crawler4j
  - Setup 7 parallel crawlers and restricted the crawling/downloading to include only html/pdf/doc files
2) Calculated Statistics such as 
Fetch Statistics
================
# fetches attempted:
# fetches succeeded:
# fetches aborted:
# fetches failed:
Outgoing URLs:
==============
Total URLs extracted:
# unique URLs extracted:
# unique URLs within School:
# unique USC URLs outside School:
# unique URLs outside USC:
Status Codes:
=============
200 OK:
301 Moved Permanently:
401 Unauthorized:
403 Forbidden:
404 Not Found:
File Sizes:
===========
< 1KB:
1KB ~ <10KB:
10KB ~ <100KB:
100KB ~ <1MB:
>= 1MB:
Content Types:
==============
text/html:
image/gif:
image/jpeg:
image/png:
application/pdf:

3) Installed and Setup Solr and indexed all the downloaded pages
4) Used Solr-PHP client as an interface to access Solr and created a UI to view the results when a query is searched
5) Ranked the indexed pages based on Solr's DEFAULT INDEXING and PAGERANK
5) Implemented a Auto-Suggest and SpellCorrect feature ad integrated it with the PHP Client

