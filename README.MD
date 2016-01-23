README
===

p2p torrent tracker searcher
----

Search torrents from multiple trackers.
Runs on your local php server

Search in:

* rutracker.org


##Preparation

File `/tmp/rt_cookie.txt` must be writable

Run before first launch:

* `bower install jquery`
* `bower install git https://github.com/christianbach/tablesorter`
* `bower install bootstrap`  

fill config.json file with your rutracker.org params
`
{  
  "rutracker": {
    "username" : "your_username",  
    "password" : "password",  
    } 
}  
`
