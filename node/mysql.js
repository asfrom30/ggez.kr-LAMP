var Crawler = require("crawler");
var url = require('url');
var urlencode = require('urlencode');
var crawlerHost = "https://playoverwatch.com";
var crawlerPath = "/ko-kr/career/pc/kr/";
var moment = require('moment');

var crawlerBaseUrl = crawlerHost + crawlerPath;

var mysql = require('mysql');
var connection = mysql.createConnection({
    host: 'localhost',
    user: 'ggezkr_root',
    password: ';9#?!ECM5--t',
    database: 'ggezkr_db'
});

var today = moment(new Date()).format('Y_MM_DD');
console.log(today);

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
            console.log(btg);

            // competitive 먼저 수행
            // $('div#competitive');
            if (!$('div#competitive')[0]) {
                done();
                return;
            }

            var playerStats = {};

            $('div#competitive').find('select[data-js=career-select][data-group-id=stats] option').each(function (a) {
                playerStats[getKeyByDataCategoryId($(this).val())] = getHeroStats($, $(this).val());

                // getHeroStats($, $(this).val(), done);
            });



            var icon = $('img.player-portrait').attr('src');
            var cptPt = $('div.competitive-rank > div').first().text();

            if (playerStats['All']) {
                playerStats['All'].etcData = {
                    iconUrl: icon,
                    cptPt: cptPt
                };
            }

            connection.query('INSERT INTO '+today+' SET ? ON DUPLICATE KEY UPDATE btg=VALUES(btg)', {
                btg: btg,
                Datas: JSON.stringify(playerStats)
            }, function (error, results, fields) {
                if (error) throw error;
            });
        }
        done();

        if (c.queueSize == 0) {
            // console.log(playerDatas);

            connection.end();
            // reg_date
            // btg
            // Datas

            // insertDocuments(mongodb, playerDatas, function (result) {
            //     console.log("result");
            //     console.log(result);
            // });
        }

        console.log(c.queueSize);
    }
});
connection.connect();
var createTableSql = "CREATE TABLE " + today +
// 	    " (id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,"	// not using id key
//배틀태그 한글8글자, 영어12글자
    "(btg VARCHAR(30) PRIMARY KEY, " +
    "Datas TEXT, " +
    "reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP" +
    ")";

connection.query(createTableSql, function (error, results, fields) {
    connection.query('SELECT btg from playerlist', function (error, results, fields) {
        if (error) throw error;

        for (idx in results) {
            var url = crawlerBaseUrl + urlencode(results[idx].btg);
            c.queue(url);
        }
    });

});

var getHeroStats = function ($, data_category_id) {
    // $ is Cheerio by default
    //a lean implementation of core jQuery designed specifically for the server
    var category = {};
    var elementId = 'div#competitive div[data-group-id="stats"][data-category-id="' + data_category_id + '"]';
    var children = $(elementId)[0].children;

    for (idx in children) {

        var value = children[idx];
        var table = $(value).find('table.data-table');
        var stat_title = table.find('span.stat-title').text();
        var trs = table.find('tbody tr');

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
        }

        category[stat_title] = stats;
    }

    return category;
};

var getKeyByDataCategoryId = function (data_category_id) {
    switch (data_category_id) {
        case "0x02E00000FFFFFFFF":
            return "All";
        case "0x02E0000000000002":
            return "Reaper";
        case "0x02E0000000000003":
            return "Tracer";
        case "0x02E0000000000004":
            return "Mercy";
        case "0x02E0000000000005":
            return "Hanzo";
        case "0x02E0000000000006":
            return "Torbjoern";
        case "0x02E0000000000007":
            return "Reinhardt";
        case "0x02E0000000000008":
            return "Pharah";
        case "0x02E0000000000009":
            return "Winston";
        case "0x02E000000000000A":
            return "Widowmaker";
        case "0x02E0000000000015":
            return "Bastion";
        case "0x02E0000000000016":
            return "Symmetra";
        case "0x02E0000000000020":
            return "Zenyatta";
        case "0x02E0000000000029":
            return "Genji";
        case "0x02E0000000000040":
            return "Roadhog";
        case "0x02E0000000000042":
            return "McCree";
        case "0x02E0000000000065":
            return "Junkrat";
        case "0x02E0000000000068":
            return "Zarya";
        case "0x02E000000000006E":
            return "Soldier76";
        case "0x02E0000000000079":
            return "Lucio";
        case "0x02E000000000007A":
            return "DVa";
        case "0x02E00000000000DD":
            return "Mei";
        case "0x02E000000000012E":
            return "Sombra";
        case "0x02E000000000013B":
            return "Ana";
        default:
            return data_category_id;
            break;
    }
};
