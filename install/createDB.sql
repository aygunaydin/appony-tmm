CREATE TABLE app_list
(
   appid            int(11),
   appname          varchar(255),
   app_add_date     timestamp DEFAULT 'CURRENT_TIMESTAMP',
   android_name     varchar(100),
   `androidToken`   varchar(100) DEFAULT 'NULL'
)


CREATE TABLE android_app_rating_history
(
   app_name    char(25),
   rating      float,
   rater_num   int(11),
   rate_date   timestamp DEFAULT 'CURRENT_TIMESTAMP'
)

CREATE TABLE app_rating_history
(
   app_id      int(11),
   rating      float,
   rater_num   int(11),
   rate_date   timestamp DEFAULT 'CURRENT_TIMESTAMP'
)

CREATE TABLE comments
(
   store          varchar(20),
   comment_id     int(100),
   user           varchar(50),
   title          varchar(200),
   comment        varchar(1500),
   comment_date   timestamp DEFAULT 'CURRENT_TIMESTAMP',
   appname        varchar(20)
)


####APP_LIST TABLE DATA

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (583274826,
             'bip',
             '2016-08-03 14:21:15.0',
             'com.turkcell.bip',
             'knox9rVichjFEfe-dYME4Xgj4iA',
             1);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (404239912,
             'fizy',
             '2016-08-03 14:31:27.0',
             'com.turkcell.gncplay',
             'u7vV3bnaepQmvdcp0kUiWG4EE20',
             1);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (665036334,
             'depo',
             '2016-08-03 14:31:57.0',
             'tr.com.turkcell.akillidepo',
             'knox9rVichjFEfe-dYME4Xgj4iA',
             1);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (335162906,
             'hesabim',
             '2016-08-03 14:32:23.0',
             'com.ttech.android.onlineislem',
             'FsoJuxIler9lDCglbfOFfdLzY9U',
             1);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (877589104,
             'akademi',
             '2016-08-03 14:32:50.0',
             'com.turkcell.akademi',
             'u7vV3bnaepQmvdcp0kUiWG4EE20',
             1);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (1026830839,
             'RBT',
             '2016-08-03 14:33:19.0',
             'tr.com.turkcell.calarkendinlet',
             'l8pQfTcYYxcYSAOL2HZClRH0AzU',
             1);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (671494224,
             'platinum',
             '2016-08-05 12:30:12.0',
             'com.turkcellplatinum.main',
             'u7vV3bnaepQmvdcp0kUiWG4EE20',
             1);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (894318685,
             'gnc',
             '2016-08-09 08:04:23.0',
             'com.solidict.gnc2',
             'PyyyQZCTxW1Yg_Ud6CNSKVUfln0',
             1);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (324684580,
             'spotify',
             '2016-08-10 07:18:23.0',
             'com.spotify.music',
             'knox9rVichjFEfe-dYME4Xgj4iA',
             0);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (327630330,
             'dropbox',
             '2016-08-10 07:27:41.0',
             'com.dropbox.android',
             'l8pQfTcYYxcYSAOL2HZClRH0AzU',
             0);

INSERT INTO `appony.app_list`(appid,
                           appname,
                           app_add_date,
                           android_name,
                           `androidToken`,
                           `isTurkcell`)
     VALUES (310633997,
             'whatsapp',
             '2016-08-10 07:29:02.0',
             'com.whatsapp',
             'l8pQfTcYYxcYSAOL2HZClRH0AzU',
             0);

COMMIT;
