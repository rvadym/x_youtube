
-- -----------------------------------------------------
-- Table `atk_bobeen`.`x_youtube`
-- -----------------------------------------------------

CREATE  TABLE IF NOT EXISTS `x_youtube` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `video_id` VARCHAR(255) NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `keywords` VARCHAR(255) NULL ,
  `desrtiption` TEXT NULL ,
  `content_description` TEXT NULL ,
  `link_to_video` VARCHAR(255) NOT NULL ,
  `embed_video` VARCHAR(255) NULL ,
  `thumbnail_big` VARCHAR(255) NOT NULL ,
  `thumbnail_small` VARCHAR(255) NOT NULL ,
  `author_name` VARCHAR(45) NULL ,
  `published` VARCHAR(100) NULL ,
  `updated` VARCHAR(100) NULL ,
  `mobile_view_href` VARCHAR(255) NULL ,
  `author_chanel_href` VARCHAR(255) NULL ,
  `author_atom` VARCHAR(255) NULL ,
  `responses_atom` VARCHAR(255) NULL ,
  `related_videos_atom` VARCHAR(255) NULL ,
  `about_video_atom` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;