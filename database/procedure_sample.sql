DROP PROCEDURE IF EXISTS procedure_sample;

DELIMITER //
CREATE PROCEDURE procedure_sample()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE user_id INT;
    DECLARE cuurentId CURSOR FOR SELECT id FROM users WHERE delete_flg = 0;
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

    OPEN cuurentId;

    REPEAT
        FETCH cuurentId INTO user_id;
        IF NOT done THEN
            SET @createSql = CONCAT('CREATE TABLE details_', user_id, ' LIKE details');
            PREPARE stmt from @createSql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        END IF;
        UNTIL done
    END REPEAT;

    CLOSE cuurentId;
END
//

DELIMITER ;

CALL procedure_sample();

DROP PROCEDURE IF EXISTS procedure_sample;