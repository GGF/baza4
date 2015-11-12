<?php
/**
 * This file is part of the phpQr package
 *
 * See @see QR_Code class for description of package and license.
 */

/**
 * Import necessary dependencies
 */
//require_once 'QR_BitBuffer.php';

/**
 * This interface describes a QR_Byte implementation
 * 
 * @author Maik Greubel <greubel@nkey.de>
 * @package phpQr
 */
interface QR_Byte
{
  /**
   * Retrieve the mode
   * 
   * @return  int The mode
   */
  public function getMode();
  
  /**
   * Retrieve the length
   * 
   * @return int The length
   */
  public function getLength();
  
  /**
   * Write data to byte
   * 
   * @param QR_BitBuffer $buffer The data to write into byte
   */
  public function write(QR_BitBuffer $buffer);
}