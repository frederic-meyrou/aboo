DELIMITER $$

DROP TRIGGER  `recette_after_delete`$$

CREATE
	TRIGGER `recette_after_delete` AFTER DELETE 
	ON `recette`
	FOR EACH ROW BEGIN
	
	DELETE FROM paiement WHERE recette_id = OLD.id;
		
    END$$

DELIMITER ;    