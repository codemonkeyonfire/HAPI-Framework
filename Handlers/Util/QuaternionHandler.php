<?php
 /*************************************************************************
 * 
 *  (c)2011 Carlos Ferrera
 *  All Rights Reserved.
 * 
 * NOTICE:  
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Carlos Ferrera.
 *
 * Email: irondogg@gmail.com
 */
namespace Handlers\Util;

class QuaternionHandler
{
	private $x;
	private $y;
	private $z;
	private $w;
	
	public function __construct($X, $Y, $Z, $W)
	{
        $this->values = new \stdClass();
        
        $this->x = floatval($X);
        $this->y = floatval($Y);
        $this->z = floatval($Z);
        $this->w = floatval($W);
	}
	
	public function setAll($QV)
	{
	    $this->x = floatval($QV[0]);
	    $this->y = floatval($QV[1]);
	    $this->z = floatval($QV[2]);
	    $this->w = floatval($QV[3]);
	}
	
	public function length()
	{
	    return sqrt($this->x * $this->x + $this->y * $this->y + $this->z * $this->z + $this->w * $this->w);
	}
	
	public function normalize()
	{
		$len = $this->length();

		return new QuaternionHandler($this->x/$len, $this->y/$len, $this->z/$len, $this->w/$len);
	}
	
	public function conjugate()
	{
	    return new QuaternionHandler(-$this->x, -$this->y, -$this->z, $this->w);
	}
	
	public function mul($Q)
	{
	    $w_ = $this->w * $Q->get("w") - $this->x * $Q->get("x") - $this->y * $Q->get("y") - $this->z * $Q->get("z");
	    $x_ = $this->x * $Q->get("w") + $this->w * $Q->get("x") + $this->y * $Q->get("z") - $this->z * $Q->get("y");
	    $y_ = $this->y * $Q->get("w") + $this->w * $Q->get("y") + $this->z * $Q->get("x") - $this->x * $Q->get("z");
	    $z_ = $this->z * $Q->get("w") + $this->w * $Q->get("z") + $this->x * $Q->get("y") - $this->y * $Q->get("x");
	    
	    $res = new QuaternionHandler($x_, $y_, $z_, $w_);
	    return $res;
	}
	
	public function vMul($V)
	{
	    $w_ = -$this->x * $V[0] - $this->y * $V[1] - $this->z * $V[2];
	    $x_ = $this->w * $V[0] + $this->y * $V[2] - $this->z * $V[1];
	    $y_ = $this->w * $V[1] + $this->z * $V[0] - $this->x * $V[2];
	    $z_ = $this->w * $V[2] + $this->x * $V[1] - $this->y * $V[0];
	    
	    $res = new QuaternionHandler($x_, $y_, $z_, $w_);
	    return $res;
	}
	
	public function get($DIM)
	{
	    return $this->$DIM;
	}
	
	public function set($DIM, $VAL)
	{
	    $this->$DIM = $VAL;
	}
}
?>