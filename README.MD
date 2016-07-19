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

File `tmp/rt_cookie.txt`, `tmp/rutor_cookie` and 
`tmp/rutracker_future_list.txt` must be writable

Also `config/config.json` file must have read/write permissions. 

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
    },
  "search-in":{   //set search options to true if you wanna use specified search engine
    "rutracker" : true,
    "rutor" : true
  }
}  
`
