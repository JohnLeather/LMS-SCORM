-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: LMS
-- ------------------------------------------------------
-- Server version	5.7.31

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
-- Table structure for table `CMIResults`
--

DROP TABLE IF EXISTS `CMIResults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CMIResults` (
  `userID` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL,
  `moduleID` int(11) DEFAULT NULL,
  `SCORMCMIData` text,
  `startedDate` varchar(32) DEFAULT NULL,
  `status` varchar(32) DEFAULT NULL,
  `completedDate` varchar(32) DEFAULT NULL,
  `score` varchar(32) DEFAULT NULL,
  `scoreRaw` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CMIResults`
--

LOCK TABLES `CMIResults` WRITE;
/*!40000 ALTER TABLE `CMIResults` DISABLE KEYS */;
/*!40000 ALTER TABLE `CMIResults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commonPasswords`
--

DROP TABLE IF EXISTS `commonPasswords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commonPasswords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2136 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commonPasswords`
--

LOCK TABLES `commonPasswords` WRITE;
/*!40000 ALTER TABLE `commonPasswords` DISABLE KEYS */;
INSERT INTO `commonPasswords` VALUES (1,'123456'),(2,'password'),(3,'12345678'),(4,'qwerty'),(5,'123456789'),(6,'12345'),(7,'1234'),(8,'111111'),(9,'1234567'),(10,'dragon'),(11,'123123'),(12,'baseball'),(13,'abc123'),(14,'football'),(15,'monkey'),(16,'letmein'),(17,'696969'),(18,'shadow'),(19,'master'),(20,'666666'),(21,'qwertyuiop'),(22,'123321'),(23,'mustang'),(24,'1234567890'),(25,'michael'),(26,'654321'),(27,'pussy'),(28,'superman'),(29,'1qaz2wsx'),(30,'7777777'),(31,'fuckyou'),(32,'121212'),(33,'000000'),(34,'qazwsx'),(35,'123qwe'),(36,'killer'),(37,'trustno1'),(38,'jordan'),(39,'jennifer'),(40,'zxcvbnm'),(41,'asdfgh'),(42,'hunter'),(43,'buster'),(44,'soccer'),(45,'harley'),(46,'batman'),(47,'andrew'),(48,'tigger'),(49,'sunshine'),(50,'iloveyou'),(51,'fuckme'),(52,'2000'),(53,'charlie'),(54,'robert'),(55,'thomas'),(56,'hockey'),(57,'ranger'),(58,'daniel'),(59,'starwars'),(60,'klaster'),(61,'112233'),(62,'george'),(63,'asshole'),(64,'computer'),(65,'michelle'),(66,'jessica'),(67,'pepper'),(68,'1111'),(69,'zxcvbn'),(70,'555555'),(71,'11111111'),(72,'131313'),(73,'freedom'),(74,'777777'),(75,'pass'),(76,'fuck'),(77,'maggie'),(78,'159753'),(79,'aaaaaa'),(80,'ginger'),(81,'princess'),(82,'joshua'),(83,'cheese'),(84,'amanda'),(85,'summer'),(86,'love'),(87,'ashley'),(88,'6969'),(89,'nicole'),(90,'chelsea'),(91,'biteme'),(92,'matthew'),(93,'access'),(94,'yankees'),(95,'987654321'),(96,'dallas'),(97,'austin'),(98,'thunder'),(99,'taylor'),(100,'matrix'),(101,'william'),(102,'corvette'),(103,'hello'),(104,'martin'),(105,'heather'),(106,'secret'),(107,'fucker'),(108,'merlin'),(109,'diamond'),(110,'1234qwer'),(111,'gfhjkm'),(112,'hammer'),(113,'silver'),(114,'222222'),(115,'88888888'),(116,'anthony'),(117,'justin'),(118,'test'),(119,'bailey'),(120,'q1w2e3r4t5'),(121,'patrick'),(122,'internet'),(123,'scooter'),(124,'orange'),(125,'11111'),(126,'golfer'),(127,'cookie'),(128,'richard'),(129,'samantha'),(130,'bigdog'),(131,'guitar'),(132,'jackson'),(133,'whatever'),(134,'mickey'),(135,'chicken'),(136,'sparky'),(137,'snoopy'),(138,'maverick'),(139,'phoenix'),(140,'camaro'),(141,'sexy'),(142,'peanut'),(143,'morgan'),(144,'welcome'),(145,'falcon'),(146,'cowboy'),(147,'ferrari'),(148,'samsung'),(149,'andrea'),(150,'smokey'),(151,'steelers'),(152,'joseph'),(153,'mercedes'),(154,'dakota'),(155,'arsenal'),(156,'eagles'),(157,'melissa'),(158,'boomer'),(159,'booboo'),(160,'spider'),(161,'nascar'),(162,'monster'),(163,'tigers'),(164,'yellow'),(165,'xxxxxx'),(166,'123123123'),(167,'gateway'),(168,'marina'),(169,'diablo'),(170,'bulldog'),(171,'qwer1234'),(172,'compaq'),(173,'purple'),(174,'hardcore'),(175,'banana'),(176,'junior'),(177,'hannah'),(178,'123654'),(179,'porsche'),(180,'lakers'),(181,'iceman'),(182,'money'),(183,'cowboys'),(184,'987654'),(185,'london'),(186,'tennis'),(187,'999999'),(188,'ncc1701'),(189,'coffee'),(190,'scooby'),(191,'0000'),(192,'miller'),(193,'boston'),(194,'q1w2e3r4'),(195,'fuckoff'),(196,'brandon'),(197,'yamaha'),(198,'chester'),(199,'mother'),(200,'forever'),(201,'johnny'),(202,'edward'),(203,'333333'),(204,'oliver'),(205,'redsox'),(206,'player'),(207,'nikita'),(208,'knight'),(209,'fender'),(210,'barney'),(211,'midnight'),(212,'please'),(213,'brandy'),(214,'chicago'),(215,'badboy'),(216,'iwantu'),(217,'slayer'),(218,'rangers'),(219,'charles'),(220,'angel'),(221,'flower'),(222,'bigdaddy'),(223,'rabbit'),(224,'wizard'),(225,'bigdick'),(226,'jasper'),(227,'enter'),(228,'rachel'),(229,'chris'),(230,'steven'),(231,'winner'),(232,'adidas'),(233,'victoria'),(234,'natasha'),(235,'1q2w3e4r'),(236,'jasmine'),(237,'winter'),(238,'prince'),(239,'panties'),(240,'marine'),(241,'ghbdtn'),(242,'fishing'),(243,'cocacola'),(244,'casper'),(245,'james'),(246,'232323'),(247,'raiders'),(248,'888888'),(249,'marlboro'),(250,'gandalf'),(251,'asdfasdf'),(252,'crystal'),(253,'87654321'),(254,'12344321'),(255,'sexsex'),(256,'golden'),(257,'blowme'),(258,'bigtits'),(259,'8675309'),(260,'panther'),(261,'lauren'),(262,'angela'),(263,'bitch'),(264,'spanky'),(265,'thx1138'),(266,'angels'),(267,'madison'),(268,'winston'),(269,'shannon'),(270,'mike'),(271,'toyota'),(272,'blowjob'),(273,'jordan23'),(274,'canada'),(275,'sophie'),(276,'Password'),(277,'apples'),(278,'dick'),(279,'tiger'),(280,'razz'),(281,'123abc'),(282,'pokemon'),(283,'qazxsw'),(284,'55555'),(285,'qwaszx'),(286,'muffin'),(287,'johnson'),(288,'murphy'),(289,'cooper'),(290,'jonathan'),(291,'liverpoo'),(292,'david'),(293,'danielle'),(294,'159357'),(295,'jackie'),(296,'1990'),(297,'123456a'),(298,'789456'),(299,'turtle'),(300,'horny'),(301,'abcd1234'),(302,'scorpion'),(303,'qazwsxedc'),(304,'101010'),(305,'butter'),(306,'carlos'),(307,'password1'),(308,'dennis'),(309,'slipknot'),(310,'qwerty123'),(311,'booger'),(312,'asdf'),(313,'1991'),(314,'black'),(315,'startrek'),(316,'12341234'),(317,'cameron'),(318,'newyork'),(319,'rainbow'),(320,'nathan'),(321,'john'),(322,'1992'),(323,'rocket'),(324,'viking'),(325,'redskins'),(326,'butthead'),(327,'asdfghjkl'),(328,'1212'),(329,'sierra'),(330,'peaches'),(331,'gemini'),(332,'doctor'),(333,'wilson'),(334,'sandra'),(335,'helpme'),(336,'qwertyui'),(337,'victor'),(338,'florida'),(339,'dolphin'),(340,'pookie'),(341,'captain'),(342,'tucker'),(343,'blue'),(344,'liverpool'),(345,'theman'),(346,'bandit'),(347,'dolphins'),(348,'maddog'),(349,'packers'),(350,'jaguar'),(351,'lovers'),(352,'nicholas'),(353,'united'),(354,'tiffany'),(355,'maxwell'),(356,'zzzzzz'),(357,'nirvana'),(358,'jeremy'),(359,'suckit'),(360,'stupid'),(361,'porn'),(362,'monica'),(363,'elephant'),(364,'giants'),(365,'jackass'),(366,'hotdog'),(367,'rosebud'),(368,'success'),(369,'debbie'),(370,'mountain'),(371,'444444'),(372,'xxxxxxxx'),(373,'warrior'),(374,'1q2w3e4r5t'),(375,'q1w2e3'),(376,'123456q'),(377,'albert'),(378,'metallic'),(379,'lucky'),(380,'azerty'),(381,'7777'),(382,'shithead'),(383,'alex'),(384,'bond007'),(385,'alexis'),(386,'1111111'),(387,'samson'),(388,'5150'),(389,'willie'),(390,'scorpio'),(391,'bonnie'),(392,'gators'),(393,'benjamin'),(394,'voodoo'),(395,'driver'),(396,'dexter'),(397,'2112'),(398,'jason'),(399,'calvin'),(400,'freddy'),(401,'212121'),(402,'creative'),(403,'12345a'),(404,'sydney'),(405,'rush2112'),(406,'1989'),(407,'asdfghjk'),(408,'red123'),(409,'bubba'),(410,'4815162342'),(411,'passw0rd'),(412,'trouble'),(413,'gunner'),(414,'happy'),(415,'fucking'),(416,'gordon'),(417,'legend'),(418,'jessie'),(419,'stella'),(420,'qwert'),(421,'eminem'),(422,'arthur'),(423,'apple'),(424,'nissan'),(425,'bullshit'),(426,'bear'),(427,'america'),(428,'1qazxsw2'),(429,'nothing'),(430,'parker'),(431,'4444'),(432,'rebecca'),(433,'qweqwe'),(434,'garfield'),(435,'01012011'),(436,'beavis'),(437,'69696969'),(438,'jack'),(439,'asdasd'),(440,'december'),(441,'2222'),(442,'102030'),(443,'252525'),(444,'11223344'),(445,'magic'),(446,'apollo'),(447,'skippy'),(448,'315475'),(449,'girls'),(450,'kitten'),(451,'golf'),(452,'copper'),(453,'braves'),(454,'shelby'),(455,'godzilla'),(456,'beaver'),(457,'fred'),(458,'tomcat'),(459,'august'),(460,'buddy'),(461,'airborne'),(462,'1993'),(463,'1988'),(464,'lifehack'),(465,'qqqqqq'),(466,'brooklyn'),(467,'animal'),(468,'platinum'),(469,'phantom'),(470,'online'),(471,'xavier'),(472,'darkness'),(473,'blink182'),(474,'power'),(475,'fish'),(476,'green'),(477,'789456123'),(478,'voyager'),(479,'police'),(480,'travis'),(481,'12qwaszx'),(482,'heaven'),(483,'snowball'),(484,'lover'),(485,'abcdef'),(486,'00000'),(487,'pakistan'),(488,'007007'),(489,'walter'),(490,'playboy'),(491,'blazer'),(492,'cricket'),(493,'sniper'),(494,'hooters'),(495,'donkey'),(496,'willow'),(497,'loveme'),(498,'saturn'),(499,'therock'),(500,'redwings'),(501,'bigboy'),(502,'pumpkin'),(503,'trinity'),(504,'williams'),(505,'tits'),(506,'nintendo'),(507,'digital'),(508,'destiny'),(509,'topgun'),(510,'runner'),(511,'marvin'),(512,'guinness'),(513,'chance'),(514,'bubbles'),(515,'testing'),(516,'fire'),(517,'november'),(518,'minecraft'),(519,'asdf1234'),(520,'lasvegas'),(521,'sergey'),(522,'broncos'),(523,'cartman'),(524,'private'),(525,'celtic'),(526,'birdie'),(527,'little'),(528,'cassie'),(529,'babygirl'),(530,'donald'),(531,'beatles'),(532,'1313'),(533,'dickhead'),(534,'family'),(535,'12121212'),(536,'school'),(537,'louise'),(538,'gabriel'),(539,'eclipse'),(540,'fluffy'),(541,'147258369'),(542,'lol123'),(543,'explorer'),(544,'beer'),(545,'nelson'),(546,'flyers'),(547,'spencer'),(548,'scott'),(549,'lovely'),(550,'gibson'),(551,'doggie'),(552,'cherry'),(553,'andrey'),(554,'snickers'),(555,'buffalo'),(556,'pantera'),(557,'metallica'),(558,'member'),(559,'carter'),(560,'qwertyu'),(561,'peter'),(562,'alexande'),(563,'steve'),(564,'bronco'),(565,'paradise'),(566,'goober'),(567,'5555'),(568,'samuel'),(569,'montana'),(570,'mexico'),(571,'dreams'),(572,'michigan'),(573,'cock'),(574,'carolina'),(575,'yankee'),(576,'friends'),(577,'magnum'),(578,'surfer'),(579,'poopoo'),(580,'maximus'),(581,'genius'),(582,'cool'),(583,'vampire'),(584,'lacrosse'),(585,'asd123'),(586,'aaaa'),(587,'christin'),(588,'kimberly'),(589,'speedy'),(590,'sharon'),(591,'carmen'),(592,'111222'),(593,'kristina'),(594,'sammy'),(595,'racing'),(596,'ou812'),(597,'sabrina'),(598,'horses'),(599,'0987654321'),(600,'qwerty1'),(601,'pimpin'),(602,'baby'),(603,'stalker'),(604,'enigma'),(605,'147147'),(606,'star'),(607,'poohbear'),(608,'boobies'),(609,'147258'),(610,'simple'),(611,'bollocks'),(612,'12345q'),(613,'marcus'),(614,'brian'),(615,'1987'),(616,'qweasdzxc'),(617,'drowssap'),(618,'hahaha'),(619,'caroline'),(620,'barbara'),(621,'dave'),(622,'viper'),(623,'drummer'),(624,'action'),(625,'einstein'),(626,'bitches'),(627,'genesis'),(628,'hello1'),(629,'scotty'),(630,'friend'),(631,'forest'),(632,'010203'),(633,'hotrod'),(634,'google'),(635,'vanessa'),(636,'spitfire'),(637,'badger'),(638,'maryjane'),(639,'friday'),(640,'alaska'),(641,'1232323q'),(642,'tester'),(643,'jester'),(644,'jake'),(645,'champion'),(646,'billy'),(647,'147852'),(648,'rock'),(649,'hawaii'),(650,'badass'),(651,'chevy'),(652,'420420'),(653,'walker'),(654,'stephen'),(655,'eagle1'),(656,'bill'),(657,'1986'),(658,'october'),(659,'gregory'),(660,'svetlana'),(661,'pamela'),(662,'1984'),(663,'music'),(664,'shorty'),(665,'westside'),(666,'stanley'),(667,'diesel'),(668,'courtney'),(669,'242424'),(670,'kevin'),(671,'porno'),(672,'hitman'),(673,'boobs'),(674,'mark'),(675,'12345qwert'),(676,'reddog'),(677,'frank'),(678,'qwe123'),(679,'popcorn'),(680,'patricia'),(681,'aaaaaaaa'),(682,'1969'),(683,'teresa'),(684,'mozart'),(685,'buddha'),(686,'anderson'),(687,'paul'),(688,'melanie'),(689,'abcdefg'),(690,'security'),(691,'lucky1'),(692,'lizard'),(693,'denise'),(694,'3333'),(695,'a12345'),(696,'123789'),(697,'ruslan'),(698,'stargate'),(699,'simpsons'),(700,'scarface'),(701,'eagle'),(702,'123456789a'),(703,'thumper'),(704,'olivia'),(705,'naruto'),(706,'1234554321'),(707,'general'),(708,'cherokee'),(709,'a123456'),(710,'vincent'),(711,'Usuckballz1'),(712,'spooky'),(713,'qweasd'),(714,'cumshot'),(715,'free'),(716,'frankie'),(717,'douglas'),(718,'death'),(719,'1980'),(720,'loveyou'),(721,'kitty'),(722,'kelly'),(723,'veronica'),(724,'suzuki'),(725,'semperfi'),(726,'penguin'),(727,'mercury'),(728,'liberty'),(729,'spirit'),(730,'scotland'),(731,'natalie'),(732,'marley'),(733,'vikings'),(734,'system'),(735,'sucker'),(736,'king'),(737,'allison'),(738,'marshall'),(739,'1979'),(740,'098765'),(741,'qwerty12'),(742,'hummer'),(743,'adrian'),(744,'1985'),(745,'vfhbyf'),(746,'sandman'),(747,'rocky'),(748,'leslie'),(749,'antonio'),(750,'98765432'),(751,'4321'),(752,'softball'),(753,'passion'),(754,'mnbvcxz'),(755,'bastard'),(756,'passport'),(757,'horney'),(758,'rascal'),(759,'howard'),(760,'franklin'),(761,'bigred'),(762,'assman'),(763,'alexander'),(764,'homer'),(765,'redrum'),(766,'jupiter'),(767,'claudia'),(768,'55555555'),(769,'141414'),(770,'zaq12wsx'),(771,'shit'),(772,'patches'),(773,'nigger'),(774,'cunt'),(775,'raider'),(776,'infinity'),(777,'andre'),(778,'54321'),(779,'galore'),(780,'college'),(781,'russia'),(782,'kawasaki'),(783,'bishop'),(784,'77777777'),(785,'vladimir'),(786,'money1'),(787,'freeuser'),(788,'wildcats'),(789,'francis'),(790,'disney'),(791,'budlight'),(792,'brittany'),(793,'1994'),(794,'00000000'),(795,'sweet'),(796,'oksana'),(797,'honda'),(798,'domino'),(799,'bulldogs'),(800,'brutus'),(801,'swordfis'),(802,'norman'),(803,'monday'),(804,'jimmy'),(805,'ironman'),(806,'ford'),(807,'fantasy'),(808,'9999'),(809,'7654321'),(810,'PASSWORD'),(811,'hentai'),(812,'duncan'),(813,'cougar'),(814,'1977'),(815,'jeffrey'),(816,'house'),(817,'dancer'),(818,'brooke'),(819,'timothy'),(820,'super'),(821,'marines'),(822,'justice'),(823,'digger'),(824,'connor'),(825,'patriots'),(826,'karina'),(827,'202020'),(828,'molly'),(829,'everton'),(830,'tinker'),(831,'alicia'),(832,'rasdzv3'),(833,'poop'),(834,'pearljam'),(835,'stinky'),(836,'naughty'),(837,'colorado'),(838,'123123a'),(839,'water'),(840,'test123'),(841,'ncc1701d'),(842,'motorola'),(843,'ireland'),(844,'asdfg'),(845,'slut'),(846,'matt'),(847,'houston'),(848,'boogie'),(849,'zombie'),(850,'accord'),(851,'vision'),(852,'bradley'),(853,'reggie'),(854,'kermit'),(855,'froggy'),(856,'ducati'),(857,'avalon'),(858,'6666'),(859,'9379992'),(860,'sarah'),(861,'saints'),(862,'logitech'),(863,'chopper'),(864,'852456'),(865,'simpson'),(866,'madonna'),(867,'juventus'),(868,'claire'),(869,'159951'),(870,'zachary'),(871,'yfnfif'),(872,'wolverin'),(873,'warcraft'),(874,'hello123'),(875,'extreme'),(876,'penis'),(877,'peekaboo'),(878,'fireman'),(879,'eugene'),(880,'brenda'),(881,'123654789'),(882,'russell'),(883,'panthers'),(884,'georgia'),(885,'smith'),(886,'skyline'),(887,'jesus'),(888,'elizabet'),(889,'spiderma'),(890,'smooth'),(891,'pirate'),(892,'empire'),(893,'bullet'),(894,'8888'),(895,'virginia'),(896,'valentin'),(897,'psycho'),(898,'predator'),(899,'arizona'),(900,'134679'),(901,'mitchell'),(902,'alyssa'),(903,'vegeta'),(904,'titanic'),(905,'christ'),(906,'goblue'),(907,'fylhtq'),(908,'wolf'),(909,'mmmmmm'),(910,'kirill'),(911,'indian'),(912,'hiphop'),(913,'baxter'),(914,'awesome'),(915,'people'),(916,'danger'),(917,'roland'),(918,'mookie'),(919,'741852963'),(920,'1111111111'),(921,'dreamer'),(922,'bambam'),(923,'arnold'),(924,'1981'),(925,'skipper'),(926,'serega'),(927,'rolltide'),(928,'elvis'),(929,'changeme'),(930,'simon'),(931,'1q2w3e'),(932,'lovelove'),(933,'fktrcfylh'),(934,'denver'),(935,'tommy'),(936,'mine'),(937,'loverboy'),(938,'hobbes'),(939,'happy1'),(940,'alison'),(941,'nemesis'),(942,'chevelle'),(943,'cardinal'),(944,'burton'),(945,'wanker'),(946,'picard'),(947,'151515'),(948,'tweety'),(949,'michael1'),(950,'147852369'),(951,'12312'),(952,'xxxx'),(953,'windows'),(954,'turkey'),(955,'456789'),(956,'1974'),(957,'vfrcbv'),(958,'sublime'),(959,'1975'),(960,'galina'),(961,'bobby'),(962,'newport'),(963,'manutd'),(964,'daddy'),(965,'american'),(966,'alexandr'),(967,'1966'),(968,'victory'),(969,'rooster'),(970,'qqq111'),(971,'madmax'),(972,'electric'),(973,'bigcock'),(974,'a1b2c3'),(975,'wolfpack'),(976,'spring'),(977,'phpbb'),(978,'lalala'),(979,'suckme'),(980,'spiderman'),(981,'eric'),(982,'darkside'),(983,'classic'),(984,'raptor'),(985,'123456789q'),(986,'hendrix'),(987,'1982'),(988,'wombat'),(989,'avatar'),(990,'alpha'),(991,'zxc123'),(992,'crazy'),(993,'hard'),(994,'england'),(995,'brazil'),(996,'1978'),(997,'01011980'),(998,'wildcat'),(999,'polina'),(1000,'freepass'),(1001,'carrie'),(1002,'99999999'),(1003,'qaz123'),(1004,'holiday'),(1005,'fyfcnfcbz'),(1006,'brother'),(1007,'taurus'),(1008,'shaggy'),(1009,'raymond'),(1010,'maksim'),(1011,'gundam'),(1012,'admin'),(1013,'vagina'),(1014,'pretty'),(1015,'pickle'),(1016,'good'),(1017,'chronic'),(1018,'alabama'),(1019,'airplane'),(1020,'22222222'),(1021,'1976'),(1022,'1029384756'),(1023,'01011'),(1024,'time'),(1025,'sports'),(1026,'ronaldo'),(1027,'pandora'),(1028,'cheyenne'),(1029,'caesar'),(1030,'billybob'),(1031,'bigman'),(1032,'1968'),(1033,'124578'),(1034,'snowman'),(1035,'lawrence'),(1036,'kenneth'),(1037,'horse'),(1038,'france'),(1039,'bondage'),(1040,'perfect'),(1041,'kristen'),(1042,'devils'),(1043,'alpha1'),(1044,'pussycat'),(1045,'kodiak'),(1046,'flowers'),(1047,'1973'),(1048,'01012000'),(1049,'leather'),(1050,'amber'),(1051,'gracie'),(1052,'chocolat'),(1053,'bubba1'),(1054,'catch22'),(1055,'business'),(1056,'2323'),(1057,'1983'),(1058,'cjkysirj'),(1059,'1972'),(1060,'123qweasd'),(1061,'ytrewq'),(1062,'wolves'),(1063,'stingray'),(1064,'ssssss'),(1065,'serenity'),(1066,'ronald'),(1067,'greenday'),(1068,'135790'),(1069,'010101'),(1070,'tiger1'),(1071,'sunset'),(1072,'charlie1'),(1073,'berlin'),(1074,'bbbbbb'),(1075,'171717'),(1076,'panzer'),(1077,'lincoln'),(1078,'katana'),(1079,'firebird'),(1080,'blizzard'),(1081,'a1b2c3d4'),(1082,'white'),(1083,'sterling'),(1084,'redhead'),(1085,'password123'),(1086,'candy'),(1087,'anna'),(1088,'142536'),(1089,'sasha'),(1090,'pyramid'),(1091,'outlaw'),(1092,'hercules'),(1093,'garcia'),(1094,'454545'),(1095,'trevor'),(1096,'teens'),(1097,'maria'),(1098,'kramer'),(1099,'girl'),(1100,'popeye'),(1101,'pontiac'),(1102,'hardon'),(1103,'dude'),(1104,'aaaaa'),(1105,'323232'),(1106,'tarheels'),(1107,'honey'),(1108,'cobra'),(1109,'buddy1'),(1110,'remember'),(1111,'lickme'),(1112,'detroit'),(1113,'clinton'),(1114,'basketball'),(1115,'zeppelin'),(1116,'whynot'),(1117,'swimming'),(1118,'strike'),(1119,'service'),(1120,'pavilion'),(1121,'michele'),(1122,'engineer'),(1123,'dodgers'),(1124,'britney'),(1125,'bobafett'),(1126,'adam'),(1127,'741852'),(1128,'21122112'),(1129,'xxxxx'),(1130,'robbie'),(1131,'miranda'),(1132,'456123'),(1133,'future'),(1134,'darkstar'),(1135,'icecream'),(1136,'connie'),(1137,'1970'),(1138,'jones'),(1139,'hellfire'),(1140,'fisher'),(1141,'fireball'),(1142,'apache'),(1143,'fuckit'),(1144,'blonde'),(1145,'bigmac'),(1146,'abcd'),(1147,'morris'),(1148,'angel1'),(1149,'666999'),(1150,'321321'),(1151,'simone'),(1152,'rockstar'),(1153,'flash'),(1154,'defender'),(1155,'1967'),(1156,'wallace'),(1157,'trooper'),(1158,'oscar'),(1159,'norton'),(1160,'casino'),(1161,'cancer'),(1162,'beauty'),(1163,'weasel'),(1164,'savage'),(1165,'raven'),(1166,'harvey'),(1167,'bowling'),(1168,'246810'),(1169,'wutang'),(1170,'theone'),(1171,'swordfish'),(1172,'stewart'),(1173,'airforce'),(1174,'abcdefgh'),(1175,'nipples'),(1176,'nastya'),(1177,'jenny'),(1178,'hacker'),(1179,'753951'),(1180,'amateur'),(1181,'viktor'),(1182,'srinivas'),(1183,'maxima'),(1184,'lennon'),(1185,'freddie'),(1186,'bluebird'),(1187,'qazqaz'),(1188,'presario'),(1189,'pimp'),(1190,'packard'),(1191,'mouse'),(1192,'looking'),(1193,'lesbian'),(1194,'jeff'),(1195,'cheryl'),(1196,'2001'),(1197,'wrangler'),(1198,'sandy'),(1199,'machine'),(1200,'lights'),(1201,'eatme'),(1202,'control'),(1203,'tattoo'),(1204,'precious'),(1205,'harrison'),(1206,'duke'),(1207,'beach'),(1208,'tornado'),(1209,'tanner'),(1210,'goldfish'),(1211,'catfish'),(1212,'openup'),(1213,'manager'),(1214,'1971'),(1215,'street'),(1216,'Soso123aljg'),(1217,'roscoe'),(1218,'paris'),(1219,'natali'),(1220,'light'),(1221,'julian'),(1222,'jerry'),(1223,'dilbert'),(1224,'dbrnjhbz'),(1225,'chris1'),(1226,'atlanta'),(1227,'xfiles'),(1228,'thailand'),(1229,'sailor'),(1230,'pussies'),(1231,'pervert'),(1232,'lucifer'),(1233,'longhorn'),(1234,'enjoy'),(1235,'dragons'),(1236,'young'),(1237,'target'),(1238,'elaine'),(1239,'dustin'),(1240,'123qweasdzxc'),(1241,'student'),(1242,'madman'),(1243,'lisa'),(1244,'integra'),(1245,'wordpass'),(1246,'prelude'),(1247,'newton'),(1248,'lolita'),(1249,'ladies'),(1250,'hawkeye'),(1251,'corona'),(1252,'bubble'),(1253,'31415926'),(1254,'trigger'),(1255,'spike'),(1256,'katie'),(1257,'iloveu'),(1258,'herman'),(1259,'design'),(1260,'cannon'),(1261,'999999999'),(1262,'video'),(1263,'stealth'),(1264,'shooter'),(1265,'nfnmzyf'),(1266,'hottie'),(1267,'browns'),(1268,'314159'),(1269,'trucks'),(1270,'malibu'),(1271,'bruins'),(1272,'bobcat'),(1273,'barbie'),(1274,'1964'),(1275,'orlando'),(1276,'letmein1'),(1277,'freaky'),(1278,'foobar'),(1279,'cthutq'),(1280,'baller'),(1281,'unicorn'),(1282,'scully'),(1283,'pussy1'),(1284,'potter'),(1285,'cookies'),(1286,'pppppp'),(1287,'philip'),(1288,'gogogo'),(1289,'elena'),(1290,'country'),(1291,'assassin'),(1292,'1010'),(1293,'zaqwsx'),(1294,'testtest'),(1295,'peewee'),(1296,'moose'),(1297,'microsoft'),(1298,'teacher'),(1299,'sweety'),(1300,'stefan'),(1301,'stacey'),(1302,'shotgun'),(1303,'random'),(1304,'laura'),(1305,'hooker'),(1306,'dfvgbh'),(1307,'devildog'),(1308,'chipper'),(1309,'athena'),(1310,'winnie'),(1311,'valentina'),(1312,'pegasus'),(1313,'kristin'),(1314,'fetish'),(1315,'butterfly'),(1316,'woody'),(1317,'swinger'),(1318,'seattle'),(1319,'lonewolf'),(1320,'joker'),(1321,'booty'),(1322,'babydoll'),(1323,'atlantis'),(1324,'tony'),(1325,'powers'),(1326,'polaris'),(1327,'montreal'),(1328,'angelina'),(1329,'77777'),(1330,'tickle'),(1331,'regina'),(1332,'pepsi'),(1333,'gizmo'),(1334,'express'),(1335,'dollar'),(1336,'squirt'),(1337,'shamrock'),(1338,'knicks'),(1339,'hotstuff'),(1340,'balls'),(1341,'transam'),(1342,'stinger'),(1343,'smiley'),(1344,'ryan'),(1345,'redneck'),(1346,'mistress'),(1347,'hjvfirf'),(1348,'cessna'),(1349,'bunny'),(1350,'toshiba'),(1351,'single'),(1352,'piglet'),(1353,'fucked'),(1354,'father'),(1355,'deftones'),(1356,'coyote'),(1357,'castle'),(1358,'cadillac'),(1359,'blaster'),(1360,'valerie'),(1361,'samurai'),(1362,'oicu812'),(1363,'lindsay'),(1364,'jasmin'),(1365,'james1'),(1366,'ficken'),(1367,'blahblah'),(1368,'birthday'),(1369,'1234abcd'),(1370,'01011990'),(1371,'sunday'),(1372,'manson'),(1373,'flipper'),(1374,'asdfghj'),(1375,'181818'),(1376,'wicked'),(1377,'great'),(1378,'daisy'),(1379,'babes'),(1380,'skeeter'),(1381,'reaper'),(1382,'maddie'),(1383,'cavalier'),(1384,'veronika'),(1385,'trucker'),(1386,'qazwsx123'),(1387,'mustang1'),(1388,'goldberg'),(1389,'escort'),(1390,'12345678910'),(1391,'wolfgang'),(1392,'rocks'),(1393,'mylove'),(1394,'mememe'),(1395,'lancer'),(1396,'ibanez'),(1397,'travel'),(1398,'sugar'),(1399,'snake'),(1400,'sister'),(1401,'siemens'),(1402,'savannah'),(1403,'minnie'),(1404,'leonardo'),(1405,'basketba'),(1406,'1963'),(1407,'trumpet'),(1408,'texas'),(1409,'rocky1'),(1410,'galaxy'),(1411,'cristina'),(1412,'aardvark'),(1413,'shelly'),(1414,'hotsex'),(1415,'goldie'),(1416,'fatboy'),(1417,'benson'),(1418,'321654'),(1419,'141627'),(1420,'sweetpea'),(1421,'ronnie'),(1422,'indigo'),(1423,'13131313'),(1424,'spartan'),(1425,'roberto'),(1426,'hesoyam'),(1427,'freeman'),(1428,'freedom1'),(1429,'fredfred'),(1430,'pizza'),(1431,'manchester'),(1432,'lestat'),(1433,'kathleen'),(1434,'hamilton'),(1435,'erotic'),(1436,'blabla'),(1437,'22222'),(1438,'1995'),(1439,'skater'),(1440,'pencil'),(1441,'passwor'),(1442,'larisa'),(1443,'hornet'),(1444,'hamlet'),(1445,'gambit'),(1446,'fuckyou2'),(1447,'alfred'),(1448,'456456'),(1449,'sweetie'),(1450,'marino'),(1451,'lollol'),(1452,'565656'),(1453,'techno'),(1454,'special'),(1455,'renegade'),(1456,'insane'),(1457,'indiana'),(1458,'farmer'),(1459,'drpepper'),(1460,'blondie'),(1461,'bigboobs'),(1462,'272727'),(1463,'1a2b3c'),(1464,'valera'),(1465,'storm'),(1466,'seven'),(1467,'rose'),(1468,'nick'),(1469,'mister'),(1470,'karate'),(1471,'casey'),(1472,'1qaz2wsx3edc'),(1473,'1478963'),(1474,'maiden'),(1475,'julie'),(1476,'curtis'),(1477,'colors'),(1478,'christia'),(1479,'buckeyes'),(1480,'13579'),(1481,'0123456789'),(1482,'toronto'),(1483,'stephani'),(1484,'pioneer'),(1485,'kissme'),(1486,'jungle'),(1487,'jerome'),(1488,'holland'),(1489,'harry'),(1490,'garden'),(1491,'enterpri'),(1492,'dragon1'),(1493,'diamonds'),(1494,'chrissy'),(1495,'bigone'),(1496,'343434'),(1497,'wonder'),(1498,'wetpussy'),(1499,'subaru'),(1500,'smitty'),(1501,'racecar'),(1502,'pascal'),(1503,'morpheus'),(1504,'joanne'),(1505,'irina'),(1506,'indians'),(1507,'impala'),(1508,'hamster'),(1509,'charger'),(1510,'change'),(1511,'bigfoot'),(1512,'babylon'),(1513,'66666666'),(1514,'timber'),(1515,'redman'),(1516,'pornstar'),(1517,'bernie'),(1518,'tomtom'),(1519,'thuglife'),(1520,'millie'),(1521,'buckeye'),(1522,'aaron'),(1523,'virgin'),(1524,'tristan'),(1525,'stormy'),(1526,'rusty'),(1527,'pierre'),(1528,'napoleon'),(1529,'monkey1'),(1530,'highland'),(1531,'chiefs'),(1532,'chandler'),(1533,'catdog'),(1534,'aurora'),(1535,'1965'),(1536,'trfnthbyf'),(1537,'sampson'),(1538,'nipple'),(1539,'dudley'),(1540,'cream'),(1541,'consumer'),(1542,'burger'),(1543,'brandi'),(1544,'welcome1'),(1545,'triumph'),(1546,'joejoe'),(1547,'hunting'),(1548,'dirty'),(1549,'caserta'),(1550,'brown'),(1551,'aragorn'),(1552,'363636'),(1553,'mariah'),(1554,'element'),(1555,'chichi'),(1556,'2121'),(1557,'123qwe123'),(1558,'wrinkle1'),(1559,'smoke'),(1560,'omega'),(1561,'monika'),(1562,'leonard'),(1563,'justme'),(1564,'hobbit'),(1565,'gloria'),(1566,'doggy'),(1567,'chicks'),(1568,'bass'),(1569,'audrey'),(1570,'951753'),(1571,'51505150'),(1572,'11235813'),(1573,'sakura'),(1574,'philips'),(1575,'griffin'),(1576,'butterfl'),(1577,'artist'),(1578,'66666'),(1579,'island'),(1580,'goforit'),(1581,'emerald'),(1582,'elizabeth'),(1583,'anakin'),(1584,'watson'),(1585,'poison'),(1586,'none'),(1587,'italia'),(1588,'callie'),(1589,'bobbob'),(1590,'autumn'),(1591,'andreas'),(1592,'123'),(1593,'sherlock'),(1594,'q12345'),(1595,'pitbull'),(1596,'marathon'),(1597,'kelsey'),(1598,'inside'),(1599,'german'),(1600,'blackie'),(1601,'access14'),(1602,'123asd'),(1603,'zipper'),(1604,'overlord'),(1605,'nadine'),(1606,'marie'),(1607,'basket'),(1608,'trombone'),(1609,'stones'),(1610,'sammie'),(1611,'nugget'),(1612,'naked'),(1613,'kaiser'),(1614,'isabelle'),(1615,'huskers'),(1616,'bomber'),(1617,'barcelona'),(1618,'babylon5'),(1619,'babe'),(1620,'alpine'),(1621,'weed'),(1622,'ultimate'),(1623,'pebbles'),(1624,'nicolas'),(1625,'marion'),(1626,'loser'),(1627,'linda'),(1628,'eddie'),(1629,'wesley'),(1630,'warlock'),(1631,'tyler'),(1632,'goddess'),(1633,'fatcat'),(1634,'energy'),(1635,'david1'),(1636,'bassman'),(1637,'yankees1'),(1638,'whore'),(1639,'trojan'),(1640,'trixie'),(1641,'superfly'),(1642,'kkkkkk'),(1643,'ybrbnf'),(1644,'warren'),(1645,'sophia'),(1646,'sidney'),(1647,'pussys'),(1648,'nicola'),(1649,'campbell'),(1650,'vfvjxrf'),(1651,'singer'),(1652,'shirley'),(1653,'qawsed'),(1654,'paladin'),(1655,'martha'),(1656,'karen'),(1657,'help'),(1658,'harold'),(1659,'geronimo'),(1660,'forget'),(1661,'concrete'),(1662,'191919'),(1663,'westham'),(1664,'soldier'),(1665,'q1w2e3r4t5y6'),(1666,'poiuyt'),(1667,'nikki'),(1668,'mario'),(1669,'juice'),(1670,'jessica1'),(1671,'global'),(1672,'dodger'),(1673,'123454321'),(1674,'webster'),(1675,'titans'),(1676,'tintin'),(1677,'tarzan'),(1678,'sexual'),(1679,'sammy1'),(1680,'portugal'),(1681,'onelove'),(1682,'marcel'),(1683,'manuel'),(1684,'madness'),(1685,'jjjjjj'),(1686,'holly'),(1687,'christy'),(1688,'424242'),(1689,'yvonne'),(1690,'sundance'),(1691,'sex4me'),(1692,'pleasure'),(1693,'logan'),(1694,'danny'),(1695,'wwwwww'),(1696,'truck'),(1697,'spartak'),(1698,'smile'),(1699,'michel'),(1700,'history'),(1701,'Exigen'),(1702,'65432'),(1703,'1234321'),(1704,'sherry'),(1705,'sherman'),(1706,'seminole'),(1707,'rommel'),(1708,'network'),(1709,'ladybug'),(1710,'isabella'),(1711,'holden'),(1712,'harris'),(1713,'germany'),(1714,'fktrctq'),(1715,'cotton'),(1716,'angelo'),(1717,'14789632'),(1718,'sergio'),(1719,'qazxswedc'),(1720,'moon'),(1721,'jesus1'),(1722,'trunks'),(1723,'snakes'),(1724,'sluts'),(1725,'kingkong'),(1726,'bluesky'),(1727,'archie'),(1728,'adgjmptw'),(1729,'911911'),(1730,'112358'),(1731,'sunny'),(1732,'suck'),(1733,'snatch'),(1734,'planet'),(1735,'panama'),(1736,'ncc1701e'),(1737,'mongoose'),(1738,'head'),(1739,'hansolo'),(1740,'desire'),(1741,'alejandr'),(1742,'1123581321'),(1743,'whiskey'),(1744,'waters'),(1745,'teen'),(1746,'party'),(1747,'martina'),(1748,'margaret'),(1749,'january'),(1750,'connect'),(1751,'bluemoon'),(1752,'bianca'),(1753,'andrei'),(1754,'5555555'),(1755,'smiles'),(1756,'nolimit'),(1757,'long'),(1758,'assass'),(1759,'abigail'),(1760,'555666'),(1761,'yomama'),(1762,'rocker'),(1763,'plastic'),(1764,'katrina'),(1765,'ghbdtnbr'),(1766,'ferret'),(1767,'emily'),(1768,'bonehead'),(1769,'blessed'),(1770,'beagle'),(1771,'asasas'),(1772,'abgrtyu'),(1773,'sticky'),(1774,'olga'),(1775,'japan'),(1776,'jamaica'),(1777,'home'),(1778,'hector'),(1779,'dddddd'),(1780,'1961'),(1781,'turbo'),(1782,'stallion'),(1783,'personal'),(1784,'peace'),(1785,'movie'),(1786,'morrison'),(1787,'joanna'),(1788,'geheim'),(1789,'finger'),(1790,'cactus'),(1791,'7895123'),(1792,'susan'),(1793,'super123'),(1794,'spyder'),(1795,'mission'),(1796,'anything'),(1797,'aleksandr'),(1798,'zxcvb'),(1799,'shalom'),(1800,'rhbcnbyf'),(1801,'pickles'),(1802,'passat'),(1803,'natalia'),(1804,'moomoo'),(1805,'jumper'),(1806,'inferno'),(1807,'dietcoke'),(1808,'cumming'),(1809,'cooldude'),(1810,'chuck'),(1811,'christop'),(1812,'million'),(1813,'lollipop'),(1814,'fernando'),(1815,'christian'),(1816,'blue22'),(1817,'bernard'),(1818,'apple1'),(1819,'unreal'),(1820,'spunky'),(1821,'ripper'),(1822,'open'),(1823,'niners'),(1824,'letmein2'),(1825,'flatron'),(1826,'faster'),(1827,'deedee'),(1828,'bertha'),(1829,'april'),(1830,'4128'),(1831,'01012010'),(1832,'werewolf'),(1833,'rubber'),(1834,'punkrock'),(1835,'orion'),(1836,'mulder'),(1837,'missy'),(1838,'larry'),(1839,'giovanni'),(1840,'gggggg'),(1841,'cdtnkfyf'),(1842,'yoyoyo'),(1843,'tottenha'),(1844,'shaved'),(1845,'newman'),(1846,'lindsey'),(1847,'joey'),(1848,'hongkong'),(1849,'freak'),(1850,'daniela'),(1851,'camera'),(1852,'brianna'),(1853,'blackcat'),(1854,'a1234567'),(1855,'1q1q1q'),(1856,'zzzzzzzz'),(1857,'stars'),(1858,'pentium'),(1859,'patton'),(1860,'jamie'),(1861,'hollywoo'),(1862,'florence'),(1863,'biscuit'),(1864,'beetle'),(1865,'andy'),(1866,'always'),(1867,'speed'),(1868,'sailing'),(1869,'phillip'),(1870,'legion'),(1871,'gn56gn56'),(1872,'909090'),(1873,'martini'),(1874,'dream'),(1875,'darren'),(1876,'clifford'),(1877,'2002'),(1878,'stocking'),(1879,'solomon'),(1880,'silvia'),(1881,'pirates'),(1882,'office'),(1883,'monitor'),(1884,'monique'),(1885,'milton'),(1886,'matthew1'),(1887,'maniac'),(1888,'loulou'),(1889,'jackoff'),(1890,'immortal'),(1891,'fossil'),(1892,'dodge'),(1893,'delta'),(1894,'44444444'),(1895,'121314'),(1896,'sylvia'),(1897,'sprite'),(1898,'shadow1'),(1899,'salmon'),(1900,'diana'),(1901,'shasta'),(1902,'patriot'),(1903,'palmer'),(1904,'oxford'),(1905,'nylons'),(1906,'molly1'),(1907,'irish'),(1908,'holmes'),(1909,'curious'),(1910,'asdzxc'),(1911,'1999'),(1912,'makaveli'),(1913,'kiki'),(1914,'kennedy'),(1915,'groovy'),(1916,'foster'),(1917,'drizzt'),(1918,'twister'),(1919,'snapper'),(1920,'sebastia'),(1921,'philly'),(1922,'pacific'),(1923,'jersey'),(1924,'ilovesex'),(1925,'dominic'),(1926,'charlott'),(1927,'carrot'),(1928,'anthony1'),(1929,'africa'),(1930,'111222333'),(1931,'sharks'),(1932,'serena'),(1933,'satan666'),(1934,'maxmax'),(1935,'maurice'),(1936,'jacob'),(1937,'gerald'),(1938,'cosmos'),(1939,'columbia'),(1940,'colleen'),(1941,'cjkywt'),(1942,'cantona'),(1943,'brooks'),(1944,'99999'),(1945,'787878'),(1946,'rodney'),(1947,'nasty'),(1948,'keeper'),(1949,'infantry'),(1950,'frog'),(1951,'french'),(1952,'eternity'),(1953,'dillon'),(1954,'coolio'),(1955,'condor'),(1956,'anton'),(1957,'waterloo'),(1958,'velvet'),(1959,'vanhalen'),(1960,'teddy'),(1961,'skywalke'),(1962,'sheila'),(1963,'sesame'),(1964,'seinfeld'),(1965,'funtime'),(1966,'012345'),(1967,'standard'),(1968,'squirrel'),(1969,'qazwsxed'),(1970,'ninja'),(1971,'kingdom'),(1972,'grendel'),(1973,'ghost'),(1974,'fuckfuck'),(1975,'damien'),(1976,'crimson'),(1977,'boeing'),(1978,'bird'),(1979,'biggie'),(1980,'090909'),(1981,'zaq123'),(1982,'wolverine'),(1983,'wolfman'),(1984,'trains'),(1985,'sweets'),(1986,'sunrise'),(1987,'maxine'),(1988,'legolas'),(1989,'jericho'),(1990,'isabel'),(1991,'foxtrot'),(1992,'anal'),(1993,'shogun'),(1994,'search'),(1995,'robinson'),(1996,'rfrfirf'),(1997,'ravens'),(1998,'privet'),(1999,'penny'),(2000,'musicman'),(2001,'memphis'),(2002,'megadeth'),(2003,'dogs'),(2004,'butt'),(2005,'brownie'),(2006,'oldman'),(2007,'02021984'),(2008,'01011982'),(2009,'zhai'),(2010,'xiong'),(2011,'willia'),(2012,'vvvvvv'),(2013,'venera'),(2014,'unique'),(2015,'tian'),(2016,'sveta'),(2017,'strength'),(2018,'stories'),(2019,'squall'),(2020,'secrets'),(2021,'seahawks'),(2022,'sauron'),(2023,'ripley'),(2024,'riley'),(2025,'recovery'),(2026,'qweqweqwe'),(2027,'qiong'),(2028,'puddin'),(2029,'playstation'),(2030,'pinky'),(2031,'phone'),(2032,'penny1'),(2033,'nude'),(2034,'mitch'),(2035,'milkman'),(2036,'mermaid'),(2037,'max123'),(2038,'maria1'),(2039,'lust'),(2040,'loaded'),(2041,'lighter'),(2042,'lexus'),(2043,'leavemealone'),(2044,'just4me'),(2045,'jiong'),(2046,'jing'),(2047,'jamie1'),(2048,'india'),(2049,'hardcock'),(2050,'gobucks'),(2051,'gawker'),(2052,'fytxrf'),(2053,'fuzzy'),(2054,'florida1'),(2055,'flexible'),(2056,'eleanor'),(2057,'dragonball'),(2058,'doudou'),(2059,'cinema'),(2060,'checkers'),(2061,'charlene'),(2062,'ceng'),(2063,'buffy1'),(2064,'brian1'),(2065,'beautifu'),(2066,'baseball1'),(2067,'ashlee'),(2068,'adonis'),(2069,'adam12'),(2070,'434343'),(2071,'02031984'),(2072,'02021985'),(2073,'xxxpass'),(2074,'toledo'),(2075,'thedoors'),(2076,'templar'),(2077,'sullivan'),(2078,'stanford'),(2079,'shei'),(2080,'sander'),(2081,'rolling'),(2082,'qqqqqqq'),(2083,'pussey'),(2084,'pothead'),(2085,'pippin'),(2086,'nimbus'),(2087,'niao'),(2088,'mustafa'),(2089,'monte'),(2090,'mollydog'),(2091,'modena'),(2092,'mmmmm'),(2093,'michae'),(2094,'meng'),(2095,'mango'),(2096,'mamama'),(2097,'lynn'),(2098,'love12'),(2099,'kissing'),(2100,'keegan'),(2101,'jockey'),(2102,'illinois'),(2103,'ib6ub9'),(2104,'hotbox'),(2105,'hippie'),(2106,'hill'),(2107,'ghblehjr'),(2108,'gamecube'),(2109,'ferris'),(2110,'diggler'),(2111,'crow'),(2112,'circle'),(2113,'chuo'),(2114,'chinook'),(2115,'charity'),(2116,'carmel'),(2117,'caravan'),(2118,'cannabis'),(2119,'cameltoe'),(2120,'buddie'),(2121,'bright'),(2122,'bitchass'),(2123,'bert'),(2124,'beowulf'),(2125,'bartman'),(2126,'asia'),(2127,'armagedon'),(2128,'ariana'),(2129,'alexalex'),(2130,'alenka'),(2131,'ABC123'),(2132,'987456321'),(2133,'373737'),(2134,'2580'),(2135,'21031988');
/*!40000 ALTER TABLE `commonPasswords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `keyID` int(11) NOT NULL,
  `keyName` varchar(64) DEFAULT NULL,
  `keyValue` mediumtext,
  PRIMARY KEY (`keyID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,'Previous Password Salt','98bd61a269a2ed000927947d8e8535e4'),(3,'Requires score column','1'),(4,'Requires password A-Z','0'),(5,'Requires password a-z','3'),(6,'Requires password 0-9','0'),(7,'Requires password symbol','0'),(8,'Reject common passwords','0'),(9,'Reject common passwords','0'),(11,'Password length','6'),(12,'CourseID','104');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `courseID` int(11) NOT NULL AUTO_INCREMENT,
  `courseName` varchar(256) DEFAULT NULL,
  `validated` tinyint(4) DEFAULT NULL,
  `windowWidth` int(11) DEFAULT NULL,
  `windowHeight` int(11) DEFAULT NULL,
  `modules` int(11) DEFAULT NULL,
  PRIMARY KEY (`courseID`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (104,'undefined',0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `moduleID` int(11) NOT NULL AUTO_INCREMENT,
  `moduleTitle` varchar(1024) DEFAULT NULL,
  `roleID` int(11) DEFAULT NULL,
  `masteryScore` varchar(32) DEFAULT NULL,
  `maxTimeAllowed` varchar(32) DEFAULT NULL,
  `dataFromLMS` varchar(256) DEFAULT NULL,
  `timeLimitAction` varchar(64) DEFAULT NULL,
  `prerequisites` varchar(256) DEFAULT NULL,
  `courseID` int(11) NOT NULL,
  `URL` varchar(256) DEFAULT NULL,
  `parentID` int(11) DEFAULT '0',
  `shortTitle` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`moduleID`,`courseID`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parents`
--

DROP TABLE IF EXISTS `parents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parents` (
  `parentID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `courseID` int(11) DEFAULT NULL,
  PRIMARY KEY (`parentID`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parents`
--

LOCK TABLES `parents` WRITE;
/*!40000 ALTER TABLE `parents` DISABLE KEYS */;
/*!40000 ALTER TABLE `parents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `roleID` int(11) NOT NULL,
  `roleName` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Administrator'),(2,'Administrator'),(4,'Users');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `userID` int(11) NOT NULL,
  `ping` int(11) DEFAULT NULL,
  `sessionID` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (1,1623460043,'1623207393');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(64) DEFAULT NULL,
  `lastName` varchar(64) DEFAULT NULL,
  `username` varchar(256) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `roleID` int(11) DEFAULT NULL,
  `dateCreated` datetime DEFAULT NULL,
  `dateCompleted` datetime DEFAULT NULL,
  `fullName` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=606 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super','User','super','22e27429b180b59a9f59ced88eed67e1',1,'2017-12-01 00:00:00',NULL,'Super User'),(2,'Admin','User','admin','22e27429b180b59a9f59ced88eed67e1',2,'2017-12-01 00:00:00',NULL,'Admin User'),(3,'Student','User','student','22e27429b180b59a9f59ced88eed67e1',4,'2021-05-28 21:07:03',NULL,'Student User');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-12  2:17:30
