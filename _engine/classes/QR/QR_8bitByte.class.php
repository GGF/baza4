<?php
/**
 * This file is part of the phpQr package
 * 
 * See @see QR_Code class for description of package and license.
 */

/**
 * Import necessary dependencies
 */
//require_once 'QR_Byte.php';
//require_once 'QR_Mode.php';

/**
 * This class provides the 8bit Byte implementaton of a QR_Byte
 * 
 * @author Maik Greubel <greubel@nkey.de>
 * @package phpQr
 */
class QR_8bitByte implements QR_Byte
{
  /**
   * The data
   * @var array
   */
  private $data;
  
  /**
   * The mode
   * @var unknown
   */
  private $mode;
  
  /**
   * Retrieve the mode
   * 
   * @return int The mode
   * @see QR_Byte::getMode()
   */
  public function getMode()
  {
    return $this->mode;
  }
  
  /**
   * Retrieve the length
   * 
   * @return int The length
   * @see QR_Byte::getLength()
   */
  public function getLength()
  {
    return strlen($this->data);    
  }
  
  /**
   * Write data to byte
   * 
   * @param QR_BitBuffer $buffer The data to write into byte
   * 
   * @see QR_Byte::write()
   */
  public function write(QR_BitBuffer $buffer)
  {
    for($i = 0; $i < strlen($this->data); $i++)
    {
      $buffer->put(ord($this->data[$i]), 8);
    }
  }
  
  /**
   * Create a new instance of a QR_8bitByte
   * 
   * @param array $data The data for the Byte
   */
  public function __construct($data)
  {
    $this->data = $data;
    $this->mode = QR_Mode::MODE_8BIT_BYTE;
  }
}
?>