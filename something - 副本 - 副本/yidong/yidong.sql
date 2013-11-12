CREATE TABLE IF NOT EXISTS `yidong` (
  -- `plan_name` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,

  `monthly_fee` double NOT NULL,
    `total_phone` double NOT NULL,
      `flow` double NOT NULL,
      `message` int NOT NULL,
       `video` double NOT NULL,
        `phone_m` double NOT NULL,
         `video_m` double NOT NULL,
          `flow_k` double NOT NULL,
          `message_m` double NOT NULL,
  
  PRIMARY KEY (`monthly_fee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;