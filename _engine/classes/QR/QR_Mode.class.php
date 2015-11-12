<?php
/**
 * This file is part of the phpQr package
 *
 * See @see QR_Code class for description of package and license.
 */

/**
 * QR_Code mode enumeration
 * 
 * @author Maik Greubel <greubel@nkey.de>
 * @package phpQr
 */
abstract class QR_Mode
{
  const MODE_NUMBER = 1;
  const MODE_ALPHA_NUM = 2;
  const MODE_8BIT_BYTE = 4;
  const MODE_KANJI = 8;
}
