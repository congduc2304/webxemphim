<?php
function mySqlErrors($response)
{
	try {
		$dbl = new DbConn;
    //Returns custom error messages instead of MySQL errors
    switch (substr($response, 0, 22)) {
        case 'Error: SQLSTATE[23000]':
			echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$dbl->lang_dialog_register_user_already_exists_error."</span>";
            break;
        default:
			echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$dbl->lang_dialog_unknown_error."</span>";
    }
	} catch (PDOException $e) {
		echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$e->getMessage()."</span>";
	}
};
