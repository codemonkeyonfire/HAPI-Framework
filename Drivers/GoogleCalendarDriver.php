<?php
/**
 * google calendar Driver
 *
 * formats config varibles and reads values from that format
 * @author Carlos Ferrera
 * @version 1.0
 * @package configuration
 */
/**
 * The first example class, this is in the same package as the
 * procedural stuff in the start of the file
 * @package configuration
 * @subpackage classes
 */
namespace Drivers;

require_once "3rdParty/google-api-php-client/src/apiClient.php";
require_once "3rdParty/google-api-php-client/src/contrib/apiCalendarService.php";

class GoogleCalendarDriver
{
	private $CLIENT;
	private $SERVICE;
	private $CALENDAR_ID;
	
	
	public function __construct()
	{
	    $this->CLIENT = new \apiClient();
		$this->CLIENT->setApplicationName("Google Calendar PHP");

		$this->SERVICE = new \apiCalendarService($this->CLIENT);
	}
	
	public function __destruct()
	{
		
	}
	
	public function Connect($TOKEN="")
	{
		// authorization complete
		if (isset($_GET['code'])) 
		{
			$this->CLIENT->authenticate();
			$token = $this->CLIENT->getAccessToken();
			$this->CLIENT->setAccessToken($token);	
			return $token;
		}		
		
		// token present, continue
		if($TOKEN != "")	
			$this->CLIENT->setAccessToken($TOKEN);		
		
		// not authorized, redirect to google
		if(!$this->CLIENT->getAccessToken())
		{
			$authUrl = $this->CLIENT->createAuthUrl();
			die("=>$authUrl");
			return $authUrl;
		}	
	}
	

	public function Close()
	{
		return $this->CLIENT->getAccessToken();
	}
	
	public function List_Calendars()
	{
		$calList = $this->SERVICE->calendarList->listCalendarList();
		return $calList["items"];
	}
	
	public function Push_Event($EVENT_DATA)
	{
	    $event = new \Event();
	    $event->setSummary($EVENT_DATA["summary"]);
	    $event->setLocation($EVENT_DATA["location"]);
	    $start = new \EventDateTime();
	    $start->setDateTime($EVENT_DATA["start"]);
	    $event->setStart($start);
	    $end = new \EventDateTime();
	    $end->setDateTime($EVENT_DATA["end"]);
	    $event->setEnd($end);
	
	    $createdEvent = $this->SERVICE->events->insert($this->CALENDAR_ID, $event);	
	    
	    return $createdEvent["id"];
	}
	
	public function Set_Calendar($CALENDAR_ID)
	{
		$this->CALENDAR_ID = $CALENDAR_ID;
	}
}


?>