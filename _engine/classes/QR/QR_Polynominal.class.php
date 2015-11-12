<?php
/**
 * This file is part of the phpQr package
 *
 * See @see QR_Code class for description of package and license.
 */

/**
 * Import necessary dependencies
 */
//require_once 'QR_Util.php';
//require_once 'QR_Math.php';
//require_once 'QR_CodeException.php';

/**
 * Derived exception
 *
 * @author Maik Greubel <greubel@nkey.de>
 * @package phpQr
 */
class QR_PolynominalException extends QR_CodeException{};

/**
 * The purpose of this class is to provide a polynominal implementation for the QR_Code package
 * 
 * @author Maik Greubel <greubel@nkey.de>
 * @package phpQr
 */
class QR_Polynominal
{
  /**
   * Bitmap
   * 
   * @var array
   */
  private $num;
  
  /**
   * Create a new QR_Polynominal instance
   * 
   * @param array $num
   * @param int $shift
   * 
   * @throws QR_PolynominalException
   */
  public function __construct($num, $shift)
  {
    if(sizeof($num) == 0)
    {
      throw new QR_PolynominalException("Invalid num size");
    }
    
    $offset = 0;
    while($offset < sizeof($num) && $num[$offset] == 0)
    {
      $offset++;
    }
    
    $this->num = QR_Util::getInstance()->createEmptyArray(sizeof($num) - $offset + $shift);
    for($i = 0; $i < sizeof($num) - $offset; $i++)
    {
      $this->num[$i] = $num[$i + $offset];
    }
  }
  
  /**
   * Get a particular bitmap index
   * 
   * @param int $index
   * @return multitype:
   */
  public function get($index)
  {
    return $this->num[$index];
  }
  
  /**
   * Get the length of bitmap
   */
  public function getLength()
  {
    return sizeof($this->num);
  }
  
  /**
   * Multiply another polynom against this
   * 
   * @param QR_Polynominal $e The other
   * @return QR_Polynominal The multiplied result
   */
  public function multiply(QR_Polynominal $e)
  {
    $num = QR_Util::getInstance()->createEmptyArray($this->getLength() + $e->getLength() - 1);
    
    for($i = 0; $i < $this->getLength(); $i++)
    {
      for($j = 0; $j < $e->getLength(); $j++)
      {
        $a = QR_Math::getInstance()->glog($this->get($i));
        $b = QR_Math::getInstance()->glog($e->get($j));
        
        $base = 0;
        if(isset($num[$i + $j]))
          $base = $num[$i + $j];
        $num[$i + $j] = $base ^ QR_Math::getInstance()->gexp( $a + $b );
      }
    }
    
    return new QR_Polynominal($num, 0);
  }
  
  /**
   * Perform modulus against another polynom
   * 
   * @param QR_Polynominal $e
   * 
   * @return QR_Polynominal
   */
  public function mod(QR_Polynominal $e)
  {
    if($this->getLength() - $e->getLength() < 0)
    {
      return $this;
    }
    
    $ratio = QR_Math::getInstance()->glog($this->get(0)) - QR_Math::getInstance()->glog($e->get(0));
    
    $num = QR_Util::getInstance()->createEmptyArray($this->getLength());
    
    for($i = 0; $i < $this->getLength(); $i++)
    {
      $num[$i] = $this->get($i);
    }
    
    for($i = 0; $i < $e->getLength(); $i++)
    {
      $num[$i] ^= QR_Math::getInstance()->gexp(QR_Math::getInstance()->glog($e->get($i)) + $ratio);
    }
    
    $result = new QR_Polynominal($num, 0);
    $result = $result->mod($e);
    
    return $result;
  }
}