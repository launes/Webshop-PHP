/*
 * INFOS:
 * to enable SHA2 please check if TLS is enabled, won't work otherwise.
 * (Link to enable TLS: https://mariadb.com/kb/en/secure-connections-overview/ )
 * if TLS enabled replace MD5 with SHA2 and after concat place strLength (int)
 * 
 * SELECT SHA2( CONCAT( password, salt ) strLength )
*/

DROP FUNCTION IF EXISTS createUser;
DROP FUNCTION IF EXISTS compPsswd;
DROP FUNCTION IF EXISTS addCart;
DROP FUNCTION IF EXISTS viewCart;


DELIMITER //
CREATE FUNCTION createUser(IN user VARCHAR( 50 ), 
						   IN userEmail VARCHAR( 100 ), 
						   IN psswd VARCHAR( 255 ) ) RETURNS BOOLEAN
/*
 * false Return = User exists or fail
 * true Return = User has been created
*/
BEGIN

-- check if user exists
-- if exists: return false
-- if not exists: create user, return true
IF( SELECT EXISTS( SELECT * FROM users WHERE( email = userEmail ) ) ) THEN
	RETURN FALSE;
	
ELSE
	INSERT INTO users( username, email, password ) VALUES(
		user,
		userEmail,
		psswd );
	RETURN TRUE;
	
END IF;

END; //
DELIMITER ;



DELIMITER //
CREATE FUNCTION compPsswd( IN userEmail VARCHAR( 100 ), IN psswd VARCHAR( 50 ) ) RETURNS BOOLEAN
/*
 * false return = false password or fail
 * true return = correct password
*/
BEGIN

-- We don't want to use unsalted psswd
DECLARE saltedPsswd VARCHAR( 255 );

SELECT MD5( CONCAT( psswd, '254' ) ) INTO saltedPsswd;

-- if psswd exists return true
-- else return false
IF( SELECT MD5( CONCAT( password, '254' ) ) FROM users WHERE( email = userEmail ) = saltedPsswd ) THEN
	RETURN TRUE;
ELSE
	RETURN FALSE;
END IF;

END; //
DELIMITER ;



DELIMITER //
CREATE FUNCTION addCart( userID INT, productID INT, quant INT ) RETURNS BOOLEAN
/*
 * Returns dummy true
*/
BEGIN

INSERT INTO cart( user_id, product_id, quantity ) VALUES( userID, productID, quant );
RETURN TRUE;

END; //
DELIMITER ;



DELIMITER //
CREATE FUNCTION viewCart( userID INT ) RETURNS BOOLEAN
/*
 * Returns dummy true
*/
BEGIN

SELECT * FROM cart WHERE( user_id = userID );
RETURN TRUE;

END; //
DELIMITER ;
