var Crawler = require("crawler");
var url = require('url');
var urlencode = require('urlencode');
var MongoClient = require('mongodb').MongoClient
    , assert = require('assert');
var crawlerHost = "https://playoverwatch.com";
var crawlerPath = "/ko-kr/career/pc/kr/";
var crawlerBaseUrl = crawlerHost + crawlerPath;

// Connection URL 
var url = 'mongodb://localhost:27017/ggezkr';
// Use connect method to connect to the Server
var numPlayer = 0;
var mongodb;

MongoClient.connect(url, function (err, db) {
    assert.equal(null, err);
    console.log("Connected correctly to server");
    mongodb = db;
    findPlayers(db, function (players) {
        numPlayer = players.length;
        addCrawlerQueue(players);
    });
    // insertDocuments(db, function() {
    //  db.close();
    // });
});

var findPlayers = function (db, callback) {
    // Get the documents collection
    var collection = db.collection('playerlist');
    // Find some documents
    collection.find({}, {btg: 1}).toArray(function (err, players) {
        callback(players);
    });
};

var insertDocuments = function (db, playersStats, callback) {
    // Get the documents collection
    var collection = db.collection('history');
    // Insert some documents
    collection.insertMany([{'2016-06-01':playersStats}], function (err, result) {
        assert.equal(null, err);

        console.log(result.result.n);
        console.log(result.ops.length);

        callback(result);
    });
};

var playerDatas = {};

var c = new Crawler({
    maxConnections: 10,
    // This will be called for each crawled page
    callback: function (error, res, done) {
        if (error) {
            console.log(error);
        } else {
            var $ = res.$;
            var btg = res.request.path.replace(crawlerPath, "");
            btg = urlencode.decode(btg);

            var allValue = {};
            // $ is Cheerio by default
            //a lean implementation of core jQuery designed specifically for the server
            if (!$('div[data-category-id="0x02E00000FFFFFFFF"]')[0]) {
                console.log('faule');
                done();
                return;
            }

            var children = $('div[data-category-id="0x02E00000FFFFFFFF"]')[0].children;
            allValue.all = {};

            for (idx in children) {

                var value = children[idx];
                var table = $(value).find('table.data-table');
                var stat_title = table.find('span.stat-title').text();
                var trs = table.find('tbody tr');

                console.log(stat_title);

                var stats = {};
                for (idx2 in trs) {
                    if (isNaN(parseInt(idx2))) {

                        break;
                    }

                    var tr = trs[idx2];

                    var tds = $(tr).find('td');
                    var statName = tds[0].children[0].data;
                    var statValue = tds[1].children[0].data;
                    stats[statName] = statValue;
                    // console.log(tds);
                    // console.log( + " = " + tds[1].children[0].data);
                    // process.exit()
                }


                allValue.all[stat_title] = stats;
            }

            playerDatas[btg] = allValue;

        }
        done();

        if (c.queueSize == 0) {
            insertDocuments(mongodb, playerDatas, function (result) {
                console.log("result");
                console.log(result);
            });
        }

        console.log(c.queueSize);
    }
});

var addCrawlerQueue = function (players) {
    players.forEach(function (player) {
        // c.queue('https://playoverwatch.com/ko-kr/career/pc/kr/' + btg);
        console.log('add queue btg = ' + player);
        // c.queue(crawlerBaseUrl + player.btg);
        var url = crawlerBaseUrl + urlencode(player.btg);
        c.queue(url);
    });
};

// var btgs = ["Alche-31920", "Alec-12675", "Alexstrasza-3768", "갓겨미-3562", "갓상련-3425"];

// btgs.forEach(function (btg) {
//     c.queue(crawlerBaseUrl + btg);
// });

// addCrawlerQueue(btgs);

// Queue just one URL, with default callback


