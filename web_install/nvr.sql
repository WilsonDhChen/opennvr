-- MySQL dump 10.14  Distrib 5.5.64-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: nvr
-- ------------------------------------------------------
-- Server version	5.5.64-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bi_gb28181_subdevs_auth`
--
CREATE DATABASE IF NOT EXISTS  `nvr`;
USE  `nvr`;
DROP TABLE IF EXISTS `bi_gb28181_subdevs_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bi_gb28181_subdevs_auth` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sDevId` varchar(45) DEFAULT '',
  `sUser` varchar(200) DEFAULT NULL,
  `sPwd` varchar(45) DEFAULT NULL,
  `server_type` varchar(30) DEFAULT NULL,
  `ip_recv_video` varchar(80) NOT NULL,
  `org_type` varchar(30) DEFAULT '',
  `gbversion` varchar(20) DEFAULT '',
  `transfer_mode` varchar(20) DEFAULT '',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`),
  KEY `sDevId` (`sDevId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bi_gb28181_subdevs_auth`
--

LOCK TABLES `bi_gb28181_subdevs_auth` WRITE;
/*!40000 ALTER TABLE `bi_gb28181_subdevs_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `bi_gb28181_subdevs_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bi_groups`
--

DROP TABLE IF EXISTS `bi_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bi_groups` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(100) NOT NULL DEFAULT '',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `sName_groups` (`sName`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bi_groups`
--

LOCK TABLES `bi_groups` WRITE;
/*!40000 ALTER TABLE `bi_groups` DISABLE KEYS */;
INSERT INTO `bi_groups` VALUES (1,'默认',0),(26,'一楼',0),(27,'地下',0),(28,'室外',0);
/*!40000 ALTER TABLE `bi_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bi_live_streams`
--

DROP TABLE IF EXISTS `bi_live_streams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bi_live_streams` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(100) NOT NULL DEFAULT '',
  `sComment` varchar(100) NOT NULL DEFAULT '',
  `sId` varchar(100) NOT NULL DEFAULT '',
  `sType` varchar(20) NOT NULL DEFAULT '',
  `sUrl` varchar(400) NOT NULL DEFAULT '',
  `sVideoList` varchar(10000) NOT NULL DEFAULT '',
  `sOutUrl` varchar(400) NOT NULL DEFAULT '',
  `bAuth` int(11) NOT NULL DEFAULT '0',
  `sUser` varchar(40) NOT NULL DEFAULT '',
  `sPassword` varchar(40) NOT NULL DEFAULT '',
  `sUrlArgs` varchar(300) NOT NULL DEFAULT '',
  `nChId` int(11) NOT NULL DEFAULT '0',
  `bRecord` int(11) NOT NULL DEFAULT '0',
  `sRecordDir` varchar(260) NOT NULL DEFAULT '',
  `nRecordDuration` int(11) NOT NULL DEFAULT '0',
  `sRecordFormat` varchar(10) NOT NULL DEFAULT '',
  `bOutHls` int(11) NOT NULL DEFAULT '1',
  `bOutRtmp` int(11) NOT NULL DEFAULT '1',
  `bOutTs` int(11) NOT NULL DEFAULT '1',
  `bOutRtsp` int(11) NOT NULL DEFAULT '1',
  `bOutFlv` int(11) NOT NULL DEFAULT '1',
  `bMemhls` int(11) NOT NULL DEFAULT '1',
  `nM3u8Files` int(11) NOT NULL DEFAULT '0',
  `nHlsFileDuration` int(11) NOT NULL DEFAULT '0',
  `sHlsDir` varchar(260) NOT NULL DEFAULT '',
  `sHlsFilePrefix` varchar(100) NOT NULL DEFAULT '',
  `bTranscodeTpl` int(11) NOT NULL DEFAULT '0',
  `sTranscodeTplName` varchar(40) NOT NULL DEFAULT '',
  `bVideoTranscode` int(11) NOT NULL DEFAULT '0',
  `bAudioTranscode` int(11) NOT NULL DEFAULT '0',
  `nVideoFps` int(11) NOT NULL DEFAULT '0',
  `sVideoSize` varchar(40) NOT NULL DEFAULT '',
  `bDeinterlace` int(11) NOT NULL DEFAULT '0',
  `sVProfile` varchar(80) NOT NULL DEFAULT '',
  `sAspect` varchar(40) NOT NULL DEFAULT '',
  `sVideoBitrateType` varchar(40) NOT NULL DEFAULT '',
  `nGopSize` int(11) NOT NULL DEFAULT '0',
  `nVideoBitrate` int(11) NOT NULL DEFAULT '0',
  `nAudioSamplerate` int(11) NOT NULL DEFAULT '0',
  `bHaveArgs` int(11) NOT NULL DEFAULT '0',
  `bGlobalArgs` int(11) NOT NULL DEFAULT '0',
  `sArgs` varchar(300) NOT NULL DEFAULT '',
  `sWDay0` varchar(1000) NOT NULL DEFAULT '',
  `sWDay1` varchar(1000) NOT NULL DEFAULT '',
  `sWDay2` varchar(1000) NOT NULL DEFAULT '',
  `sWDay3` varchar(1000) NOT NULL DEFAULT '',
  `sWDay4` varchar(1000) NOT NULL DEFAULT '',
  `sWDay5` varchar(1000) NOT NULL DEFAULT '',
  `sWDay6` varchar(1000) NOT NULL DEFAULT '',
  `nStartType` int(11) NOT NULL DEFAULT '0',
  `bGB28181Output` int(11) NOT NULL DEFAULT '0',
  `sGB28181Id` varchar(30) NOT NULL DEFAULT '',
  `sGB28181InputId` varchar(30) NOT NULL DEFAULT '',
  `sGB28181Pwd` varchar(30) NOT NULL DEFAULT '',
  `bUdpTsOutput` int(11) NOT NULL DEFAULT '0',
  `sUdpTsOutAddr` varchar(30) NOT NULL DEFAULT '',
  `sUdpTsOutEth` varchar(30) NOT NULL DEFAULT '',
  `sUdpTsOutServiceName` varchar(30) NOT NULL DEFAULT '',
  `nUdpTsOutServiceId` int(11) NOT NULL DEFAULT '0',
  `sUdpTsOutMuxrate` varchar(30) NOT NULL DEFAULT '',
  `nGroupId` int(11) NOT NULL DEFAULT '0',
  `sOnvifAddr` varchar(200) NOT NULL DEFAULT '',
  `sOnvifUser` varchar(30) NOT NULL DEFAULT '',
  `sOnvifPwd` varchar(30) NOT NULL DEFAULT '',
  `sOnvifVideoToken` varchar(80) NOT NULL DEFAULT '',
  `sOnvifPTZToken` varchar(80) NOT NULL DEFAULT '',
  `nOrder` int(11) NOT NULL DEFAULT '0',
  `nNop` int(11) NOT NULL DEFAULT '0',
  `bPublish` int(11) DEFAULT '1',
  `sUdpRecvEth` varchar(30) DEFAULT '',
  `sUdpRecvAddr` varchar(30) DEFAULT '',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `sId_live_streams` (`sId`)
) ENGINE=MyISAM AUTO_INCREMENT=521 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bi_live_streams`
--

LOCK TABLES `bi_live_streams` WRITE;
/*!40000 ALTER TABLE `bi_live_streams` DISABLE KEYS */;
/*!40000 ALTER TABLE `bi_live_streams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bi_record_streams`
--

DROP TABLE IF EXISTS `bi_record_streams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bi_record_streams` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sType` varchar(20) NOT NULL DEFAULT '',
  `sName` varchar(100) NOT NULL DEFAULT '',
  `sComment` varchar(100) NOT NULL DEFAULT '',
  `sInUrl` varchar(400) NOT NULL DEFAULT '',
  `bAuth` int(11) NOT NULL DEFAULT '0',
  `sUser` varchar(40) NOT NULL DEFAULT '',
  `sPassword` varchar(40) NOT NULL DEFAULT '',
  `sUrlArgs` varchar(300) NOT NULL DEFAULT '',
  `sFormat` varchar(8) NOT NULL DEFAULT '',
  `sOutUrl` varchar(400) NOT NULL DEFAULT '',
  `sEpgContent` varchar(15000) NOT NULL DEFAULT '',
  `bVideoTranscode` int(11) NOT NULL DEFAULT '0',
  `bAudioTranscode` int(11) NOT NULL DEFAULT '0',
  `nVideoFps` int(11) NOT NULL DEFAULT '0',
  `sVideoSize` varchar(40) NOT NULL DEFAULT '',
  `nAudioSamplerate` int(11) NOT NULL DEFAULT '0',
  `bHaveArgs` int(11) NOT NULL DEFAULT '0',
  `bGlobalArgs` int(11) NOT NULL DEFAULT '0',
  `sArgs` varchar(300) NOT NULL DEFAULT '',
  `sBeginTime` varchar(40) NOT NULL DEFAULT '',
  `sEndTime` varchar(40) NOT NULL DEFAULT '',
  `nTimeOffset` int(11) NOT NULL DEFAULT '0',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `sName_record_streams` (`sName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bi_record_streams`
--

LOCK TABLES `bi_record_streams` WRITE;
/*!40000 ALTER TABLE `bi_record_streams` DISABLE KEYS */;
/*!40000 ALTER TABLE `bi_record_streams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bi_route`
--

DROP TABLE IF EXISTS `bi_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bi_route` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sNet` varchar(80) NOT NULL DEFAULT '',
  `sNetMask` varchar(80) NOT NULL DEFAULT '',
  `sDev` varchar(40) NOT NULL DEFAULT '',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `bi_route_key` (`sNet`,`sNetMask`,`sDev`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bi_route`
--

LOCK TABLES `bi_route` WRITE;
/*!40000 ALTER TABLE `bi_route` DISABLE KEYS */;
/*!40000 ALTER TABLE `bi_route` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bi_transcode_presets`
--

DROP TABLE IF EXISTS `bi_transcode_presets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bi_transcode_presets` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(40) NOT NULL DEFAULT '',
  `sParameter` varchar(400) NOT NULL DEFAULT '',
  `sOutFileExt` varchar(10) NOT NULL DEFAULT '',
  `nInputArgs` int(11) NOT NULL DEFAULT '1',
  `nOutputArgs` int(11) NOT NULL DEFAULT '1',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `sName` (`sName`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bi_transcode_presets`
--

LOCK TABLES `bi_transcode_presets` WRITE;
/*!40000 ALTER TABLE `bi_transcode_presets` DISABLE KEYS */;
INSERT INTO `bi_transcode_presets` VALUES (1,'FLV','$transcode -loglevel 16 -y -i \"%1\" -vcodec $videoenc -acodec $audioenc -f flv \"%2\"','flv',1,1,0),(2,'MP4','$transcode  -loglevel 16 -y -i \"%1\" -vcodec $videoenc -acodec $audioenc -f mp4 \"%2\"','mp4',1,1,0),(3,'TS','$transcode  -loglevel 16 -y -i \"%1\" -vcodec $videoenc -acodec $audioenc -f mpegts \"%2\"','ts',1,1,0);
/*!40000 ALTER TABLE `bi_transcode_presets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bi_transcode_tpls`
--

DROP TABLE IF EXISTS `bi_transcode_tpls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bi_transcode_tpls` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(40) NOT NULL DEFAULT '',
  `bVideoTranscode` int(11) NOT NULL DEFAULT '0',
  `nVideoFps` int(11) NOT NULL DEFAULT '0',
  `sVideoSize` varchar(40) NOT NULL DEFAULT '',
  `nVideoBitrate` int(11) NOT NULL DEFAULT '0',
  `bDeinterlace` int(11) NOT NULL DEFAULT '0',
  `sVProfile` varchar(80) NOT NULL DEFAULT '',
  `sAspect` varchar(40) NOT NULL DEFAULT '',
  `sVideoBitrateType` varchar(40) NOT NULL DEFAULT '',
  `nGopSize` int(11) NOT NULL DEFAULT '0',
  `bAudioTranscode` int(11) NOT NULL DEFAULT '0',
  `nAudioSamplerate` int(11) NOT NULL DEFAULT '0',
  `nAudioChannels` int(11) NOT NULL DEFAULT '0',
  `bHaveArgs` int(11) NOT NULL DEFAULT '0',
  `sArgs` varchar(300) NOT NULL DEFAULT '',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `sName_bi_transcodetpl` (`sName`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bi_transcode_tpls`
--

LOCK TABLES `bi_transcode_tpls` WRITE;
/*!40000 ALTER TABLE `bi_transcode_tpls` DISABLE KEYS */;
/*!40000 ALTER TABLE `bi_transcode_tpls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_gb28181_keyvalue`
--

DROP TABLE IF EXISTS `config_gb28181_keyvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_gb28181_keyvalue` (
  `name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `value` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `desc` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_gb28181_keyvalue`
--

LOCK TABLES `config_gb28181_keyvalue` WRITE;
/*!40000 ALTER TABLE `config_gb28181_keyvalue` DISABLE KEYS */;
INSERT INTO `config_gb28181_keyvalue` VALUES ('log_level','DETAIL','日志级别',1),('my_id','21010100002000000002','本设备的国标Id',2),('my_domain','','本设备的国标域',3),('register_check_domain','0','下级设备接入是否做域匹配验证',4),('register_need_auth','0','下级设备接入是否验证密码',5),('read_catalog','1','下级设备接入时是否读取目录',6),('refresh_catalog_time','0','目录刷新时间间隔',7),('sip_timeout','180','SIP会话超时时间',8),('device_name','lanyu-nvr','设备名字',9),('log_debug','1','是否输出调试信息',10),('mode','PLATFORM',NULL,11),('forward_video','1','转发视频',12);
/*!40000 ALTER TABLE `config_gb28181_keyvalue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_gb28181_parents`
--

DROP TABLE IF EXISTS `config_gb28181_parents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_gb28181_parents` (
  `enable` int(11) DEFAULT NULL,
  `connect_mode` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `name` varchar(40) NOT NULL DEFAULT '',
  `server_id` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `server_domain` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `via_addr` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `via_port` int(11) NOT NULL,
  `addr` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `user` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `pwd` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `keepalive_time` int(11) DEFAULT NULL,
  `autopush_catalog` int(11) NOT NULL,
  `shared_all` int(11) NOT NULL,
  `catalog_per_packet` int(11) NOT NULL,
  `charset` varchar(30) DEFAULT '',
  `server_type` varchar(30) DEFAULT '',
  `contact_addr` varchar(128) DEFAULT NULL,
  `rtsp_addr` varchar(128) DEFAULT '',
  `vts_addr` varchar(128) DEFAULT '',
  `ip_recv_video` varchar(100) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_gb28181_parents`
--

LOCK TABLES `config_gb28181_parents` WRITE;
/*!40000 ALTER TABLE `config_gb28181_parents` DISABLE KEYS */;
/*!40000 ALTER TABLE `config_gb28181_parents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_gb28181_sips`
--

DROP TABLE IF EXISTS `config_gb28181_sips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_gb28181_sips` (
  `enable_tcp` int(11) DEFAULT NULL,
  `enable_udp` int(11) DEFAULT NULL,
  `bind_addr` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `bind_addr6` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `port` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_gb28181_sips`
--

LOCK TABLES `config_gb28181_sips` WRITE;
/*!40000 ALTER TABLE `config_gb28181_sips` DISABLE KEYS */;
INSERT INTO `config_gb28181_sips` VALUES (1,1,'*','*','5062',1);
/*!40000 ALTER TABLE `config_gb28181_sips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_global_keyvalue`
--

DROP TABLE IF EXISTS `config_global_keyvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_global_keyvalue` (
  `name` varchar(45) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `desc` varchar(45) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_global_keyvalue`
--

LOCK TABLES `config_global_keyvalue` WRITE;
/*!40000 ALTER TABLE `config_global_keyvalue` DISABLE KEYS */;
INSERT INTO `config_global_keyvalue` VALUES ('device_name','test','设备名字',14),('mode','PLATFORM','工作模式',15),('app','gb28181','Application           ',16),('domain','',NULL,17);
/*!40000 ALTER TABLE `config_global_keyvalue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_mediasrv_ets`
--

DROP TABLE IF EXISTS `config_mediasrv_ets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_mediasrv_ets` (
  `enable` int(11) DEFAULT '1',
  `bind_addr` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `bind_addr6` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `port` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `publish` int(11) DEFAULT '1',
  `playback` int(11) DEFAULT '1',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_mediasrv_ets`
--

LOCK TABLES `config_mediasrv_ets` WRITE;
/*!40000 ALTER TABLE `config_mediasrv_ets` DISABLE KEYS */;
INSERT INTO `config_mediasrv_ets` VALUES (1,'*','*','2554',1,1,3);
/*!40000 ALTER TABLE `config_mediasrv_ets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_mediasrv_global_keyvalue`
--

DROP TABLE IF EXISTS `config_mediasrv_global_keyvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_mediasrv_global_keyvalue` (
  `name` varchar(45) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `desc` varchar(45) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_mediasrv_global_keyvalue`
--

LOCK TABLES `config_mediasrv_global_keyvalue` WRITE;
/*!40000 ALTER TABLE `config_mediasrv_global_keyvalue` DISABLE KEYS */;
INSERT INTO `config_mediasrv_global_keyvalue` VALUES ('record_type','NVR2','下拉选择NVR  NVR2  SESSION',32),('record_ts','1',NULL,33),('record_mp4','0',NULL,34),('record_flv','0',NULL,35),('record_ts_dir','/var/www/ts',NULL,36),('record_mp4_dir','/var/www/mp4',NULL,37),('record_flv_dir','/var/www/flv',NULL,38),('output_hls','1',NULL,39),('memory_file','1',NULL,40),('hls_dir','/',NULL,41),('hls_ts_prefix','',NULL,42),('udp_port_range','25001-26001',NULL,43),('enable_hiktcprtp','0',NULL,44),('hik_port_video','25000',NULL,45),('hik_port_audio','25001',NULL,46),('hik_port_video4route','0',NULL,47),('hik_port_audio4route','0',NULL,48),('tcp_port_range','25001-26001',NULL,50);
/*!40000 ALTER TABLE `config_mediasrv_global_keyvalue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_mediasrv_httpdhls`
--

DROP TABLE IF EXISTS `config_mediasrv_httpdhls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_mediasrv_httpdhls` (
  `enable` int(11) DEFAULT NULL,
  `bind_addr` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `bind_addr6` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `port` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `keepalive` int(11) DEFAULT '0',
  `enable206` int(11) DEFAULT '1',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_mediasrv_httpdhls`
--

LOCK TABLES `config_mediasrv_httpdhls` WRITE;
/*!40000 ALTER TABLE `config_mediasrv_httpdhls` DISABLE KEYS */;
INSERT INTO `config_mediasrv_httpdhls` VALUES (1,'*','*','280',0,1,2);
/*!40000 ALTER TABLE `config_mediasrv_httpdhls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_mediasrv_httpts`
--

DROP TABLE IF EXISTS `config_mediasrv_httpts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_mediasrv_httpts` (
  `enable` int(11) DEFAULT '1',
  `bind_addr` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `bind_addr6` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `port` int(11) DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_mediasrv_httpts`
--

LOCK TABLES `config_mediasrv_httpts` WRITE;
/*!40000 ALTER TABLE `config_mediasrv_httpts` DISABLE KEYS */;
INSERT INTO `config_mediasrv_httpts` VALUES (1,'*','*',281,2);
/*!40000 ALTER TABLE `config_mediasrv_httpts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_mediasrv_keyvalue`
--

DROP TABLE IF EXISTS `config_mediasrv_keyvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_mediasrv_keyvalue` (
  `name` varchar(45) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `desc` varchar(45) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_mediasrv_keyvalue`
--

LOCK TABLES `config_mediasrv_keyvalue` WRITE;
/*!40000 ALTER TABLE `config_mediasrv_keyvalue` DISABLE KEYS */;
INSERT INTO `config_mediasrv_keyvalue` VALUES ('fast_start','1',NULL,19),('enable_snapshot','1',NULL,20),('snapshot_updatetime','5',NULL,21),('snapshot_size','320x240',NULL,22),('network_timeout','20',NULL,23),('log_level','DETAIL',NULL,24),('log_debug','1',NULL,25);
/*!40000 ALTER TABLE `config_mediasrv_keyvalue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_mediasrv_rtmp`
--

DROP TABLE IF EXISTS `config_mediasrv_rtmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_mediasrv_rtmp` (
  `enable` int(11) DEFAULT '1',
  `bind_addr` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `bind_addr6` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `port` int(11) DEFAULT '0',
  `publish` int(11) DEFAULT '1',
  `playback` int(11) DEFAULT '1',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_mediasrv_rtmp`
--

LOCK TABLES `config_mediasrv_rtmp` WRITE;
/*!40000 ALTER TABLE `config_mediasrv_rtmp` DISABLE KEYS */;
INSERT INTO `config_mediasrv_rtmp` VALUES (1,'*','*',1935,0,1,3);
/*!40000 ALTER TABLE `config_mediasrv_rtmp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_mediasrv_rtsp`
--

DROP TABLE IF EXISTS `config_mediasrv_rtsp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_mediasrv_rtsp` (
  `enable` int(11) DEFAULT '1',
  `bind_addr` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `bind_addr6` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `port` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `publish` int(11) DEFAULT '1',
  `playback` int(11) DEFAULT '1',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_mediasrv_rtsp`
--

LOCK TABLES `config_mediasrv_rtsp` WRITE;
/*!40000 ALTER TABLE `config_mediasrv_rtsp` DISABLE KEYS */;
INSERT INTO `config_mediasrv_rtsp` VALUES (1,'*','*','554',1,1,2);
/*!40000 ALTER TABLE `config_mediasrv_rtsp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ext_gb28181_catalog`
--

DROP TABLE IF EXISTS `ext_gb28181_catalog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ext_gb28181_catalog` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sParentChid` varchar(40) DEFAULT '',
  `sParentName` varchar(100) DEFAULT '',
  `sChid` varchar(40) DEFAULT '',
  `sUpOrg` varchar(40) DEFAULT '',
  `bOrg` int(11) DEFAULT '0',
  `sName` varchar(100) DEFAULT '',
  `sManufacturer` varchar(40) DEFAULT '',
  `sModel` varchar(40) DEFAULT '',
  `sOwner` varchar(40) DEFAULT '',
  `sCivil` varchar(40) DEFAULT '',
  `sAddress` varchar(100) DEFAULT '',
  `sParental` varchar(40) DEFAULT '',
  `sParentID` varchar(40) DEFAULT '',
  `sSafetyWay` varchar(40) DEFAULT '',
  `sRegisterWay` varchar(40) DEFAULT '',
  `sSecrecy` varchar(40) DEFAULT '',
  `sStatus` varchar(10) DEFAULT '',
  `fLongitude` double DEFAULT '0',
  `fLatitude` double DEFAULT '0',
  `sIPAddress` varchar(100) DEFAULT '',
  `nPtzType` int(11) DEFAULT '0',
  `sPositionType` varchar(10) DEFAULT '',
  `sRoomType` varchar(10) DEFAULT '',
  `sUseType` varchar(10) DEFAULT '',
  `sSupplyLightType` varchar(10) DEFAULT '',
  `sDirectionType` varchar(10) DEFAULT '',
  `nType` int(11) DEFAULT '0',
  `sType` varchar(40) DEFAULT '',
  `sTypeName` varchar(40) DEFAULT '',
  `sAreaProvince` varchar(20) DEFAULT '',
  `sAreaCity` varchar(20) DEFAULT '',
  `sAreaDistrict` varchar(20) DEFAULT '',
  `sAreaName` varchar(100) DEFAULT '',
  `sUpdateTime` varchar(30) DEFAULT '',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `ext_gb28181_catalog_key1` (`sParentChid`,`sChid`),
  UNIQUE KEY `ext_gb28181_catalog_key2` (`sParentChid`,`sAreaProvince`,`sAreaCity`,`sAreaDistrict`,`sChid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ext_gb28181_catalog`
--

LOCK TABLES `ext_gb28181_catalog` WRITE;
/*!40000 ALTER TABLE `ext_gb28181_catalog` DISABLE KEYS */;
/*!40000 ALTER TABLE `ext_gb28181_catalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ext_gb28181_devs`
--

DROP TABLE IF EXISTS `ext_gb28181_devs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ext_gb28181_devs` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sDevid` varchar(40) DEFAULT '',
  `sName` varchar(100) DEFAULT '',
  `sManufacturer` varchar(40) DEFAULT '',
  `sModel` varchar(40) DEFAULT '',
  `sFirmware` varchar(40) DEFAULT '',
  `sIp` varchar(80) DEFAULT '',
  `sOnlineTime` varchar(30) DEFAULT '',
  `sOfflineTime` varchar(30) DEFAULT '',
  `bOnline` int(11) DEFAULT '0',
  `bAllowAccess` int(11) DEFAULT '0',
  `sIpRecvVideo` varchar(100) DEFAULT '',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `ext_gb28181_devs` (`sDevid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ext_gb28181_devs`
--

LOCK TABLES `ext_gb28181_devs` WRITE;
/*!40000 ALTER TABLE `ext_gb28181_devs` DISABLE KEYS */;
INSERT INTO `ext_gb28181_devs` VALUES (1,'21010100001180000002','unicom\'s Virtual NVR','LanYu(Dalian).Inc','','','','2019-10-25 20:11:35','',1,0,'');
/*!40000 ALTER TABLE `ext_gb28181_devs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ext_live_status`
--

DROP TABLE IF EXISTS `ext_live_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ext_live_status` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sDate` varchar(12) NOT NULL DEFAULT '',
  `sTime` varchar(12) NOT NULL DEFAULT '',
  `sDateTime` varchar(24) NOT NULL DEFAULT '',
  `nTime` bigint(20) NOT NULL DEFAULT '0',
  `nEts` int(11) NOT NULL DEFAULT '0',
  `nRtmp` int(11) NOT NULL DEFAULT '0',
  `nHttp` int(11) NOT NULL DEFAULT '0',
  `nRtspTs` int(11) NOT NULL DEFAULT '0',
  `nMemMax` bigint(20) NOT NULL DEFAULT '0',
  `nMemUsed` bigint(20) NOT NULL DEFAULT '0',
  `nMemFree` bigint(20) NOT NULL DEFAULT '0',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `live_connections` (`sDateTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ext_live_status`
--

LOCK TABLES `ext_live_status` WRITE;
/*!40000 ALTER TABLE `ext_live_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `ext_live_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ext_pshared___css__`
--

DROP TABLE IF EXISTS `ext_pshared___css__`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ext_pshared___css__` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sParentChid` varchar(40) DEFAULT '',
  `sChid` varchar(40) DEFAULT '',
  `sChidNew` varchar(40) DEFAULT '',
  `sNameNew` varchar(100) DEFAULT '',
  `sUpOrg` varchar(40) DEFAULT '',
  `bOrg` int(11) DEFAULT '0',
  PRIMARY KEY (`nId`),
  UNIQUE KEY `ext_pshared_key1` (`sParentChid`,`sChid`)
) ENGINE=MyISAM AUTO_INCREMENT=596705 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ext_pshared___css__`
--

LOCK TABLES `ext_pshared___css__` WRITE;
/*!40000 ALTER TABLE `ext_pshared___css__` DISABLE KEYS */;
/*!40000 ALTER TABLE `ext_pshared___css__` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ext_record_result`
--

DROP TABLE IF EXISTS `ext_record_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ext_record_result` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sTaskName` varchar(100) NOT NULL DEFAULT '',
  `sName` varchar(100) NOT NULL DEFAULT '',
  `sInputUrl` varchar(400) NOT NULL DEFAULT '',
  `sType` varchar(20) NOT NULL DEFAULT '',
  `sEpgName` varchar(200) NOT NULL DEFAULT '',
  `sEpgDateTime` varchar(24) NOT NULL DEFAULT '',
  `sEpgDate` varchar(12) NOT NULL DEFAULT '',
  `sEpgTime` varchar(12) NOT NULL DEFAULT '',
  `sFullPath` varchar(300) NOT NULL DEFAULT '',
  `sFileName` varchar(200) NOT NULL DEFAULT '',
  `sFileType` varchar(12) NOT NULL DEFAULT '',
  `sBeginTime` varchar(24) NOT NULL DEFAULT '',
  `sEndTime` varchar(24) NOT NULL DEFAULT '',
  `nDur` int(11) NOT NULL DEFAULT '0',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ext_record_result`
--

LOCK TABLES `ext_record_result` WRITE;
/*!40000 ALTER TABLE `ext_record_result` DISABLE KEYS */;
/*!40000 ALTER TABLE `ext_record_result` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ext_transcode_files`
--

DROP TABLE IF EXISTS `ext_transcode_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ext_transcode_files` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `sSource` varchar(40) NOT NULL DEFAULT '',
  `nSourceId` int(11) NOT NULL DEFAULT '0',
  `sTimeAdd` varchar(30) NOT NULL DEFAULT '',
  `sInUrl` varchar(400) NOT NULL DEFAULT '',
  `sOutUrl` varchar(800) NOT NULL DEFAULT '',
  `sPreset` varchar(40) NOT NULL DEFAULT '',
  `sTimeBegin` varchar(30) NOT NULL DEFAULT '',
  `nStatus` int(11) NOT NULL DEFAULT '0',
  `sStatus` varchar(20) NOT NULL DEFAULT '',
  `sMsg` varchar(800) NOT NULL DEFAULT '',
  `nUsedTime` int(11) NOT NULL DEFAULT '0',
  `nNop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ext_transcode_files`
--

LOCK TABLES `ext_transcode_files` WRITE;
/*!40000 ALTER TABLE `ext_transcode_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `ext_transcode_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_config`
--

DROP TABLE IF EXISTS `ly_sys_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_config` (
  `config_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级config_id',
  `identifier` varchar(100) DEFAULT NULL,
  `name` varchar(60) NOT NULL COMMENT '配置中文名称',
  `attrs` varchar(3000) NOT NULL COMMENT '配置属性(json)',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `valid_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `insert_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`config_id`),
  KEY `parent_id` (`parent_id`),
  KEY `identifier` (`identifier`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COMMENT='系统配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_config`
--

LOCK TABLES `ly_sys_config` WRITE;
/*!40000 ALTER TABLE `ly_sys_config` DISABLE KEYS */;
INSERT INTO `ly_sys_config` VALUES (1,0,'top_system','系统配置','',0,1,1433298228),(2,0,'top_common','常用配置','',0,1,1433298230),(3,1,'','系统基本配置','',3,1,1434274123),(4,3,'sys_cnname','系统显示名称','{\"value\":\"\\u5170\\u5b87VTS2.0\\u7ba1\\u7406\\u7cfb\\u7edf\"}',0,1,1434274144),(5,1,'','系统表单模型','',0,1,1434274126),(6,2,'com_setting','配置管理','{\"config_type\":\"opts\",\"config_name\":\"\\u914d\\u7f6e\\u540d\\u79f0\",\"identifier_name\":\"\\u914d\\u7f6e\\u6807\\u8bc6\",\"config_id\":\"1\",\"config_time\":\"1\",\"config_sort\":\"1\"}',0,1,1496199590),(8,6,'api_site','接口站点','{\"value\":\"http:\\/\\/127.0.0.1:180\"}',0,1,1496214197),(9,6,'api_camera','摄像头接口配置','{\"livestreamtypes\":\"\\/streams\\/livestreamtypes.json\",\"streams\":\"\\/streams\\/streams.json\",\"detail_by_sysid\":\"\\/streams\\/{sysid}.json\",\"detail_by_id\":\"\\/streams\\/id\\/{id}.json\",\"add\":\"\\/streams\\/add.json\",\"delete\":\"\\/streams\\/delete.json\",\"update\":\"\\/streams\\/update.json\",\"status_by_sysid\":\"\\/streams\\/status\\/{sysid}.json\",\"status_by_id\":\"\\/streams\\/status\\/id\\/{id}.json\",\"action\":\"\\/streams\\/action.json\",\"setting\":\"\\/streams\\/setting.json\",\"ptz\":\"\\/streams\\/ptz.json\",\"scan\":\"\\/onvif\\/scan.json\",\"getallchsbytree\":\"\\/gb28181-api\\/getallchsbytree.json\",\"eths\":\"\\/mediasrv-api\\/eths.json\",\"group_delete\":\"\\/group\\/delete.json\",\"udprecveths\":\"\\/eths.json\"}',0,1,1496214932),(10,2,'com_camera_adv','摄像头配置','{\"config_type\":\"node\",\"config_name\":\"\\u914d\\u7f6e\\u540d\\u79f0\",\"identifier_name\":\"\\u914d\\u7f6e\\u6807\\u8bc6\",\"config_sort\":\"1\",\"config_level\":2}',0,1,1496217854),(11,10,'camera_dpi','分辨率','',0,1,1496218106),(12,11,'','NoChange','',0,1,1496218158),(13,11,'','1080P','',0,1,1496218174),(14,11,'','720P','',0,1,1496218182),(15,11,'','1024x768','',0,1,1496218190),(16,11,'','800x600','',0,1,1496218206),(17,11,'','720x480','',0,1,1496218214),(18,11,'','640x480','',0,1,1496218221),(19,11,'','480x320','',0,1,1496218230),(20,11,'','320x240','',0,1,1496218239),(21,11,'','QCIF','',0,1,1496218251),(22,11,'','CIF','',0,1,1496218258),(23,11,'','HALF-D1','',0,1,1496218267),(24,11,'','D1','',0,1,1496218275),(25,10,'camera_fps','帧率','',0,1,1496218324),(26,25,'','15','',0,1,1496218353),(27,25,'','20','',0,1,1496218362),(28,25,'','25','',0,1,1496218371),(29,10,'camera_aspect','画面比率','',0,1,1496218405),(30,29,'','4:3','',0,1,1496218420),(31,29,'','16:9','',0,1,1496218428),(32,10,'camera_vprofile','编码质量','',0,1,1496218454),(33,32,'','baseline','',0,1,1496218497),(34,32,'','main','',0,1,1496218505),(35,32,'','high','',0,1,1496218517),(36,10,'camera_videobitratetype','编码方式','',0,1,1496218547),(37,36,'','cbr','',0,1,1496218562),(38,36,'','vbr','',0,1,1496218571),(39,10,'camera_audiosamplerate','音频采样率','',0,1,1496218604),(40,39,'','16000','',0,1,1496218619),(41,39,'','32000','',0,1,1496218628),(42,39,'','44100','',0,1,1496218637),(43,39,'','48000','',0,1,1496218648),(44,10,'camera_audiochannels','音频通道','',0,1,1496218694),(45,44,'','1','',0,1,1496218705),(46,44,'','2','',0,1,1496218716),(47,6,'api_play_url','播放地址','{\"value\":\"http:\\/\\/{host}:280\\/{app}\\/{id}.flv\"}',10,1,1496389005),(48,6,'api_network','网络接口配置','{\"lists\":\"\\/system\\/network.json\",\"update\":\"\\/system\\/network.json\",\"action\":\"\\/system\\/action.json\"}',0,1,1496393784),(49,6,'vods_download_timer','视频下载时间长度','{\"value\":\"4\"}',0,1,1497347576),(50,6,'api_down_url','下载地址','{\"value\":\"http:\\/\\/{host}:280\\/{app}\\/{id}.ts\"}',10,1,1497593889),(51,6,'api_gb28181','gb28181接口配置','{\"eths\":\"\\/gb28181-api\\/eths.json\",\"parentstatus\":\"\\/gb28181-api\\/parentstatus.json\",\"parentchanged\":\"\\/gb28181-api\\/parentchanged.json\",\"basechanged\":\"\\/gb28181-api\\/basechanged.json\",\"cmd\":\"\\/gb28181-api\\/cmd.json\",\"getsubdevs\":\"\\/gb28181-api\\/getsubdevs\",\"address\":\"\\/gb28181\\/address.json\"}',0,1,1497945358),(52,6,'system_tool','工具配置','{\"mediasrvstatus\":\"\\/mediasrv\\/status.json\",\"resources\":\"\\/system\\/status.json\",\"system_action\":\"\\/system\\/action.json\"}',0,1,1498390954),(53,6,'snapshot_url','快照地址','{\"value\":\"http:\\/\\/{host}:280\\/{app}\\/{id}.snapshot\"}',0,1,1499484333),(54,6,'square_status','是否开启视频广场','{\"value\":\"1\"}',0,1,1499508972),(55,6,'api_play_url_m3u8','m3u8播放地址','{\"value\":\"http:\\/\\/{host}:280\\/{app}\\/{id}.m3u8\"}',10,1,1500262218),(57,6,'api_driver','磁盘设置api','{\"value\":\"http:\\/\\/127.0.0.1:580\\/storage.json?type={type}\"}',0,1,1534312008);
/*!40000 ALTER TABLE `ly_sys_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_factory`
--

DROP TABLE IF EXISTS `ly_sys_factory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_factory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '名字',
  `py` varchar(255) DEFAULT '' COMMENT '缩写',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_factory`
--

LOCK TABLES `ly_sys_factory` WRITE;
/*!40000 ALTER TABLE `ly_sys_factory` DISABLE KEYS */;
INSERT INTO `ly_sys_factory` VALUES (1,'海康','hik',0),(7,'其他','other',0);
/*!40000 ALTER TABLE `ly_sys_factory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_form_field`
--

DROP TABLE IF EXISTS `ly_sys_form_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_form_field` (
  `field_id` int(10) unsigned NOT NULL COMMENT '字段id',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '记录id',
  `form_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '表单id',
  `val_line` varchar(255) DEFAULT NULL COMMENT '表单值单行(短内容)',
  `val_area` text COMMENT '表单值多行(长内容)',
  UNIQUE KEY `field_id_2` (`field_id`,`record_id`),
  KEY `record_id` (`record_id`,`form_id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='【表单模型】字段表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_form_field`
--

LOCK TABLES `ly_sys_form_field` WRITE;
/*!40000 ALTER TABLE `ly_sys_form_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `ly_sys_form_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_form_record`
--

DROP TABLE IF EXISTS `ly_sys_form_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_form_record` (
  `record_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录id',
  `form_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '表单id',
  `insert_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '插入时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`record_id`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='【表单模型】记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_form_record`
--

LOCK TABLES `ly_sys_form_record` WRITE;
/*!40000 ALTER TABLE `ly_sys_form_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `ly_sys_form_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_navi`
--

DROP TABLE IF EXISTS `ly_sys_navi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_navi` (
  `navi_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '栏目父级id',
  `navi_name` varchar(60) NOT NULL COMMENT '栏目名称',
  `module` varchar(60) NOT NULL COMMENT '栏目模块名',
  `action` varchar(200) NOT NULL COMMENT '栏目操作名(多个以逗号分隔)',
  `conditions` varchar(600) NOT NULL COMMENT '权限附加条件表达式',
  `get_params` varchar(600) NOT NULL COMMENT '栏目附加GET参数',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目排序',
  `valid_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '启用状态',
  `insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`navi_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8 COMMENT='moa系统栏目表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_navi`
--

LOCK TABLES `ly_sys_navi` WRITE;
/*!40000 ALTER TABLE `ly_sys_navi` DISABLE KEYS */;
INSERT INTO `ly_sys_navi` VALUES (1,0,'系统管理','','','','',0,1,1433237764),(2,0,'权限管理','','','','',0,1,1429254388),(3,0,'员工管理','','','','',0,1,1429604653),(4,0,'个人中心','','','','',0,1,1429946538),(5,1,'系统栏目','section','index','','',0,1,1433227501),(6,2,'权限角色','role','index','','',0,1,1429602789),(7,2,'添加角色','role','add,load,add_post','','',0,1,1429602826),(8,6,'编辑','role','edit,load,edit_post','','',0,1,1429602841),(10,3,'员工列表','staff','index','','',0,1,1429604470),(11,3,'添加员工','staff','add,add_post','','',0,1,1429604864),(12,10,'编辑','staff','edit,edit_post','','',0,1,1429605184),(13,10,'删除','staff','delete','','',0,1,1429605219),(14,10,'修改密码','staff','password,password_post','','',0,1,1429610462),(15,4,'个人资料','my','info','','',0,1,1433225082),(16,15,'查看资料','my','info','','',0,1,1433154540),(17,15,'修改资料','my','edit,edit_post','','',0,1,1433154541),(18,15,'修改密码','my','password,password_post','','',0,1,1433154405),(19,10,'列表','staff','index','','',0,1,1433154906),(20,6,'列表','role','index','','',0,1,1433225370),(22,5,'编辑','section','edit,navi_edit_post,navi_add_post,add','','',0,1,1433226640),(23,5,'删除','section','navi_delete','','',0,1,1433229371),(24,5,'列表','section','index,load,get_navi','','',0,1,1433227481),(25,1,'系统配置','config','system','','',0,1,1433239751),(26,25,'列表','config','system,load,get_config','','',0,1,1433239795),(27,25,'增加','config','add,system_add_post','','',0,1,1433320704),(28,25,'编辑','config','edit,system_edit_post','','',0,1,1433320714),(29,6,'删除','role','delete,delete_post','','',0,1,1433239866),(30,1,'常用配置','config','common','','',0,1,1433239972),(31,25,'删除','config','delete,delete_post','','',0,1,1433323956),(32,30,'列表','config','common,,load,get_config','','',0,1,1433486144),(33,30,'增加','config','add,common_add_post','','',0,1,1433486331),(34,30,'编辑','config','edit,common_edit_post','','',0,1,1433486456),(35,30,'删除','config','delete,delete_post','','',0,1,1433486527),(36,25,'标识','config','identifier','','',0,1,1449658795),(37,30,'标识','config','identifier','','',0,1,1449658810),(38,30,'栏目','config','tonavi','','',0,1,1477298505),(39,0,'视频巡逻','','','','',9000,1,1495445755),(40,0,'摄像头管理','','','','',8500,1,1495445767),(41,0,'摄像头分组','','','','',8100,1,1495445774),(42,0,'存储设置','','','','',8350,1,1495445783),(43,0,'网络设置','','','','',8300,1,1495445792),(44,0,'系统维护','','','','',0,1,1495445800),(45,0,'配置管理','','','','',0,1,1495445846),(46,39,'实时视频','live','index','','',0,1,1495446637),(47,39,'视频回放_不用 ','video','index,lists','','',0,0,1495446758),(56,41,'分组列表','Groups','index','','',0,1,1495788633),(57,56,'新增','Groups','add','','',0,1,1495788657),(58,56,'列表','Groups','index','','',10,1,1495788701),(59,56,'修改','Groups','update','','',0,1,1495788739),(60,56,'删除','Groups','delete','','',0,1,1495788751),(61,40,'摄像头列表','Camera','index','','',0,1,1495801432),(62,61,'列表','Camera','index,lists,detail','','',0,1,1495801443),(63,61,'新增','Camera','save,get_scan,get_allchsbytree,add','','',0,1,1495801462),(65,61,'删除','Camera','delete','','',0,1,1495801479),(66,61,'详情','Camera','detail','','',0,1,1495880553),(67,45,'配置管理','config','com_setting__index','','',0,1,1496199637),(68,67,'列表','config','com_setting__index','','',0,1,1496199637),(69,67,'添加','config','com_setting__insert','','',0,1,1496199637),(70,67,'修改','config','com_setting__update','','',0,1,1496199637),(71,67,'删除','config','com_setting__delete','','',0,1,1496199637),(72,67,'详情','config','com_setting__detail','','',0,1,1496199637),(73,67,'标识','config','com_setting__identifier','','',0,1,1496199637),(74,45,'摄像头配置','config','com_camera_adv__index','','',0,1,1496218007),(75,74,'列表','config','com_camera_adv__index','','',0,1,1496218007),(76,74,'添加','config','com_camera_adv__insert','','',0,1,1496218007),(77,74,'修改','config','com_camera_adv__update','','',0,1,1496218007),(78,74,'删除','config','com_camera_adv__delete','','',0,1,1496218007),(79,74,'详情','config','com_camera_adv__detail','','',0,1,1496218007),(80,74,'标识','config','com_camera_adv__identifier','','',0,1,1496218007),(81,46,'列表','live','index,lists','','',0,1,1496369580),(82,46,'详情','live','detail','','',0,1,1496369676),(83,43,'网络设置','network','index,update,restart','','',0,1,1496391563),(84,46,'控制','live','ptz_control','','',0,1,1497162065),(85,47,'列表','video','index,lists','','',0,1,1497164559),(86,47,'详情','video','detail','','',0,1,1497164573),(88,39,'视频回放','download','','','',0,1,1497335070),(89,88,'列表','download','index,lists','','',0,1,1497335097),(90,88,'详情','download','detail,get_calendar','','',0,1,1497335116),(91,0,'GB28181','','','','',8400,1,1497406552),(92,91,'基本配置','gb28181','index,update,restart','','',100,1,1497406616),(93,91,'上级配置','gb28181','parents,parents_config,parents_config_post,delete,share_data_list,ajax_share_data,ajax_pshare_add,ajax_pshare_data,ajax_pshare_del,ajax_pshare_del_check','','',0,1,1497408982),(94,91,'下级配置','gb28181','childs,childs_config,childs_config_post,childs_delete','','',0,1,1497421547),(97,61,'控制','Camera','camera_action,getinfo','','',0,1,1497610156),(98,91,'网络配置','gb28181','network,network_info,network_post,network_delete','','',90,1,1497950417),(99,91,'下级在线设备','gb28181','online','','',0,1,1497954096),(100,61,'修改','Camera','save,get_scan,get_allchsbytree,update','','',0,1,1498124653),(101,61,'修改ID','Camera','update_id','','',0,1,1498130119),(102,44,'系统核心计数','system','corestatistics,getcoreinfo','','',0,1,1498388486),(103,44,'资源使用','system','resorces,get_resorces','','',0,1,1498391877),(104,44,'系统工具','system','tooler,tooler_post','','',0,1,1498392436),(105,61,'GB28181输出','Camera','GB28181Power','','',0,1,1498566252),(106,61,'UDP组播输出','Camera','UDPPower','','',0,0,1498566280),(107,61,'录像配置','Camera','RecordPower','','',0,1,1498566321),(108,61,'视频转码','Camera','TrancodePower','','',0,1,1498566358),(109,45,'厂家管理','config','Factory_management,Factory_del,Factory_insert,Factory_update','','',0,1,1517808599),(111,0,'流媒体','','','','',8450,1,1522310167),(112,44,'我的设备','system','my_equipment,my_equipment_update','','',0,1,1522981162),(113,111,'基本配置','Media','index,update,gb28181_update','','',0,1,1523039206),(114,111,'VTS 网络','Media','ets,ets_info,ets_post,ets_delete','','',0,1,1523039217),(115,111,'RTSP网络','Media','rtsp,rtsp_info,rtsp_post,rtsp_delete','','',0,1,1523039228),(116,111,'RTMP网络','Media','rtmp,rtmp_info,rtmp_post,rtmp_delete','','',0,1,1523039247),(117,111,'HTTP-FLV/TS/AAC网络','Media','http,http_info,http_post,http_delete','','',0,1,1523039261),(118,111,'HLS网络','Media','hls,hls_info,hls_post,hls_delete,hlsconfigure_update','','',0,1,1523039271),(127,44,'LOGO','system','logo,logo_upload','','',0,1,1523102403),(128,42,'录像配置','Media','record,record_update','','',0,1,1523596445),(129,42,'磁盘设置','driver','base','','',0,1,1534310114),(130,42,'本地磁盘','driver','local','','',0,1,1534310159),(131,42,'扩展磁盘','driver','external,add_external','','',0,1,1534310182),(132,42,'网络磁盘','driver','network','','',0,1,1534310204);
/*!40000 ALTER TABLE `ly_sys_navi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_role`
--

DROP TABLE IF EXISTS `ly_sys_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_role` (
  `role_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `role_name` varchar(60) NOT NULL COMMENT '角色名称',
  `insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='moa系统权限角色表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_role`
--

LOCK TABLES `ly_sys_role` WRITE;
/*!40000 ALTER TABLE `ly_sys_role` DISABLE KEYS */;
INSERT INTO `ly_sys_role` VALUES (1,'超级管理员',1432224000),(2,'管理员',1489547395),(3,'视频管理',1499078783);
/*!40000 ALTER TABLE `ly_sys_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_role_access`
--

DROP TABLE IF EXISTS `ly_sys_role_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_role_access` (
  `acce_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '关键角色id',
  `navi_id` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  PRIMARY KEY (`acce_id`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8 COMMENT='moa系统权限角色明细表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_role_access`
--

LOCK TABLES `ly_sys_role_access` WRITE;
/*!40000 ALTER TABLE `ly_sys_role_access` DISABLE KEYS */;
INSERT INTO `ly_sys_role_access` VALUES (1,2,2),(2,2,6),(3,2,8),(4,2,20),(5,2,29),(6,2,7),(7,2,3),(8,2,10),(9,2,12),(10,2,13),(11,2,14),(12,2,19),(13,2,11),(14,2,4),(15,2,15),(16,2,16),(17,2,17),(18,2,18),(19,2,46),(21,2,48),(22,2,49),(23,2,50),(24,2,51),(25,2,52),(26,2,53),(27,2,54),(28,2,61),(29,2,62),(30,2,119),(38,2,81),(39,2,82),(42,2,88),(43,2,93),(44,2,94),(45,2,95),(46,2,96),(48,2,98),(49,2,99),(50,2,100),(52,2,102),(53,2,104),(54,2,106),(55,2,107),(56,2,108),(57,2,109),(58,2,110),(59,2,111),(60,2,112),(61,2,113),(62,2,114),(63,2,115),(64,2,116),(65,2,117),(66,2,39),(67,2,40),(70,2,91),(71,2,66),(72,2,84),(73,2,89),(74,2,90),(75,2,97),(76,2,41),(77,2,56),(78,2,57),(79,2,58),(80,2,59),(81,2,60),(83,2,43),(84,2,83),(85,2,44),(86,2,92),(87,2,103),(88,2,63),(89,2,65),(90,2,101),(91,2,105),(92,3,4),(93,3,15),(94,3,16),(95,3,17),(96,3,18),(97,3,39),(98,3,46),(99,3,81),(100,3,82),(101,3,84),(102,3,88),(103,3,89),(104,3,90),(105,3,40),(106,3,61),(107,3,62),(108,3,63),(109,3,65),(110,3,66),(111,3,97),(112,3,100),(113,3,101),(114,3,105),(115,3,106),(116,3,107),(117,3,108),(118,3,41),(119,3,56),(120,3,57),(121,3,58),(122,3,59),(123,3,60),(124,3,42),(125,3,91),(126,3,99),(127,2,1),(128,2,5),(129,2,22),(130,2,23),(131,2,24),(132,2,25),(133,2,26),(134,2,27),(135,2,28),(136,2,31),(137,2,36),(138,2,30),(139,2,32),(140,2,33),(141,2,34),(142,2,35),(143,2,37),(144,2,38),(145,2,127),(146,2,45),(147,2,67),(148,2,68),(149,2,69),(150,2,70),(151,2,71),(152,2,72),(153,2,73),(154,2,74),(155,2,75),(156,2,76),(157,2,77),(158,2,78),(159,2,79),(160,2,80);
/*!40000 ALTER TABLE `ly_sys_role_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_role_staff`
--

DROP TABLE IF EXISTS `ly_sys_role_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_role_staff` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  `staff_id` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '员工号',
  `insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`,`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='员工角色关系表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_role_staff`
--

LOCK TABLES `ly_sys_role_staff` WRITE;
/*!40000 ALTER TABLE `ly_sys_role_staff` DISABLE KEYS */;
INSERT INTO `ly_sys_role_staff` VALUES (1,1,10000,1432224000),(2,2,10001,1489547566),(4,3,10003,1499227801);
/*!40000 ALTER TABLE `ly_sys_role_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_staff`
--

DROP TABLE IF EXISTS `ly_sys_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_staff` (
  `staff_id` int(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '员工号',
  `password` varchar(32) NOT NULL COMMENT '员工登录密码',
  `username` varchar(16) NOT NULL COMMENT '用户名',
  `realname` varchar(30) NOT NULL COMMENT '真实姓名',
  `gender` enum('Male','Female','UnKnown') NOT NULL DEFAULT 'UnKnown' COMMENT '性别',
  `birth_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '员工生日',
  `qq` bigint(11) NOT NULL DEFAULT '0' COMMENT 'QQ号码',
  `email` varchar(50) NOT NULL COMMENT '电子邮箱',
  `cellphone` bigint(11) NOT NULL DEFAULT '0' COMMENT '手机号码',
  `job_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '员工状态{1在职中,2已离职,3休假中}',
  `entry_date` date NOT NULL COMMENT '入职时间',
  `delete_status` tinyint(1) unsigned DEFAULT '1' COMMENT '删除状态1 未删除，0已删除',
  `insert_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `oaui_skin` varchar(30) DEFAULT NULL COMMENT '设置使用OAdmin的皮肤名称',
  PRIMARY KEY (`staff_id`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10004 DEFAULT CHARSET=utf8 COMMENT='moa员工表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_staff`
--

LOCK TABLES `ly_sys_staff` WRITE;
/*!40000 ALTER TABLE `ly_sys_staff` DISABLE KEYS */;
INSERT INTO `ly_sys_staff` VALUES (10000,'c8837b23ff8aaa8a2dde915473ce0991','lyadmin','管理员','Male','2015-05-22',0,'',0,1,'2015-01-01',1,0,NULL),(10001,'e10adc3949ba59abbe56e057f20f883e','admin','管理员','Male','0000-00-00',0,'',0,1,'2016-08-27',1,1472294491,NULL),(10002,'e10adc3949ba59abbe56e057f20f883e','liantong','liantong','Male','0000-00-00',0,'',0,1,'2017-07-03',0,1499078863,NULL),(10003,'e10adc3949ba59abbe56e057f20f883e','test','','','0000-00-00',0,'',0,1,'2017-07-05',1,1499227801,NULL);
/*!40000 ALTER TABLE `ly_sys_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ly_sys_staff_login_log`
--

DROP TABLE IF EXISTS `ly_sys_staff_login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ly_sys_staff_login_log` (
  `login_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login_account` varchar(60) NOT NULL COMMENT '登录时使用帐号',
  `login_status` enum('Success','Fail','Unknown') NOT NULL DEFAULT 'Unknown' COMMENT '登录状态',
  `session_id` varchar(40) NOT NULL COMMENT '登录时session_id',
  `login_time` int(11) unsigned DEFAULT '0' COMMENT '登录时间',
  `login_ip` varchar(32) NOT NULL DEFAULT '0.0.0.0' COMMENT '登录ip地址',
  PRIMARY KEY (`login_id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8 COMMENT='moa系统员工登录日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ly_sys_staff_login_log`
--

LOCK TABLES `ly_sys_staff_login_log` WRITE;
/*!40000 ALTER TABLE `ly_sys_staff_login_log` DISABLE KEYS */;
INSERT INTO `ly_sys_staff_login_log` VALUES (1,'','Fail','ohm9i8cp9inu9aaj64912cf877',1543420924,'172.16.83.1'),(2,'admin','Fail','ohm9i8cp9inu9aaj64912cf877',1543421008,'172.16.83.1'),(3,'lyadmin','Success','ohm9i8cp9inu9aaj64912cf877',1543421020,'172.16.83.1'),(4,'admin','Fail','ohm9i8cp9inu9aaj64912cf877',1543421044,'172.16.83.1'),(5,'admin','Fail','ohm9i8cp9inu9aaj64912cf877',1543421053,'172.16.83.1'),(6,'admin','Fail','ohm9i8cp9inu9aaj64912cf877',1543421065,'172.16.83.1'),(7,'test','Success','ohm9i8cp9inu9aaj64912cf877',1543421077,'172.16.83.1'),(8,'admin','Fail','ohm9i8cp9inu9aaj64912cf877',1543421105,'172.16.83.1'),(9,'admin','Fail','ohm9i8cp9inu9aaj64912cf877',1543421115,'172.16.83.1'),(10,'lyadmin','Success','ohm9i8cp9inu9aaj64912cf877',1543421138,'172.16.83.1'),(11,'admin','Success','ohm9i8cp9inu9aaj64912cf877',1543421243,'172.16.83.1'),(12,'lyadmin','Success','s0e692q01qtfggk3qmsd8uuhp0',1556205150,'175.171.6.15'),(13,'lyadmin','Success','ogqth7phhej5vahhg3rt5rpgc6',1556284541,'175.171.6.15'),(14,'lyadmin','Success','srjh6s0kl9olm2p96tprmk12u1',1556335639,'175.171.6.15'),(15,'lyadmin','Success','6hj5r25tvg5rdvjnks12m72v04',1556349880,'175.171.13.204'),(16,'lyadmin','Success','2prqcurron7u8e8o1ajei265r4',1556349942,'175.171.13.204'),(17,'lyadmin','Success','levtqvpvavcc01ecvvideklur0',1556356814,'175.168.201.61'),(18,'lyadmin','Success','pu3vf5p6caip3hbn514chmi9f7',1556418695,'175.168.201.61'),(19,'lyadmin','Success','p66of4jp4n53jq8acpm77pfmm0',1556501389,'175.168.221.131'),(20,'lyadmin','Success','tuu27g6qpgb2kamaemf2l6ea22',1556506805,'175.171.13.204'),(21,'lyadmin','Success','vkfklqcfm8ja4pr11smob4b116',1556679771,'175.171.13.204'),(22,'lyadmin','Success','d36p6ls2tb40b5r1rrlcnhbtf4',1557282246,'175.168.220.10'),(23,'lyadmin','Success','5m55rspo6m9ta7iv7f5vo6srn7',1557284079,'175.168.220.10'),(24,'lyadmin','Success','9gvriqcmjn1mkm8984htgu9995',1557286833,'175.168.220.10'),(25,'lyadmin','Success','o5rkt5rvrofnrb345eg64ivvt7',1557296556,'175.168.220.10'),(26,'lyadmin','Success','opurri5rd1emve77r17qg1mp46',1557319272,'113.227.10.95'),(27,'lyadmin','Success','2vt4tp2apsrp1u7mnbpd4115i4',1557370928,'175.168.220.10'),(28,'lyadmin','Success','2vt4tp2apsrp1u7mnbpd4115i4',1557390773,'175.168.220.10'),(29,'','Fail','96ljg4c8lmts58mtnuph60ggr6',1557390903,'175.168.220.10'),(30,'lyadmin','Success','2vt4tp2apsrp1u7mnbpd4115i4',1557390968,'175.168.220.10'),(31,'lyadmin','Success','2vt4tp2apsrp1u7mnbpd4115i4',1557391045,'175.168.220.10'),(32,'lyadmin','Success','2vt4tp2apsrp1u7mnbpd4115i4',1557391169,'175.168.220.10'),(33,'lyadmin','Success','2vt4tp2apsrp1u7mnbpd4115i4',1557391293,'175.168.220.10'),(34,'lyadmin','Success','54u70v8cspr1srtorhob47ean4',1557391885,'175.168.220.10'),(35,'lyadmin','Success','54u70v8cspr1srtorhob47ean4',1557392008,'175.168.220.10'),(36,'lyadmin','Success','96ljg4c8lmts58mtnuph60ggr6',1557392797,'175.168.220.10'),(37,'lyadmin','Success','dhjnnujg8htlbcnud74d4fdrs2',1557449338,'175.168.220.10'),(38,'lyadmin','Success','kun9nfvbhvqrp0u1hvpp80s0s4',1557460951,'175.171.11.5'),(39,'lyadmin','Success','dhjnnujg8htlbcnud74d4fdrs2',1557466559,'175.168.202.191'),(40,'lyadmin','Success','dhjnnujg8htlbcnud74d4fdrs2',1557468100,'175.168.202.191'),(41,'lyadmin','Success','f71h5hcoqda8bn6fohml2d7r67',1557473277,'175.168.202.191'),(42,'admin','Success','sauu8b4dt58k15s52p6ckdoqt1',1557637574,'175.171.11.5'),(43,'admin','Success','lqmv2mjc0fn1gf33oq2v08r8c6',1557637706,'36.4.142.160'),(44,'lyadmin','Success','2gci2vfksqntkiee6898e55h73',1557708205,'175.168.202.191'),(45,'lyadmin','Success','eqoqoic5nqog39dcq3qs2d0923',1557718705,'175.168.202.191'),(46,'admin','Success','gn02iilmelu9iv4esuh03gm323',1557720493,'36.4.142.160'),(47,'lyadmin','Success','eqoqoic5nqog39dcq3qs2d0923',1557724593,'175.168.202.191'),(48,'lyadmin','Success','eqoqoic5nqog39dcq3qs2d0923',1557727369,'175.168.202.191'),(49,'lyadmin','Success','eqoqoic5nqog39dcq3qs2d0923',1557728006,'175.168.202.191'),(50,'lyadmin','Success','3ht78u3d7fehkqodmd9rf28oi4',1557794766,'175.168.202.191'),(51,'lyadmin','Success','3ht78u3d7fehkqodmd9rf28oi4',1557812310,'175.168.223.208'),(52,'lyadmin','Success','3ht78u3d7fehkqodmd9rf28oi4',1557812603,'175.168.223.208'),(53,'lyadmin','Fail','3ht78u3d7fehkqodmd9rf28oi4',1557812619,'175.168.223.208'),(54,'lyadmin','Success','3ht78u3d7fehkqodmd9rf28oi4',1557812626,'175.168.223.208'),(55,'lyadmin','Success','3ht78u3d7fehkqodmd9rf28oi4',1557812691,'175.168.223.208'),(56,'lyadmin','Success','9hi9ocfncqbonm6ogvuquftj16',1557821701,'175.168.223.208'),(57,'','Fail','9hi9ocfncqbonm6ogvuquftj16',1557821709,'175.168.223.208'),(58,'','Fail','9hi9ocfncqbonm6ogvuquftj16',1557821715,'175.168.223.208'),(59,'lyadmin','Success','g2n8sm8hq5dtujgkkedq8rv8g3',1557882239,'175.168.223.208'),(60,'lyadmin','Success','0heju1ea4vna9g0tl11r6eoed4',1557888016,'175.162.240.25'),(61,'lyadmin','Success','vsd2h9trcvr7o91m1l9urmg9h4',1557888862,'175.162.240.232'),(62,'lyadmin','Success','klhmokaov0imfkesq95gff6q46',1557889370,'175.162.240.232'),(63,'admin','Success','3p6a6uim5m3395h434u5418tp0',1557889454,'175.162.240.232'),(64,'admin','Success','g8haa7ma23otvo55c5ssro2lt0',1557889532,'58.210.169.162'),(65,'','Fail','9ns0k09fjih1b1oii101e3o3n1',1557889532,'117.50.2.17'),(66,'','Fail','o92fj61g4d5civk08d297tu473',1557889533,'117.50.2.17'),(67,'','Fail','rnselmrkd38j9ug38d9tbv4cs4',1557889533,'117.50.2.17'),(68,'','Fail','q7d6t1j3e26u1jnq1q4no4ba35',1557889533,'117.50.2.17'),(69,'','Fail','u4p21oqbcmija7o9lkamqug1l6',1557889533,'117.50.2.17'),(70,'','Fail','e298qu6ssnte2brtbckpoi64u0',1557889533,'117.50.2.17'),(71,'','Fail','6idcsgkmb2b6lksak99pjq3700',1557889598,'101.91.60.110'),(72,'lyadmin','Success','eqoqoic5nqog39dcq3qs2d0923',1557890790,'175.168.223.208'),(73,'lyadmin','Success','9hi9ocfncqbonm6ogvuquftj16',1557890837,'175.168.223.208'),(74,'','Fail','hsl8mfsm44jv6mu0ceabn9qqf5',1557890899,'58.246.221.162'),(75,'admin','Success','54e3qc0qum0kg9ekmff3tspa67',1557891645,'58.210.169.162'),(76,'lyadmin','Success','9hi9ocfncqbonm6ogvuquftj16',1557898064,'175.168.223.208'),(77,'admin','Success','qqcjlojhqmai90h1fqdek3i6f0',1557905164,'175.162.240.133'),(78,'admin','Success','qqcjlojhqmai90h1fqdek3i6f0',1557908888,'175.162.240.232'),(79,'lyadmin','Success','21228t7erqooj3ks92jvfeoaj2',1557966995,'175.168.223.208'),(80,'admin','Success','5mdqmntcojkodka6kfndv0c5l2',1557967681,'175.162.240.25'),(81,'admin','Success','129akdjum7jnuupdnjoqi8v800',1557969000,'175.162.240.133'),(82,'admin','Success','8c9dk4u1dq3d38bgn4b79ealo2',1557982643,'175.162.240.25'),(83,'admin','Success','fdoaeqc5lnd73ga26293v1a2a4',1558057493,'113.226.198.37'),(84,'lyadmin','Success','poo1fl0mrjodtdaob6b5fqn8p7',1558365468,'113.227.20.99'),(85,'lyadmin','Success','567gr6jj5h3s2ctnmu4g86ftg6',1558421966,'175.168.212.222'),(86,'lyadmin','Success','qt6ifpukge7epmek65ujn5gop4',1558444408,'113.227.30.19'),(87,'lyadmin','Success','75v2ql7v80qj8plsoi607e6vq6',1572005410,'192.168.1.188');
/*!40000 ALTER TABLE `ly_sys_staff_login_log` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-25 20:47:52
