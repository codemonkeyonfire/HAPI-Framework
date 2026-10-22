<?php

	$SITE = $_SERVER['HTTP_HOST']; 
	$DB_Server = '';
	$DB_User = '';
	$DB_Pass = '';
	$DB_Database = '';
		
	
    /**
     * Open the session
     * @return bool
     */
    function open($save_path, $sess_name) 
    {
    	global $SITE,$DB_Server,$DB_User,$DB_Pass,$DB_Database;
    	
		switch ($SITE)
		{
			case "switch.veritaspoll.local":
				//	DATABASE settings
				$DB_Server = "localhost";
				$DB_User = "session_usr";
				$DB_Pass = "abc%123*";
				$DB_Database = "web_sessions";					
				break;  
		} 	    	
		
		
    }

    /**
     * Close the session
     * @return bool
     */
    function close() {

        return true;

    }

    /**
     * Read the session
     * @param int session id
     * @return string string of the sessoin
     */
    function read($id) 
    {
    	global $SITE,$DB_Server,$DB_User,$DB_Pass,$DB_Database;
		if ($objDB = mysql_connect($DB_Server, $DB_User, $DB_Pass)) 
        {
            mysql_select_db($DB_Database, $objDB);
        }

        $id = mysql_real_escape_string($id);
        $sql = sprintf("SELECT `data` FROM `sessions` WHERE session_id = '%s'", $id);
        if ($result = mysql_query($sql, $objDB)) 
        {
            if (mysql_num_rows($result)) 
            {
                $record = mysql_fetccontroller_assoc($result);
                return $record['data'];
            }
        }
        return '';

    }

    /**
     * Write the session
     * @param int session id
     * @param string data of the session
     */
    function write($id, $data) 
    {
    	global $SITE,$DB_Server,$DB_User,$DB_Pass,$DB_Database;
    	
		if ($objDB = mysql_connect($DB_Server, $DB_User, $DB_Pass)) 
        {
            mysql_select_db($DB_Database, $objDB);
        }    	

 
        $sql = sprintf("REPLACE INTO `sessions` VALUES('%s', '%s', '%s', '%s', '%s')",
                       mysql_real_escape_string($id),
                       mysql_real_escape_string(time()),
                       mysql_real_escape_string($data),
                       $SITE,
                       $_SERVER['REMOTE_ADDR']);

        return mysql_query($sql, $objDB);

    }

    /**
     * Destoroy the session
     * @param int session id
     * @return bool
     */
    function destroy($id) 
    {
    	global $SITE,$DB_Server,$DB_User,$DB_Pass,$DB_Database;
		if ($objDB = mysql_connect($DB_Server, $DB_User, $DB_Pass)) 
        {
            mysql_select_db($DB_Database, $objDB);
        }
        $sql = sprintf("DELETE FROM `sessions` WHERE `session_id` = '%s'", $id);
        return mysql_query($sql, $objDB);

	}

    /**
     * Garbage Collector
     * @param int life time (sec.)
     * @return bool
     * @see session.gc_divisor      100
     * @see session.gc_maxlifetime 1440
     * @see session.gc_probability    1
     * @usage execution rate 1/100
     *        (session.gc_probability/session.gc_divisor)
     */
    function gc($max) 
    {
    	global $SITE,$DB_Server,$DB_User,$DB_Pass,$DB_Database;
		if ($objDB = mysql_connect($DB_Server, $DB_User, $DB_Pass)) 
        {
            mysql_select_db($DB_Database, $objDB);
        }
        
        $sql = sprintf("DELETE FROM `sessions` WHERE `Created` < '%s'", mysql_real_escape_string(time() - $max));
        return mysql_query($sql, $objDB);

    }


?>
