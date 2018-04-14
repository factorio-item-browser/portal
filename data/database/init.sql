-- Creates the database tables for the portal in an empty database.

CREATE TABLE `User` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The ID of the user.',
  `locale` VARCHAR(5) NOT NULL COMMENT 'The locale the user uses.',
  `enabledModNames` TEXT NOT NULL COMMENT 'The mods the user wants to have enabled.',
  `lastVisit` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'The timestamp when the user last visited.',
  `isFirstVisit` BIT(1) NOT NULL COMMENT 'Whether this is the first visit of the user.',
  `sessionId` CHAR(32) NOT NULL COMMENT 'The session ID for the user.',
  `apiAuthorizationToken` TEXT NOT NULL COMMENT 'The API authorization token of the user.',
  `sessionData` LONGTEXT NOT NULL COMMENT 'The data of the user session.',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `sessionId` (`sessionId`)
)
COMMENT='The table holding the user data.'
ENGINE=InnoDB;


CREATE TABLE `SidebarEntity` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'The ID of the sidebar entity.',
  `userId` INT(10) UNSIGNED NOT NULL COMMENT 'The ID of the user owning the sidebar entity.',
  `type` ENUM('item','fluid','recipe') NOT NULL COMMENT 'The type of the entity.',
  `name` VARCHAR(255) NOT NULL COMMENT 'The name of the entity.',
  `label` TEXT NOT NULL COMMENT 'The translated label of the entity.',
  `description` TEXT NOT NULL COMMENT 'The translated description of the entity.',
  `pinnedPosition` INT(10) UNSIGNED NOT NULL COMMENT 'The pinned position of the entity in the sidebar. 0 if not pinned.',
  `lastViewTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time when the entity was last viewed.',
  PRIMARY KEY (`id`),
  INDEX `userId` (`userId`),
  CONSTRAINT `fk_SE_userId` FOREIGN KEY (`userId`) REFERENCES `User` (`id`)
)
COMMENT='The table holding the entities of the sidebar for the users.'
ENGINE=InnoDB;
