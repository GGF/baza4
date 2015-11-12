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
 * Derived exception class
 *  
 * @author Maik Greubel <greubel@nkey.de>
 * @package phpQr
 */
class QR_CodeImageException extends QR_CodeException{};

/**
 * This class provides all needed functionality to create an image out of an QR_Code bitmap
 * 
 * @author Maik Greubel <greubel@nkey.de>
 * @package phpQr
 */
class QR_CodeImage
{
  /**
   * The previously created QR_Code
   * 
   * @var QR_Code
   */
  private $qrcode;
  
  /**
   * The desired width of the destination image
   * 
   * @var int
   */
  private $width;

  /**
   * The desired height of the destination image
   * 
   * @var int
   */
  private $height;
  
  /**
   * Quality of the destination image
   * 
   * @var int
   */
  private $quality;
  
  /**
   * The image buffer provided by GD function imagecreate()
   * 
   * @var resource
   */
  private $img;
  
  /**
   * Create a new QR_CodeImage instance
   * 
   * @param QR_Code $qrcode The previously created QR_Code
   * @param int $width The desired width of the destination image
   * @param int $height The desired height of the destination image
   * @param int $quality The desired quality of the destination image
   */
  public function __construct(QR_Code $qrcode, $width, $height, $quality = 90)
  {
    $this->qrcode = $qrcode;
    $this->width  = $width;
    $this->height = $height;
    $this->quality = $quality;
    $this->img    = null;
  }
  
  /**
   * Draw the image
   */
  public function draw()
  {
    $moduleCount = $this->qrcode->getModuleCount();
    $tileWidth  = $this->width / $moduleCount;
    $tileHeight = $this->height / $moduleCount;
    
    $this->img = imagecreatetruecolor($this->width, $this->height);
    
    if($this->img)
    {
      $fg = imagecolorallocate($this->img, 0, 0, 0);
      if($fg === false)
      {
        $this->finish();
        throw new QR_CodeImageException('Could not allocate foreground color!');
      }
      $bg = imagecolorallocate($this->img, 255, 255, 255);
      if($bg === false)
      {
        $this->finish();
        throw new QR_CodeImageException('Could not allocate background color!');
      }
      
      for($row = 0; $row < $moduleCount; $row++)
      {
        for($col = 0; $col < $moduleCount; $col++)
        {
          $fillStyle = $this->qrcode->isDark($row, $col) ? $fg : $bg;
          
          $x = round($col * $tileWidth);
          $y = round($row * $tileHeight);
          $w = (ceil(($col + 1) * $tileWidth) - floor($col * $tileWidth));
          if($x + $w > $this->width)
          {
            $w = $this->width - $x;
          }
          $h = (ceil(($row + 1) * $tileWidth) - floor($row * $tileWidth));
          if($y + $h > $this->height)
          {
            $h = $this->height - $y;
          }
          
          if(!imagefilledrectangle($this->img, $x, $y, $x + $w, $y + $h, $fillStyle))
          {
            $this->finish();
            throw new QR_CodeImageException(sprintf('Could not fill the rectangle using desired coordinates (x = %d, y = %d, w = %d, h = %d, c = %d)',
                $x, $y, $w, $h, $fillStyle));
          }
        }
      }
    }
    else
    {
      throw new QR_CodeImageException('Could not create true color image buffer!');
    }
  }
  
  /**
   * Store the image
   * 
   * @param string $filename
   */
  public function store($filename)
  {
    if($this->img)
    {
      if(!imagejpeg($this->img, $filename, $this->quality))
      {
        throw new QR_CodeImageException(sprintf('Could not save image to file %s', $filename));
      }
    }
  }
  
  /**
   * Return the image as string
   */
  public function getImage()
  {
    if($this->img)
    {
      ob_start();
      if(!imagejpeg($this->img, null, $this->quality))
      {
        ob_end_flush();
        throw new QR_CodeImageException('Could not create a jpeg out of the image buffer!');
      }
      $out = ob_get_clean();
      return $out;
    }
    throw new QR_CodeImageException('No image data available!');
  }
  
  /**
   * Clean the image buffer
   */
  public function finish()
  {
    if($this->img)
    {
      imagedestroy($this->img);
      $this->img = null;
    }
  }
}