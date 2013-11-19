<?php
namespace OpenSteam\MokodeskBundle\Backend;
require_once dirname(__FILE__) . '/../etc/default.def.php';

use \steam_connector,
    \ParameterException,
    \Exception;

class MokodeskSteam
{
	public static function connect( $server = STEAM_SERVER, $port = STEAM_PORT, $login = "", $password = "" )
	{
		try {
			$GLOBALS["STEAM"] = steam_connector::connect( $server, $port, $login, $password );
			return $GLOBALS["STEAM"];
		} catch (ParameterException $p) {
			return false;
		} catch( Exception $e ) {
			throw new Exception("No connection to sTeam server ($server:$port).");
		}
	}
}

?>
