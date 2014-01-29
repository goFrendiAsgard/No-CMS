CREATE TABLE `{{ complete_table_name:article }}` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_title` varchar(100),
  `article_url` varchar(100),
  `date` datetime,
  `author_user_id` int(10),
  `content` text,
  `allow_comment` tinyint(3),
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:category }}` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100),
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:category_article }}` (
  `category_id` int(10),
  `article_id` int(10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:comment }}` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10),
  `date` datetime,
  `author_user_id` int(10),
  `name` varchar(50),
  `email` varchar(50),
  `website` varchar(50),
  `content` text,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:photo }}` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10),
  `url` varchar(50),
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/
INSERT INTO `{{ complete_table_name:category }}` (`category_id`, `category_name`, `description`) VALUES
(1, 'Science', NULL),
(2, 'Fun', NULL);

/*split*/
INSERT INTO `{{ complete_table_name:article }}` (`article_id`, `article_title`, `article_url`, `date`, `author_user_id`, `content`, `allow_comment`) VALUES
(1, 'Scandal', 'Scandal', '2013-03-25 09:50:49', 1, '<p>\n  &nbsp;</p>\n<div>\n SCANDAL (スキャンダル Sukyandaru?, stylized as SCANDAL) is a Japanese pop rock girl band from Osaka, Japan. Formed in August 2006 by four high school girls, they started playing street lives until they were noticed and signed to the indie label Kitty Records. In 2008, they released three singles and a mini-album while performing shows in the United States, France, and Hong Kong. That October, Scandal released their major debut single, &quot;Doll&quot;, under Epic Records Japan.</div>\n<div>\n &nbsp;</div>\n<div>\n   The band has performed the theme songs for many anime, including &quot;Shōjo S&quot; for Bleach and &quot;Shunkan Sentimental&quot; for Fullmetal Alchemist: Brotherhood. With numerous overseas performances and anime theme songs, Scandal has built a considerable international fanbase.</div>\n<div>\n &nbsp;</div>\n<div style="page-break-after: always;">\n <span style="display: none;">&nbsp;</span></div>\n<div>\n   <div>\n     <strong>Indie career</strong></div>\n   <div>\n     &nbsp;</div>\n  <div>\n     Scandal was formed in August 2006 by four high school girls. The girls, Haruna, Mami, Tomomi, and Rina, met in an Osaka vocal and dance school called Caless. Shortly thereafter, they started performing street lives every weekend at Shiroten in Osaka Castle Park. Soon, they started getting offers from clubs in Osaka and Kyoto. The band&#39;s name originates from a sign near Studio Brotherz, a studio where they practiced in their early days. The studio is on the sixth floor of a building shared with other businesses, namely adult shops. The girls decided to choose the biggest sign among the shops, &quot;Scandal&quot; (スキャンダル Sukyandaru?), as the name for their band.</div>\n    <div>\n     &nbsp;</div>\n  <div>\n     Scandal signed with indie label Kitty Records and released three singles exclusive to Tower Records in 2008. The first, &quot;Space Ranger&quot;, ranked #2 on the Tower indie charts and the other two, &quot;Koi Moyō&quot; and &quot;Kagerō&quot;, ranked #1. In March, they embarked on the Japan Nite US tour 2008, touring six major cities in the United States. They also performed at Sakura-Con, one of the largest anime conventions in the United States. In July, they performed in front of 10,000 people at France&#39;s Japan Expo and also at Hong Kong&#39;s Animation-Comic-Game Hong Kong in August. Scandal concluded their indie career with the release of their first mini-album, Yah! Yah! Yah! Hello Scandal: Maido! Scandal Desu! Yah Yah Yah!.</div>\n  <div>\n     &nbsp;</div>\n  <div>\n     <strong>Major debut</strong></div>\n    <div>\n     &nbsp;</div>\n  <div>\n     2008 continued to be an eventful year for Scandal. In October, they made their major debut on Epic Records Japan with the single &quot;Doll&quot;. It gave them more exposure, including appearances on mainstream music television shows like Music Station. The band released their second major single &quot;Sakura Goodbye&quot; in March 2009 to commemorate Mami and Tomomi&#39;s high school graduation. The song is a new version of their indie song, &quot;Sakura&quot;, only heard live. The following month, their then upcoming third major single &quot;Shōjo S&quot; was used as the tenth opening theme for the anime Bleach. This brought their popularity up even higher as the single ranked #6 on the Oricon charts when it was released two months later in June.</div>\n  <div>\n     &nbsp;</div>\n  <div>\n     On October 14th, Scandal released their fourth major single, &quot;Yumemiru Tsubasa&quot; which was followed with their major debut album, Best Scandal, the next week. The album ranked #5 on the Oricon weekly chart, making them the first girl band since Zone to have a debut album chart in the top five. In December, Scandal embarked on their first one-man tour. Concluding the year, the band won a New Artist Award at the 51st Japan Record Award, but lost the Best New Artist Award to Big Bang.</div>\n <div>\n     &nbsp;</div>\n  <div>\n     2010 began with Scandal&#39;s fifth major single in February, &quot;Shunkan Sentimental&quot;. It was used as the fourth ending theme for the anime Fullmetal Alchemist: Brotherhood. The following month, they embarked on a spring tour, Scandal: Shunkan Sakura Zensen Tour 2010 Spring. Prior to its start, the band conducted a Twitter poll to choose a song to cover for the tour. The winner was &quot;Secret Base (Kimi ga Kureta Mono)&quot;, which was chosen out of over 600 candidates. In June, Scandal released a pop tune for summer, &quot;Taiyō to Kimi ga Egaku Story&quot;, followed by their first original ballad, &quot;Namida no Regret&quot;, in July.</div>\n <div>\n     &nbsp;</div>\n  <div>\n     Between the end of July and the beginning of August, Scandal traveled to Hong Kong. The band performed for the third consecutive year at the Animation-Comic-Game Hong Kong convention and held their first one-man live concert in Hong Kong, which sold out. SCANDAL was also featured on the cover of the Hong Kong magazine re:spect music magazine, and their previously released single &quot;Taiyō to Kimi ga Egaku Story&quot; reached #1 on the Radio Television Hong Kong J-pop chart. Earlier in the year, they were awarded a bronze newcomer award by RTHK, similar to the Hong Kong Grammys.</div>\n  <div>\n     &nbsp;</div>\n  <div>\n     After returning to Japan, Scandal released their second album, Temptation Box, on August 11. The album debuted at #3 on the Oricon weekly chart, making them the first girl band to have an album chart in the top three in over a year since Chatmonchy&#39;s Kokuhaku. The album was also released in 42 other countries worldwide.</div>\n   <div>\n     &nbsp;</div>\n  <div>\n     Later in August, Scandal provided the theme, insert, and ending songs for the animated film Loups=Garous, which premiered on the 28th in most of Japan. The songs were &quot;Midnight Television&quot;, &quot;Koshi-Tantan&quot;, and &quot;Sayonara My Friend&quot;, respectively. The band also appeared as themselves, marking their big-screen debut as a band. They were shown in a musical performance scene that was created with the help of motion capture, providing a realistic representation of the band&#39;s movements. Each member also had a role voicing a minor character.</div>\n   <div>\n     &nbsp;</div>\n  <div>\n     Two months following Temptation Box, Scandal released their eighth major single, &quot;Scandal Nanka Buttobase&quot;, on October 6, 2010. The title track was written and composed by the husband-and-wife duo Yoko Aki and Ryudo Uzaki, who are known for creating many songs for Momoe Yamaguchi. The limited edition DVDs contains performances from the band&#39;s first television show, Shiteki Ongaku Jijō, which ran for 13 episodes from July to September 2010. In November, Scandal released a cover mini-album called R-Girl&#39;s Rock!. It features songs by female artists that they respect from the last three decades, including their cover of &quot;Secret Base (Kimi ga Kureta Mono)&quot; from May. Rina undertook her first lead vocal on the song &quot;Sunny Day Sunday&quot;.</div>\n <div>\n     &nbsp;</div>\n  <div>\n     Scandal continued into 2011 with their ninth major single on February 9. Titled &quot;Pride&quot;, the song was used as the second ending theme for the anime Star Driver: Kagayaki no Takuto. The single also includes the tracks &quot;Cute!&quot;, a collaboration with Sanrio&#39;s Cinnamoroll, and &quot;Emotion&quot;, their first song that was written solely by a band member. Their tenth major single, &quot;Haruka&quot;, was released on April 20. The title track was used as the theme song for the animated film Tofu Kozou, while the song &quot;Satisfaction&quot; was later used as the promotional song for the release of Windows 8. This was followed by their eleventh major single, &quot;Love Survive&quot;, as well as their third studio album Baby Action. They also embarked on their first Asian Tour, performing to sell out crowds in Hong Kong, Taiwan and Singapore.</div>\n <div>\n     &nbsp;</div>\n  <div>\n     2012 proved to be a year of firsts for Scandal. The title track of their twelfth single, &quot;Harukaze&quot;, was used as the the opening theme for the anime Bleach. The following month, they released their first Best Of album, Scandal Show, as well as holding their first concert at the Nippon Budokan, thus becoming the fastest girl band to perform there since their debut. In July, they released their thirteenth major single, &quot;Taiyō Scandalous&quot;. This single marked the first official release of their subunits, Dobondobondo (Mami x Tomomi) and Almond Crush (Haruna x Rina). This was followed by their fourteenth major single, &quot;Pin Heel Surfer&quot;, and their fourth major album, Queens Are Trumps: Kirifuda wa Queen. With this release, they became the first girl band to achieve fur consecutive top 5 positions in the Oricon Weekly charts. They also held a concert in Malaysia in December, becoming the first Japanese band to hold a solo concert there.</div>\n   <div>\n     &nbsp;</div>\n  <div>\n     Scandal started 2013 by fulfilling one of their biggest dreams they had since their formation by performing in their hometown at the Osaka-jō Hall in March. Later that month they also performed to sellout crowds on their 2nd Asian Tour in Indonesia, Singapore and Thailand. During this period they also announced their fourteenth major single &quot;Awanai Tsumori no, Genki de ne&quot; released in May, with the title track being used as the theme song for the movie &quot;Ore wa Mada Honki Dashitenai Dake&quot;.</div>\n</div>\n<p>\n  &nbsp;</p>\n', 1);

/*split*/
INSERT INTO `{{ complete_table_name:category_article }}` (`category_id`, `article_id`) VALUES
(2, 1);

/*split*/
INSERT INTO `{{ complete_table_name:comment }}` (`comment_id`, `article_id`, `date`, `author_user_id`, `name`, `email`, `website`, `content`) VALUES
(1, 1, '2013-03-25 09:53:16', 1, '0', '0', '', 'great comment for great article');

/*split*/
INSERT INTO `{{ complete_table_name:photo }}` (`photo_id`, `article_id`, `url`) VALUES
(1, 1, 'e290d4scandal-promo.jpg'),
(2, 1, '6d65c1si_5097488_vjjnjbm6s1_lr.jpg'),
(3, 1, 'cf061dNews_Scandal_Tempt1b.jpg'),
(4, 1, '77bf85541445_422004441170914_376342220_n.jpg'),
(5, 1, '38a006scandalbox.jpg');