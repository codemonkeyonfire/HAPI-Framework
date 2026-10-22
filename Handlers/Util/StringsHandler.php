<?php
/**
 * Database Handler
 * can only implement drivers
 * can only use other handlers if they are passed by reference
 * Do not reference Globals except for lib paths (explicitly pass values to functions)
 * can implement base class if and only if base class used by more than one handlers
*
* @author 
* @version 1.0
* @package insideArtCenter
* @subpackage common
* @date 6/14/12
*/
namespace Handlers\Util;

class StringsHandler 
{
	protected $Registry;
	
	function __construct(&$REGISTRY)
	{
		$this->Registry = &$REGISTRY;
	}
		
	static public function get_random_string($LENGTH = 10, $CHAR_SET="")
	{
		
		switch ($CHAR_SET)
		{
		    case "SQL_SAFE":
		        $valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ$-_.!*(),abcdefghijklmnopqrstuvwxyz0123456789";
		        break;
			case "URL_SAFE":
				$valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ$-_.!*'(),abcdefghijklmnopqrstuvwxyz0123456789";
				break;
			case "ALPHA_NUM":
				$valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
				break;
			case "":
				$valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*-+~'_():;?<>[]/\|{}abcdefghijklmnopqrstuvwxyz0123456789";
				break;
			default:
				$valid_chars = $CHAR_SET;
		}
	
	    // start with an empty random string
	    $random_string = "";
	
	    // count the number of chars in the valid chars string so we know how many choices we have
	    $num_valid_chars = strlen($valid_chars);
	
	    // repeat the steps until we've created a string of the right length
	    for ($i = 0; $i < $LENGTH; $i++)
	    {
	        // pick a random number from 1 up to the number of valid chars
	        $random_pick = mt_rand(1, $num_valid_chars);
	
	        // take the random character out of the string of valid chars
	        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
	        $random_char = $valid_chars[$random_pick-1];
	
	        // add the randomly-chosen char onto the end of our string so far
	        $random_string .= $random_char;
	    }
	
	    // return our finished random string
	    return $random_string;
	}	  
	
	static public function Generate_SRID()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    $seed = $sec + $usec * 1000000;
	    mt_srand($seed);
	    $startPos = mt_rand(0, 30);
	    
	    $srID = substr(sha1($usec."monkeyBallz"), $startPos, 10);
	    
	    return $srID;
	}
	
	static public function Highlite($HAYSTACK, $NEEDLE)
	{
		$pos = stripos($HAYSTACK, $NEEDLE);
		$strLen = strlen($NEEDLE);
		$output = "";
			
		if($pos !== false)
		{
			if($pos > 0)
				$output = substr($HAYSTACK, 0, $pos);
	
				$output .= "<span class=\"search-hilite\">".substr($HAYSTACK, $pos, $strLen)."</span>";
				$output .= substr($HAYSTACK, $pos + $strLen);
		}else{
			$output = $HAYSTACK;
		}
			
		return $output;
	}	
	
	static public function str_diff($OLD, $NEW)
	{
	   # split the source text into arrays of lines
		$t1 = explode(" ", $OLD);	   		   
	   	$t2 = explode(" ",$NEW);
	   	
	   # build a reverse-index array using the line as key and line number as value
	   # don't store blank lines, so they won't be targets of the shortest distance
	   # search
	   	foreach($t1 as $i=>$x) if ($x>'') $r1[$x][]=$i;
	   	foreach($t2 as $i=>$x) if ($x>'') $r2[$x][]=$i;

	   	$a1=0; $a2=0;   # start at beginning of each list
	   	$actions=array();

  		# walk this loop until we reach the end of one of the lists
	   	while ($a1<count($t1) && $a2<count($t2)) 
	   	{
	     # if we have a common element, save it and go to the next
	   		if ($t1[$a1]==$t2[$a2]) { $actions[]=4; $a1++; $a2++; continue; } 
	
	     # otherwise, find the shortest move (Manhattan-distance) from the
	     # current location
			$best1=count($t1); $best2=count($t2);
		    $s1=$a1; $s2=$a2;
	
		    while(($s1+$s2-$a1-$a2) < ($best1+$best2-$a1-$a2)) 
		    {
	       		$d=-1;
	       		foreach((array)@$r1[$t2[$s2]] as $n) 
	         		if ($n>=$s1) { $d=$n; break; }
	       		
	       		if ($d>=$s1 && ($d+$s2-$a1-$a2)<($best1+$best2-$a1-$a2))
	         	{ $best1=$d; $best2=$s2; }
	       
	         	$d=-1;
	       	
	       		foreach((array)@$r2[$t1[$s1]] as $n) 
	         		if ($n>=$s2) { $d=$n; break; }
	       
	       		if ($d>=$s2 && ($s1+$d-$a1-$a2)<($best1+$best2-$a1-$a2))
	         	{ $best1=$s1; $best2=$d; }
	       		
	         	$s1++; $s2++;
	     	}
	     
	     	while ($a1<$best1) { $actions[]=1; $a1++; }  # deleted elements
	     	while ($a2<$best2) { $actions[]=2; $a2++; }  # added elements
	   	}

  # we've reached the end of one list, now walk to the end of the other
		while($a1<count($t1)) { $actions[]=1; $a1++; }  # deleted elements
  		while($a2<count($t2)) { $actions[]=2; $a2++; }  # added elements

  # and this marks our ending point
 		$actions[]=8;

  # now, let's follow the path we just took and report the added/deleted
  # elements into $out.
  		$op = 0;
  		$x0=$x1=0; $y0=$y1=0;
  		$out = array();
  		$tmp = array();
  		
  		foreach($actions as $act) 
  		{
		    if ($act==1) { $op|=$act; $x1++; continue;}
		    if ($act==2) { $op|=$act; $y1++; continue;}
		    if ($op>0) 
		    {
		    	$xstr = ($x1==($x0+1)) ? $x1 : ($x0+1).",$x1";
		      	$ystr = ($y1==($y0+1)) ? $y1 : ($y0+1).",$y1";
		      
		      	if ($op==1) 
		      		$out[] = array($xstr, "d", $y1);
		      	elseif ($op==3) 
		      		$out[] = array($xstr, "c", $ystr);
		      
		      	while ($x0<$x1) { $x0++; }   # deleted elems
		      
		      	if ($op==2) 
		      		$out[] = array($x1, "a", $ystr);
		      
		      	while ($y0<$y1) { $y0++; }   # added elems
    		}
    
		    $x1++; $x0=$x1;
		    $y1++; $y0=$y1;
		    $op=0;
  		}
  		
  	// ******************************************************************************   highlite old text
  		$buff = "";
  		$editIndx = 0;
  		$cn = count($t1);
  		$cnt = count($out);
  		
  		while($out[$editIndx][1] == 'a' && $editIndx < $cnt)
  			$editIndx++;
  		
		for($i = 0; $i < $cn; $i++)
		{
			list($start, $end) = explode(",", $out[$editIndx][0]);
			$start--; $end--;
		
			$color = ($out[$editIndx][1] == 'd')?"ee1111":"c1b111";
			if($i == $start) $buff .= "<font style='color: #{$color};'>";
			$buff .= " "; $buff .= $t1[$i];
			if($i == $start)
			{
				if(!empty($end))
				{
					for($j = $i+1; $j <= $end; $j++ )
						$buff .= " ".$t1[$j];
  								
					$i = $j;
				}
  		
				$buff .= "</font>";
				$editIndx++;
				while($out[$editIndx][1] == 'a' && $editIndx < $cnt)
					$editIndx++;
			}
		}	
		
		$result["old"] = $buff;

	// ******************************************************************************   highlite new text	
	
		$buff = "";
		$editIndx = 0;
		$cn = count($t2);
		
		while($out[$editIndx][1] == 'd' && $editIndx < $cnt)
			$editIndx++;
		
		for($i = 0; $i < $cn; $i++)
		{
			list($start, $end) = explode(",", $out[$editIndx][2]);
			$start--; $end--;
		
			$color = ($out[$editIndx][1] == 'a')?"208e07":"c1b111";
			if($i == $start) $buff .= "<span style='color: #{$color};'>";
			$buff .= " "; $buff .= $t2[$i];
			if($i == $start)
			{
				if(!empty($end))
				{
					for($j = $i+1; $j <= $end; $j++ )
						$buff .= " ".$t2[$j];
								
					$i = $j;
				}
		
				$buff .= "</span>";
				$editIndx++;
				while($$t2[$editIndx][1] == 'd' && $editIndx < $cnt)
					$editIndx++;
			}
		}		
		$result["new"] = $buff;
		
		return $result;
	}	
	
	static public function to12Hours($TIME_HR24)
	{
		list($hours, $minutes, $seconds) = explode(":", $TIME_HR24);
			
		$hrs = intval($hours);
		$mins = intval($minutes);
	
		if ($hrs > 11)
		{
			if($hrs == 24)
				$md = "AM";
			else
				$md = "PM";
	
			if($hrs > 12) $hrs = $hrs - 12;
		} else {
			$md = "AM";
		}
	
		$result = "";
	
		$hours = ($hrs < 10)?"0".$hrs:$hrs;
		$minutes = ($mins < 10)?"0".$mins:$mins;
			
		$result .= $hours.":".$minutes.$md;
	
		return $result;
	}
	
	static public function to24Hours($TIME_HR12)
	{
	
		list($hours, $buffer) = explode(":", $TIME_HR12);
	
		$h = intval($hours);
		$m = substr($buffer, 0, 2);
		$meri = strtoupper(substr($buffer, -2));
			
		if($meri == "PM" && $h <> 12)
			$h = $h + 12;
				
		if($meri == "AM" && $h == 12)
			$h = 0;
					
		$p = ($h < 10)?"0":"";
					
		$hr24 = "{$p}{$h}:{$m}:00";
	
		return $hr24;
	}	
	
	static public function toSlashDate($DATETIME, $MERIDIANTIME=false)
	{
		$parts = explode(" ", $DATETIME);
		[$y, $m, $d] = explode("-", $parts[0]);
		$result = "{$m}/{$d}/{$y}";
		
		if($MERIDIANTIME) $parts[1] = StringsHandler::to12Hours($parts[1]);
		if(!empty($parts[1])) $result .= " {$parts[1]}";
		    
	    return $result;
	}
	

	static public function toDashDate($DATE)
	{
		list($m, $d, $y) = explode("/", $DATE);
		return "{$y}-{$m}-{$d}";	
	}
	
	static public function toHumanReadable($NUMBER, $PERCISION=1)
	{
		$n = intval($NUMBER);
		if ($n < 1000000)
		{
			$readable = number_format($n / 1000, $PERCISION) . 'K';
		} else if ($n < 1000000000) {
			$readable = number_format($n / 1000000, $PERCISION) . 'M';
		}else if ($n < 1000000000000) {
			$readable = number_format($n / 1000000000, $PERCISION) . 'B';
		} else {
			$readable = number_format($n / 1000000000000, $PERCISION) . 'G';
		}
			
		return $readable;
	}
	
	static public function getPostPreview($html, $num_chars) 
	{
		if(strlen($html) <= $num_chars) 
			$preview = $html;
		else {
			$preview = '';
			$DOMDocument = new \DOMDocument();
			$dom = $DOMDocument::loadHTML($html); // creates DOCTYPE, <html>, and <body>
			$dom->removeChild($dom->firstChild); // DOCTYPE
			/*
			* If your post is wrapped by a parent element with an id:
			* $node = $dom->getElementById('id')->firstChild
			* Otherwise skip <html> and <body> and grab the first element of your post
			*/
			$node = $dom->firstChild->firstChild->firstChild;
			while(strlen($preview) <  $num_chars) {
				$preview .= $dom->saveHTML($node);
				$node = $node->nextSibling;
			}
			
		}
		return $preview;	
	}
	
	public static function excerpt_paragraph($html, $max_char = 100, $trail='...' )
	{
	    // temp var to capture the p tag(s)
	    $matches= array();
	    if ( preg_match( '/<p>[^>]+<\/p>/', $html, $matches) )
	    {
	        // found <p></p>
	        $p = strip_tags($matches[0]);
	    } else {
	        $p = strip_tags($html);
	    }
	    //shorten without cutting words
	    $p = self::short_str($p, $max_char );
	
	    // remove trailing comma, full stop, colon, semicolon, 'a', 'A', space
	    $p = rtrim($p, ',.;: aA' );
	
	    // return nothing if just spaces or too short
	    if (ctype_space($p) || $p=='' || strlen($p)<10) { return ''; }
	
	    return $p.$trail;
	}
	//
	
	/**
	* shorten string but not cut words
	* 
	**/
	public static function short_str( $str, $len, $cut = false )
	{
	    if ( strlen( $str ) <= $len ) { return $str; }
	    $string = ( $cut ? substr( $str, 0, $len ) : substr( $str, 0, strrpos( substr( $str, 0, $len ), ' ' ) ) );
	    return $string;
	}
	
	public static function shorten_text( $str, $len, $cut = false )
	{
		if ( strlen( $str ) <= $len ) { return $str; }
		$string = ( $cut ? substr( $str, 0, $len ) : substr( $str, 0, strrpos( substr( $str, 0, $len ), ' ' ) ) );
		$string = rtrim($string, ',.;: aA' );
		return $string;
	}	
	
	public static function formatPhoneNumber($phoneNumber) {
	    $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);
	    
	    if(strlen($phoneNumber) > 10) {
	        $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
	        $areaCode = substr($phoneNumber, -10, 3);
	        $nextThree = substr($phoneNumber, -7, 3);
	        $lastFour = substr($phoneNumber, -4, 4);
	        
	        $phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
	    }
	    else if(strlen($phoneNumber) == 10) {
	        $areaCode = substr($phoneNumber, 0, 3);
	        $nextThree = substr($phoneNumber, 3, 3);
	        $lastFour = substr($phoneNumber, 6, 4);
	        
	        $phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
	    }
	    else if(strlen($phoneNumber) == 7) {
	        $nextThree = substr($phoneNumber, 0, 3);
	        $lastFour = substr($phoneNumber, 3, 4);
	        
	        $phoneNumber = $nextThree.'-'.$lastFour;
	    }
	    
	    return $phoneNumber;
	}
	
	
	/**
	 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
	 *
	 * @param string $text String to truncate.
	 * @param integer $length Length of returned string, including ellipsis.
	 * @param string $ending Ending to be appended to the trimmed string.
	 * @param boolean $exact If false, $text will not be cut mid-word
	 * @param boolean $considerHtml If true, HTML tags would be handled correctly
	 *
	 * @return string Trimmed string.
	 */
	public static function truncate_Html($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
	{
	    if ($considerHtml)
	    {
	        // if the plain text is shorter than the maximum length, return the whole text
	        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length)
	        {
	            return $text;
	        }
	        // splits all html-tags to scanable lines
	        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
	        $total_length = strlen($ending);
	        $open_tags = array();
	        $truncate = '';
	        foreach ($lines as $line_matchings)
	        {
	            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
	            if (!empty($line_matchings[1]))
	            {
	                // if it's an "empty element" with or without xhtml-conform closing slash
	                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1]))
	                {
	                    // do nothing
	                    // if tag is a closing tag
	                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
	                    // delete tag from $open_tags list
	                    $pos = array_search($tag_matchings[1], $open_tags);
	                    if ($pos !== false)
	                    {
	                        unset($open_tags[$pos]);
	                    }
	                    // if tag is an opening tag
	                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
	                    // add tag to the beginning of $open_tags list
	                    array_unshift($open_tags, strtolower($tag_matchings[1]));
	                }
	                // add html-tag to $truncate'd text
	                $truncate .= $line_matchings[1];
	            }
	            // calculate the length of the plain text part of the line; handle entities as one character
	            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
	            if ($total_length+$content_length> $length)
	            {
	                // the number of characters which are left
	                $left = $length - $total_length;
	                $entities_length = 0;
	                // search for html entities
	                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE))
	                {
	                    // calculate the real length of all entities in the legal range
	                    foreach ($entities[0] as $entity)
	                    {
	                        if ($entity[1]+1-$entities_length <= $left)
	                        {
	                            $left--;
	                            $entities_length += strlen($entity[0]);
	                        } else {
	                            // no more characters left
	                            break;
	                        }
	                    }
	                }
	                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
	                // maximum lenght is reached, so get off the loop
	                break;
	            } else {
	                $truncate .= $line_matchings[2];
	                $total_length += $content_length;
	            }
	            // if the maximum length is reached, get off the loop
	            if($total_length>= $length)
	            {
	                break;
	            }
	        }
	    } else {
	        if (strlen($text) <= $length)
	        {
	            return $text;
	        } else {
	            $truncate = substr($text, 0, $length - strlen($ending));
	        }
	    }
	    // if the words shouldn't be cut in the middle...
	    if (!$exact)
	    {
	        // ...search the last occurance of a space...
	        $spacepos = strrpos($truncate, ' ');
	        if (isset($spacepos))
	        {
	            // ...and cut the text in this position
	            $truncate = substr($truncate, 0, $spacepos);
	        }
	    }
	    // add the defined ending to the text
	    $truncate .= $ending;
	    if($considerHtml)
	    {
	        // close all unclosed html-tags
	        foreach ($open_tags as $tag)
	        {
	            $truncate .= '</' . $tag . '>';
	        }
	    }
	    return $truncate;
	}
	
	public static function remove_last_token($TEXT, $DELIMITOR)
	{
	    $pos = strrpos($TEXT, $DELIMITOR);
	    
	    if($pos !== false)
	    {
	        $result = substr($TEXT, 0, $pos);
	    }else{
	        $result = $TEXT;
	    }
	    
	    return $result;
	}
	
	static public function isAbbreviation(string $string, string $abbreviation, bool $caseSensitive = false): bool
	{
	    // An empty abbreviation is technically always a subsequence
	    if ($abbreviation === '')
	    {
	        return true;
	    }
	    
	    // Normalize casing if case-insensitive tracking is preferred
	    if (!$caseSensitive)
	    {
	        $string = strtolower($string);
	        $abbreviation = strtolower($abbreviation);
	    }
	    
	    $stringLength = strlen($string);
	    $abbrLength = strlen($abbreviation);
	    
	    $stringPointer = 0;
	    $abbrPointer = 0;
	    
	    // Scan through both strings simultaneously
	    while ($stringPointer < $stringLength && $abbrPointer < $abbrLength)
	    {
	        // If characters match, move the abbreviation pointer forward
	        if ($string[$stringPointer] === $abbreviation[$abbrPointer])
	        {
	            $abbrPointer++;
	        }
	        // Always move the original string pointer forward
	        $stringPointer++;
	    }
	    
	    // If we matched every character in the abbreviation, it is valid
	    return $abbrPointer === $abbrLength;
	}
	
	static public function partial_match($TARGET, $TEXT, $THRESHOLD = 0.75)
	{
	    $txtLength = strlen($TEXT);
	    $TEXT = strtoupper($TEXT);
	    $TARGET = strtoupper($TARGET);
	    $targetLength = strlen($TARGET);
	    
	    $targetPointer = 0;
	    $textPointer = 0;
	               
	    //echo "{$thresholdPointer}  <br>";
	    if($targetLength < 5)
	    {
	        $thresholdPointer = $targetLength;
	    }else{
	        $thresholdPointer = intval($targetLength * $THRESHOLD);
	        
	        if($thresholdPointer < 5)
	        {
	            $thresholdPointer = 5;
	        }
	    }
	    
	    for($textPointer=0; $textPointer < $txtLength; $textPointer++ )
	    {
	        //echo "{$TEXT[$textPointer]} : {$textPointer}, {$TARGET[$targetPointer]} : {$targetPointer} <br>";
	        
	        if($TEXT[$textPointer] == $TARGET[$targetPointer])
	        {
	            $targetPointer++;
	        }else{
	            
	            if($targetPointer >= $thresholdPointer )
	            {
	                return true;
	            }
	            
	            $targetPointer = 0;
	        }
	    }
	    
	    return false;
	}

}