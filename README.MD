README
===

p2p torrent tracker searcher
----

Search torrents from multiple trackers.
Separate API for each tracker and web page to combine results
Runs on your local server with php module

Search in:

* rutracker.org (already done)
* rutor (already done)
* ex.ua (planned)
* piratebay (planned)


##Preparation

File `/tmp/rt_cookie.txt` and `/tmp/rutor_cookie`must be writable

Run before first launch:

* `bower install jquery`
* `bower install git https://github.com/christianbach/tablesorter`
* `bower install bootstrap`  

Fill config.json file with your rutracker.org params
`
{  
  "rutracker": {
    "username" : "your_username",  
    "password" : "password",  
    } 
}  
`
