<?php
namespace Handlers\Util;

class WidgetHandler
{
	private $SETTINGS;
	
	public function __construct($SETTINGS)
	{
		//  set static settings here 
		$this->SETTINGS = $SETTINGS;
	}	
	
	public function Percent_Bar($VAL, $MAX, $HEIGHT="3", $COLOR='ba1126')
	{
		if($MAX == 0) $MAX = 100;
		
		$percent =  number_format((($VAL / $MAX) * 100), 0, ',', ' ');
		$percent = ($percent > 100)?100:$percent;

		if($HEIGHT > 15)
		{
			$left = ($percent > 33)?$VAL:"";
			$right = ($percent <= 33)?$VAL:"";
		}
		
		if($percent < 1) $visible = "display: none;";
		
		$output = "<div style=\"width: 100px; background-color: #000000; height: {$HEIGHT}px; font-size:10px;\">";
		$output .= "<div style=\"background-color: #{$COLOR}; {$visible} font-size:10px; width: {$percent}px; height: {$HEIGHT}px;\">";
		$output .= "$left";
		$output .= "</div>";
		$output .= " <div style=\" position: relative; top: -17px; left: -3px; float: right;\">{$right}</div>";
		$output .= "</div>\n";

		return $output;
	}
	
	public function Select_Box($ID, $DATA, $SELECTED, $CLASS, $ACTION)
	{
		$strOUTPUT = "<select name='$ID' id='$ID' class=\"$CLASS\" $ACTION>\n";
		
		if($SELECTED == '') $strOUTPUT .= "<option value=0 SELECTED>Select Value</option>\n";	
		
		foreach ($DATA as $ARR)
		{
			$OPT = ($ARR[0] == $SELECTED)?"SELECTED":"";
			$strOUTPUT .= "<option value=\"".$ARR[0]."\" $OPT>".$ARR[1]."</option>\n";	
		}		
		
		$strOUTPUT .= "</select>\n";	

		return $strOUTPUT;
	}
	
	public function CarpeSliderInit()
	{
?>
		<script>
		/* This script and many more are available free online at
		The JavaScript Source!! http://javascript.internet.com
		Created by: Tom Hermansson Snickars | http://carpe.ambiprospect.com/ */
		//------------------------------------------+
		//  CARPE  S l i d e r  v. 1.5  2006-01-03  |
		//  By Tom Hermansson Snickars              |
		//  Copyright CARPE Design                  |
		//  http://carpe.ambiprospect.com/          |
		//------------------------------------------+
		
		var carpeDefaultSliderLength      = 100
		var carpeSliderDefaultOrientation = 'horizontal'
		var carpeSliderClassName          = 'carpe_slider'
		var carpeSliderDisplayClassName   = 'carpe_slider_display'
		
		function carpeGetElementsByClass(className) {
		  var classElements = new Array()
		  var els = document.getElementsByTagName("*")
		  var elsLen = els.length
		  var pattern = new RegExp("\\b" + className + "\\b")
		  for (i = 0, j = 0; i < elsLen; i++) {
		    if ( pattern.test(els[i].className) ) {
		      classElements[j] = els[i]
		      j++
		    }
		  }
		  return classElements;
		}
		
		function carpeLeft(elmnt, pos) {
		  if (!(elmnt = document.getElementById(elmnt))) return 0;
		  if (elmnt.style && (typeof(elmnt.style.left) == 'string')) {
		    if (typeof(pos) == 'number') elmnt.style.left = pos + 'px';
		    else {
		      pos = parseInt(elmnt.style.left);
		      if (isNaN(pos)) pos = 0;
		    }
		  } else if (elmnt.style && elmnt.style.pixelLeft) {
		    if (typeof(pos) == 'number') elmnt.style.pixelLeft = pos;
		    else pos = elmnt.style.pixelLeft;
		  }
		  return pos;
		}
		
		function carpeTop(elmnt, pos) {
		  if (!(elmnt = document.getElementById(elmnt))) return 0;
		  if (elmnt.style && (typeof(elmnt.style.top) == 'string')) {
		    if (typeof(pos) == 'number') elmnt.style.top = pos + 'px';
		    else {
		      pos = parseInt(elmnt.style.top);
		      if (isNaN(pos)) pos = 0;
		    }
		  } else if (elmnt.style && elmnt.style.pixelTop) {
		    if (typeof(pos) == 'number') elmnt.style.pixelTop = pos;
		    else pos = elmnt.style.pixelTop;
		  }
		  return pos;
		}
		
		function moveSlider(evnt) {
		  var evnt = (!evnt) ? window.event : evnt;
		  if (mouseover) {
		    x = slider.startOffsetX + evnt.screenX
		    y = slider.startOffsetY + evnt.screenY
		    if (x > slider.xMax) x = slider.xMax
		    if (x < 0) x = 0
		    if (y > slider.yMax) y = slider.yMax
		    if (y < 0) y = 0
		    carpeLeft(slider.id, x)
		    carpeTop(slider.id, y)
		    sliderVal = x + y
		    sliderPos = (slider.distance / display.valuecount) *
		      Math.round(display.valuecount * sliderVal / slider.distance)
		    v = Math.round((sliderPos * slider.scale + slider.from) *
		      Math.pow(10, display.decimals)) / Math.pow(10, display.decimals)
		    display.value = v
		    return false
		  }
		  return
		}
		
		function slide(evnt) {
		  if (!evnt) evnt = window.event;
		  slider = (evnt.target) ? evnt.target : evnt.srcElement;
		  dist = parseInt(slider.getAttribute('distance'))
		  slider.distance = dist ? dist : carpeDefaultSliderLength
		  ori = slider.getAttribute('orientation')
		  orientation = ((ori == 'horizontal') || (ori == 'vertical')) ? ori : carpeSliderDefaultOrientation
		  displayId = slider.getAttribute('display')
		  display = document.getElementById(displayId)
		  display.sliderId = slider.id
		  dec = parseInt(display.getAttribute('decimals'))
		  display.decimals = dec ? dec : 0
		  val = parseInt(display.getAttribute('valuecount'))
		  display.valuecount = val ? val : slider.distance + 1
		  from = parseFloat(display.getAttribute('from'))
		  from = from ? from : 0
		  to = parseFloat(display.getAttribute('to'))
		  to = to ? to : slider.distance
		  slider.scale = (to - from) / slider.distance
		  if (orientation == 'vertical') {
		    slider.from = to
		    slider.xMax = 0
		    slider.yMax = slider.distance
		    slider.scale = -slider.scale
		  } else {
		    slider.from = from
		    slider.xMax = slider.distance
		    slider.yMax = 0
		  }
		  slider.startOffsetX = carpeLeft(slider.id) - evnt.screenX
		  slider.startOffsetY = carpeTop(slider.id) - evnt.screenY
		  mouseover = true
		  document.onmousemove = moveSlider
		  document.onmouseup = sliderMouseUp
		  return false
		}
		
		function sliderMouseUp() {
		  if (mouseover) {
		    v = (display.value) ? display.value : 0
		    pos = (v - slider.from)/(slider.scale)
		    if (slider.yMax == 0) {
		      pos = (pos > slider.xMax) ? slider.xMax : pos
		      pos = (pos < 0) ? 0 : pos
		      carpeLeft(slider.id, pos)
		    }
		    if (slider.xMax == 0) {
		      pos = (pos > slider.yMax) ? slider.yMax : pos
		      pos = (pos < 0) ? 0 : pos
		      carpeTop(slider.id, pos)
		    }
		    if (document.removeEventListener) {
		      document.removeEventListener('mousemove', moveSlider, false)
		      document.removeEventListener('mouseup', sliderMouseUp, false)
		    }
		    else if (document.detachEvent) {
		      document.detachEvent('onmousemove', moveSlider)
		      document.detachEvent('onmouseup', sliderMouseUp)
		    }
		  }
		  mouseover = false
		}
		
		function focusDisplay(evnt) {
		  if (!evnt) evnt = window.event;
		  display = (evnt.target) ? evnt.target : evnt.srcElement;
		  lock = display.getAttribute('typelock')
		  if (lock == 'on') {
		    display.blur()
		  }
		  return
		}
		
		window.onload = function() {
		  sliders = carpeGetElementsByClass(carpeSliderClassName)
		  for (i = 0; i < sliders.length; i++) {
		    sliders[i].onmousedown = slide
		  }
		  displays = carpeGetElementsByClass(carpeSliderDisplayClassName)
		  for (i = 0; i < displays.length; i++) {
		    displays[i].onfocus = focusDisplay
		  }
		}
		</script>

<?php		
	}
	
	public function CarpeSliderCSS()
	{
?>
		<style>
		/* Default stylesheet for the Carpe Slider
		  By Tom Hermansson Snickars
		  2005-12-17 version 1.5
		  Copyright CARPE Design
		  carpe.ambiprospect.com */
		
		*.carpe_horizontal_slider_display_combo {
		  clear: left;
		  margin: 0;
		}
		*.carpe_vertical_slider_display_combo {
		  float: left;
		  margin: 0;
		}
		*.carpe_horizontal_slider_track {
		  background-color: #bbb;
		  color: #333;
		  width: 120px;
		  float: left;
		  margin: 0;
		  line-height: 0px;
		  font-size: 0px;
		  text-align: left;
		  padding: 4px;
		  border: 1px solid;
		  border-color: #ddd #999 #999 #ddd;
		}
		*.carpe_vertical_slider_track {
		  background-color: #bbb;
		  color: #333;
		  padding: 3px 6px 15px 6px;
		  width: 24px;
		  height: 100px;
		  border: 1px solid;
		  border-color: #ddd #999 #999 #ddd;
		}
		*.carpe_horizontal_slider_track *.carpe_slider_slit {
		  background-color: #333;
		  color: #ccc;
		  width: 110px;
		  height: 2px;
		  margin: 4px 4px 2px 4px;
		  line-height: 0px;
		  position: absolute;
		  z-index: 1;
		  border: 1px solid;
		  border-color: #999 #ddd #ddd #999;
		}
		*.carpe_vertical_slider_track *.carpe_slider_slit {
		  background-color: #000;
		  color: #333;
		  width: 2px;
		  height: 100px;
		  position: absolute;
		  margin: 4px 10px 4px 10px;
		  padding: 4px 0 1px 0;
		  line-height: 0px;
		  font-size: 0;
		  border: 1px solid;
		  border-color: #666 #ccc #ccc #666;
		}
		*.carpe_horizontal_slider_track *.carpe_slider {
		  width: 16px;
		  background-color: #666;
		  color: #333;
		  position: relative;
		  margin: 0;
		  height: 8px;
		  z-index: 1;
		  line-height: 0px;
		  font-size: 0px;
		  text-align: left;
		  border: 2px solid;
		  border-color: #999 #333 #333 #999;
		}
		*.carpe_vertical_slider_track *.carpe_slider {
		  width: 20px;
		  background-color: #666;
		  color: #333;
		  position: relative;
		  margin: 0;
		  height: 8px;
		  z-index: 1;
		  line-height: 0px;
		  font-size: 0px;
		  text-align: left;
		  border: 2px solid;
		  border-color: #999 #333 #333 #999;
		}
		*.carpe_slider_display_holder {
		  background-color: #bbb;
		  color: #333;
		  width: 34px;
		  margin: 0;
		  float: left;
		  padding: 0 2px 0 0;
		  height: 20px;
		  text-align: right;
		  border: 1px solid;
		  border-color: #ddd #999 #999 #ddd;
		}
		.carpe_slider_display {
		  background-color: #bbb;
		  color: #333;
		  padding: 3px 1px 0 0;
		  width: 30px;
		  text-align: right;
		  font-size: 11px;
		  line-height: 10px;
		  font-family: verdana, arial, helvetica, sans-serif;
		  font-weight: bold;
		  border: 0;
		  cursor: default;
		}
		<style>
<?php
	}
	
	public function CarpeSliderHorizontal($ID, $ORIENT)
	{
?>
		<div class="carpe_horizontal_slider_display_combo">
		  <div class="carpe_horizontal_slider_track" style="background-color: #bcd; border-color: #def #9ab #9ab #def;">
		    <div class="carpe_slider_slit" style="background-color: #003; border-color: #99b #ddf #ddf #99b;"> </div>
		    <div
		      class="carpe_slider"
		      id="slider4"
		      display="display4"
		      style="left: 50px; background-color: #369; border-color: #69c #036 #036 #69c;"> </div>
		  </div>
		  <div class="carpe_slider_display_holder" style="background-color: #bcd; border-color: #def #9ab #9ab #def;">
		    <input
		      class="carpe_slider_display"
		      id="display4"
		      style="background-color: #bcd; color: #258;"
		      type="text"
		      from="0.1"
		      to="0.3"
		      valuecount="3"
		      decimals="1"
		      value="0.2" />
		  </div>
		</div>
<?php
	}
	
	public function CarpeSliderVertical()
	{
?>
        <div class="carpe_vertical_slider_display_combo">
		    <div class="carpe_vertical_slider_track" style="height: 50px; background-color: #fff; border-color: #fff;">
		      <div class="carpe_slider_slit" style="height: 50px; "> </div>
		      <div
		        class="carpe_slider"
		        id="slider6"
		        orientation="vertical"
		        distance="50"
		        display="display6"
		        style="top: 25px; background-color: #696; border-color: #9c9 #363 #363 #9c9;"> </div>
		    </div>
		    <div class="carpe_slider_display_holder" style="background-color: #fff; border-color: #fff;">
		      <input
		        class="carpe_slider_display"
		        id="display6"
		        style="background: #fff;"
		        type="text"
		        from="-250"
		        to="250"
		        valuecount="50"
		        value="0"
		        typelock="on" />
		    </div>
		  </div>

<?php
	}
	
	public function PopUp($OPTS, $W, $H, $FUNC="")
	{
		echo "<script>";
		if($FUNC != "")
			echo "function ".$FUNC."(){";
?>
win=window.open('popup.php?<?php echo $OPTS?>','myname','width=<?php echo $W?>,height=<?php echo $H?>,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes,scrollbars=no');
<?	
		if($FUNC != "")
			echo "}";
		
		echo "</script>";
	}

}

?>