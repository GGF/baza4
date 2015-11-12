<?php
/**
 * This file is part of the phpQr package
 *
 * See @see QR_Code class for description of package and license.
 */

/**
 * Import necessary dependencies
 */
//require_once 'QR_CodeException.php';

/**
 * Derived exception
 * 
 * @author Maik Greubel <greubel@nkey.de>
 * @package phpQr
 */
class QR_MathException extends QR_CodeException
{
}

/**
 * QR_ Code math helper class
 *
 * @author Maik Greubel <greubel@nkey.de>
 * @package phpQr
 */
final class QR_Math
{
  /**
   * Exponent table
   *
   * @var array
   */
  private $EXP_TABLE = null;
  
  /**
   * Logarithm table
   * 
   * @var array
   */
  private $LOG_TABLE = null;
  
  /**
   * Singleton pattern
   * 
   * @var QR_Math
   */
  private static $instance = null;
  
  /**
   * Singleton pattern
   *
   * @return QR_Math Singleton
   */
  public static function getInstance()
  {
    if (! self::$instance)
    {
      self::$instance = new self ();
    }
    
    return self::$instance;
  }
  
  /**
   * Create a new instance of QR_Math
   */
  private function __construct()
  {
    $this->init ();
  }
  
  /**
   * Initialize the tables
   */
  private function init()
  {
    $this->EXP_TABLE = array ();
    for($i = 0; $i < 8; $i ++)
    {
      $this->EXP_TABLE [$i] = 1 << $i;
    }
    
    for($i = 8; $i < 256; $i ++)
    {
      $this->EXP_TABLE [$i] = $this->EXP_TABLE [$i - 4] ^ $this->EXP_TABLE [$i - 5] ^ $this->EXP_TABLE [$i - 6] ^ $this->EXP_TABLE [$i - 8];
    }
    
    $this->LOG_TABLE = array ();
    for($i = 0; $i < 255; $i ++)
    {
      $this->LOG_TABLE [$this->EXP_TABLE [$i]] = $i;
    }
  }
  
  /**
   * Get logarithm of n
   *
   * @param int $n          
   * @throws QR_MathException
   * @return int
   */
  public function glog($n)
  {
    if ($n < 1)
    {
      throw new QR_MathException ( "glog(" . $n . ")" );
    }
    
    foreach ( $this->LOG_TABLE as $key => $value )
    {
      if ($key == $n)
        return $value;
    }
    
    throw new QR_MathException ( "glog($n)" );
  }
  
  /**
   * Get the exponent of n
   *
   * @param int $n          
   * @return int
   */
  public function gexp($n)
  {
    while ( $n < 0 )
    {
      $n += 255;
    }
    while ( $n >= 256 )
    {
      $n -= 255;
    }
    foreach ( $this->EXP_TABLE as $key => $value )
    {
      if ($key == $n)
        return $value;
    }
    
    throw new QR_MathException ( "gexp($n)" );
  }
} 

?>