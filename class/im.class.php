<?php
class ImageManipulate
{
    // proprietà 
    public $CachePath = '';
    private $w;
    private $h;
    private $c;
    private $s;
    private $img;
    public $filename;
    public $cachedfile;
    public $output;
    // metodi
    public function __construct($filename = '', $w = 300, $h = 300, $c = 1, $s = 0, $output = 1)
    {
        //inizializzazione della proprietÃ  $name    
        $this->filename = $filename;
        $this->w        = $w;
        $this->h        = $h;
        $this->c        = $c;
        $this->s        = $s;
        $this->output   = $output;
    }
    
    private function orient_image($file_path)
    {
        
        if (!function_exists('exif_read_data')) {
            return false;
        }
        
        $exif = @exif_read_data($file_path);
        
        if ($exif === false) {
            return false;
        }
        
        $orientation = intval(@$exif['Orientation']);
        
        if (!in_array($orientation, array(
            3,
            6,
            8
        ))) {
            return false;
        }
        
        $image = @imagecreatefromjpeg($file_path);
        switch ($orientation) {
            case 3:
                $image = @imagerotate($image, 180, 0);
                break;
            case 6:
                $image = @imagerotate($image, 270, 0);
                break;
            case 8:
                $image = @imagerotate($image, 90, 0);
                break;
            default:
                return false;
        }
        
        $success = imagejpeg($image, $file_path);
        @imagedestroy($image);
        return $success;
    }
    
    private function filter($filtertype, $arg1, $arg2, $arg3, $arg4)
    {
        /*
        IMG_FILTER_NEGATE: Reverses all colors of the image.
        IMG_FILTER_GRAYSCALE: Converts the image into grayscale.
        IMG_FILTER_BRIGHTNESS: Changes the brightness of the image. Use arg1 to set the level of brightness. The range for the brightness is -255 to 255.
        IMG_FILTER_CONTRAST: Changes the contrast of the image. Use arg1 to set the level of contrast.
        IMG_FILTER_COLORIZE: Like IMG_FILTER_GRAYSCALE, except you can specify the color. Use arg1, arg2 and arg3 in the form of red, green, blue and arg4 for the alpha channel. The range for each color is 0 to 255.
        IMG_FILTER_EDGEDETECT: Uses edge detection to highlight the edges in the image.
        IMG_FILTER_EMBOSS: Embosses the image.
        IMG_FILTER_GAUSSIAN_BLUR: Blurs the image using the Gaussian method.
        IMG_FILTER_SELECTIVE_BLUR: Blurs the image.
        IMG_FILTER_MEAN_REMOVAL: Uses mean removal to achieve a "sketchy" effect.
        IMG_FILTER_SMOOTH: Makes the image smoother. Use arg1 to set the level of smoothness.
        IMG_FILTER_PIXELATE: Applies pixelation effect to the image, use arg1 to set the block size and arg2 to set the pixelation effect mode.
        */
        switch ($filtertype) {
            case 0:
                imagefilter($this->img, $filtertype);
                break;
            case 1:
                imagefilter($this->img, $filtertype);
                break;
            case 2:
                imagefilter($this->img, $filtertype, $arg1);
                break;
            case 3:
                imagefilter($this->img, $filtertype, $arg1);
                break;
            case 4:
                imagefilter($this->img, $filtertype, $arg1, $arg2, $arg3);
                break;
            case 5:
                imagefilter($this->img, $filtertype);
                break;
            case 6:
                imagefilter($this->img, $filtertype);
                break;
            case 7:
                imagefilter($this->img, $filtertype);
                break;
            case 8:
                imagefilter($this->img, $filtertype);
                break;
            case 9:
                imagefilter($this->img, $filtertype);
                break;
            case 10:
                imagefilter($this->img, $filtertype, $arg1);
                break;
            case 11:
                imagefilter($this->img, $filtertype, $arg1, $arg2);
                break;
        }
        
    }

	public function grab_screen($new_w=800,$new_h=600,$c=0,$s=0)
	{
		$this->img = imagegrabscreen();
		
		$this->filename = "desktop_" . date("Y_m_d") . ".png";
        $this->w        = $new_w;
        $this->h        = $new_h;
        $this->c        = $c;
        $this->s        = $s;
//        $this->cachedfile = $this->CachePath . "grab_" . sha1($this->filename).".png";
		if ($c==1)
		 $this->img = $this->redim_whc($this->img,$this->w,$this->h);	
		else
		 $this->img = $this->redim_wh($this->img,$this->w,$this->h);	
		
		
//		imagepng($this->img,$this->cachedfile);
		imagepng($this->img);

	}
    
    public function asciize($filename = '', $chars = 'Battello', $shrpns = 1, $size = 4, $weight = 2, $output = 1, $w = 800, $h = 600, $c = 0)
    {
        
        if ($filename == '')
            $filename = $this->filename;
        $ext = substr(strrchr($filename, '.'), 1);
        
        if (file_exists($this->cachedfile)) {
            //echo "esiste". $cachedfile . "<br>"; 
            
            if (strtolower($ext) == 'gif')
                $img = imagecreatefromgif($this->cachedfile);
            
            if ((strtolower($ext) == 'png')) {
                $img = imagecreatefrompng($this->cachedfile);
            }
            
            
            if (((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg'))) {
                $img = imagecreatefromjpeg($this->cachedfile);
            }
            
        } else {
            list($wo, $ho, $type) = getimagesize($filename);
            $resource = imagecreatefromstring(file_get_contents($filename));
            $img      = imagecreatetruecolor($wo * $size, $ho * $size);
            $cc       = strlen($chars);
            for ($y = 0; $y < $ho; $y += $shrpns)
                for ($x = 0; $x < $wo; $x += $shrpns)
                    imagestring($img, $weight, $x * $size, $y * $size, $chars{@++$p % $cc}, imagecolorat($resource, $x, $y));
        }
        
        
        if ($c == 0)
            $img = $this->redim_wh($img, $w, $h);
        else
            $img = $this->redim_whc($img, $w, $h);
        $this->cachedfile = $this->CachePath . "shrpns_" . $shrpns . "size_" . $size . "_w" . $weight . "_" . sha1($filename) . sha1($chars) . "_wh" . $w . "x" . $h . "_c" . $c . "." . $ext;
        
        if ((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg')) {
            imagejpeg($img, $this->cachedfile, 85);
        }
        
        
        if ((strtolower($ext) == 'gif')) {
            imagegif($img, $this->cachedfile);
        }
        
        
        if ((strtolower($ext) == 'png')) {
            //echo $imc;     
            imagealphablending($img, false);
            imagesavealpha($img, true);
            imagepng($img, $this->cachedfile);
        }
        
        
        if ($output == 1) {
            //echo $imc;
            
            if ((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg')) {
                imagejpeg($img, null, 85);
            }
            
            
            if ((strtolower($ext) == 'gif')) {
                imagegif($img);
            }
            
            
            if ((strtolower($ext) == 'png')) {
                //echo $imc;     
                imagealphablending($img, false);
                imagesavealpha($img, true);
                imagepng($img);
            }
            
        }
        
    }
    
    public function extension_to_image_type($ext)
    {
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
                break;
            case 'png':
                return 'image/png';
                break;
            case 'pdf':
                return 'image/png';
                break;
            case 'gif':
            default:
                return 'image/gif';
                break;
        }
        
    }
    
    private function setTransparency($new_image, $image_source)
    {
        $transparencyIndex = imagecolortransparent($image_source);
        $transparencyColor = array(
            'red' => 255,
            'green' => 255,
            'blue' => 255
        );
        
        if ($transparencyIndex >= 0) {
            $transparencyColor = imagecolorsforindex($image_source, $transparencyIndex);
        }
        
        $transparencyIndex = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
        imagefill($new_image, 0, 0, $transparencyIndex);
        imagecolortransparent($new_image, $transparencyIndex);
    }
    
    public function resize($filename, $w = 300, $h = 300, $c = 1, $s = 0)
    {
        $this->filename = $filename;
        $this->s        = $s;
        $this->c        = $c;
        $this->w        = $w;
        $this->h        = $h;
        //echo $filename."<br />";
        $ext            = substr(strrchr($filename, '.'), 1);
        
        if (!file_exists($this->CachePath))
            mkdir($this->CachePath);
        $this->cachedfile = $this->CachePath . "s" . $this->s . "px_c" . $this->c . "_" . $this->w . "x" . $this->h . "px_" . sha1($filename) . "." . $ext;
        $this->create_cached();
        //return ($cachedfile);
    }
    
    public function ApplyFilter($filename, $filtertype, $w = 300, $h = 300, $c = 1, $s = 0, $arg1 = 128, $arg2 = 128, $arg3 = 128, $arg4 = 128)
    {
        $this->filename = $filename;
        $this->s        = $s;
        $this->c        = $c;
        $this->w        = $w;
        $this->h        = $h;
        $this->output   = 1;
        $ext            = substr(strrchr($filename, '.'), 1);
        
        if (strtolower($ext) == 'gif')
            $this->img = imagecreatefromgif($filename);
        
        if ((strtolower($ext) == 'png'))
            $this->img = imagecreatefrompng($filename);
        
        if (((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg')))
            $this->img = imagecreatefromjpeg($filename);
        
        if (!file_exists($this->CachePath))
            mkdir($this->CachePath);
        $this->redim_whc($this->img, $w, $h, $c, $s);
        $this->filter($filtertype, $arg1, $arg2, $arg3, $arg4);
        $this->cachedfile = $this->CachePath . "filtered_" . $filtertype . "_s" . $this->s . "px_c" . $this->c . "_" . $this->w . "x" . $this->h . "px_" . sha1($filename.$arg1."-".$arg2."-".$arg3."-".$arg4) . "." . $ext;
        $this->create_cached($this->img);
        //return ($cachedfile);
    }
    
    
    public function append($fn1, $fn2, $w, $h, $output = 1, $direction = 0)
    {
        $file           = basename($fn2) . "_" . basename($fn1);
        $path           = str_replace(basename($fn2), "", $fn2);
        $this->filename = $path . $file;
        // echo $filename."<br />";
        $ext            = substr(strrchr($this->filename, '.'), 1);
        
        if (!file_exists($this->CachePath))
            mkdir($this->CachePath);
        $this->cachedfile = $this->CachePath . "append_" . sha1($this->filename) . "_d" . $direction . "." . $ext;
        $this->create_cached_append($this->filename, $this->cachedfile, $fn1, $fn2, $w, $h, $output, $direction);
        
        if ($output == 0)
            return ($this->cachedfile);
    }
    
    private function abs_2_rel($file)
    {
        $res = str_replace($this->FilePath, "", $file);
        $res = str_replace(DIRECTORY_SEPARATOR, "/", $res);
        return $res;
    }
    
    public function append_array($fna, $w, $h, $c = 0, $r = 0, $output = 1)
    {
        $file = '';
        for ($i = 0; $i < count($fna); $i++)
            $file .= basename($fna[$i]["filename"]);
        $path           = str_replace(basename($fna[0]["filename"]), "", $fna[0]["filename"]);
        $this->filename = $path . $file;
        $ext            = "jpg";
        //substr(strrchr($filename, '.'), 1);
        
        if (!$this->CachePath)
            mkdir($this->CachePath);
        $this->cachedfile = $this->CachePath . "wall_" . $w . "x" . $h . "_" . $c . "x" . $r . "_" . sha1($this->filename) . "." . $ext;
        
        if ($output == 0)
            echo $this->abs_2_rel($this->cachedfile);
        // print_r($fna);
        $this->create_cached_append_array($this->filename, $this->cachedfile, $fna, $w, $h, $c, $r, $output);
        //return ($cachedfile);
    }
    
    private function create_cached_append($filename, $cachedfile, $fn1, $fn2, $w, $h, $output = 1, $direction = 0)
    {
        $ext = substr(strrchr($filename, '.'), 1);
        //echo "esiste ? ". $cachedfile . "<br>"; 
        
        if (file_exists($cachedfile)) {
            //echo "esiste". $cachedfile . "<br>"; 
            
            if (strtolower($ext) == 'gif')
                $imc = imagecreatefromgif($cachedfile);
            
            if ((strtolower($ext) == 'png')) {
                $imc = imagecreatefrompng($cachedfile);
            }
            
            
            if (((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg'))) {
                $imc = imagecreatefromjpeg($cachedfile);
            }
            
        } else {
            $ext = substr(strrchr($fn1, '.'), 1);
            
            if (strtolower($ext) == 'gif')
                $im1 = imagecreatefromgif($fn1);
            
            if ((strtolower($ext) == 'png')) {
                $im1 = imagecreatefrompng($fn1);
            }
            
            
            if (((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg'))) {
                $im1 = imagecreatefromjpeg($fn1);
            }
            
            $ext = substr(strrchr($fn2, '.'), 1);
            
            if (strtolower($ext) == 'gif')
                $im2 = imagecreatefromgif($fn2);
            
            if ((strtolower($ext) == 'png')) {
                $im2 = imagecreatefrompng($fn2);
            }
            
            
            if (((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg'))) {
                $im2 = imagecreatefromjpeg($fn2);
            }
            
            
            if ($direction == 1)
                $imc = $this->h_append($im1, $im2, $w, $h);
            else
                $imc = $this->v_append($im1, $im2, $w, $h);
            
            if ((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg')) {
                imagejpeg($imc, $cachedfile, 85);
            }
            
            
            if ((strtolower($ext) == 'gif')) {
                imagegif($imc, $cachedfile);
            }
            
            
            if ((strtolower($ext) == 'png')) {
                //echo $imc;     
                imagealphablending($imc, false);
                imagesavealpha($imc, true);
                imagepng($imc, $cachedfile);
            }
            
        }
        
        
        if ($output == 1) {
            //echo $imc;
            
            if ((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg')) {
                imagejpeg($imc, null, 85);
            }
            
            
            if ((strtolower($ext) == 'gif')) {
                imagegif($imc);
            }
            
            
            if ((strtolower($ext) == 'png')) {
                //echo $imc;     
                imagealphablending($imc, false);
                imagesavealpha($imc, true);
                imagepng($imc);
            }
            
        }
         $this->img = $imc;
        // wm($cachedfile);
        @imagedestroy($imc);
        @imagedestroy($im);
    }
    
    private function create_cached_append_array($filename, $cachedfile, $fna, $w, $h, $c, $r, $output = 1)
    {
        $ext = substr(strrchr($filename, '.'), 1);
        //    echo $w . "X".$h; 
        
        if (file_exists($cachedfile)) {
            //echo "esiste". $cachedfile . "<br>"; 
            
            if (strtolower($ext) == 'gif')
                $imc = imagecreatefromgif($cachedfile);
            
            if ((strtolower($ext) == 'png')) {
                $imc = imagecreatefrompng($cachedfile);
            }
            
            
            if (((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg'))) {
                $imc = imagecreatefromjpeg($cachedfile);
            }
            
        } else {
            $imc = $this->wall_append($fna, $w, $h, $c, $r);
            //$cachedfile = '';
            
            if ((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg')) {
                imagejpeg($imc, $cachedfile, 85);
            }
            
            
            if ((strtolower($ext) == 'gif')) {
                imagegif($imc, $cachedfile);
            }
            
            
            if ((strtolower($ext) == 'png')) {
                //echo $imc;     
                imagealphablending($imc, false);
                imagesavealpha($imc, true);
                imagepng($imc, $cachedfile);
            }
            
        }
        
        
        if ($output == 1) {
            //echo $imc;
            
            if ((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg')) {
                imagejpeg($imc, null, 85);
            }
            
            
            if ((strtolower($ext) == 'gif')) {
                imagegif($imc);
            }
            
            
            if ((strtolower($ext) == 'png')) {
                //echo $imc;     
                imagealphablending($imc, false);
                imagesavealpha($imc, true);
                imagepng($imc);
            }
            
        }
         $this->img = $imc;
        // wm($cachedfile);
        @imagedestroy($imc);
        @imagedestroy($im);
    }
    
    private function create_cached($imc = null)
    {
        $ext = substr(strrchr($this->filename, '.'), 1);
        
        if (is_null($imc)) {
            $ext = substr(strrchr($this->filename, '.'), 1);
            //echo $this->filename;
            //echo "esiste ? ". $cachedfile . "<br>"; 
            
            if (file_exists($this->cachedfile)) {
                //echo "esiste". $cachedfile . "<br>"; 
                
                if (strtolower($ext) == 'gif')
                    $imc = imagecreatefromgif($this->cachedfile);
                
                if ((strtolower($ext) == 'png')) {
                    $imc = imagecreatefrompng($this->cachedfile);
                }
                
                
                if (((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg'))) {
                    $imc = imagecreatefromjpeg($this->cachedfile);
                }
                
            } else {
                
                if (strtolower($ext) == 'gif')
                    $im = imagecreatefromgif($this->filename);
                
                if ((strtolower($ext) == 'png')) {
                    $im = imagecreatefrompng($this->filename);
                }
                
                
                if (((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg'))) {
                    $this->orient_image($this->filename);
                    $im = imagecreatefromjpeg($this->filename);
                }
                
                // in $im c'Ã¨ l'immagine originale
                
                if (!$im) {
                    exit;
                }
                
                
                if (($this->w != 0) && ($this->h != 0)) {
                    
                    if ($this->c == 0)
                        $imc = $this->redim_wh($im, $this->w, $this->h);
                    else
                        $imc = $this->redim_whc($im, $this->w, $this->h);
                } else {
                    
                    if ($this->s > 0) {
                        
                        if ($this->c == 0)
                            $imc = $this->redim_s($im, $this->s);
                        else
                            $imc = $this->redim_sc($im, $this->s);
                    } else {
                        $imc = $im;
                    }
                    
                }
                
            }
            
        }
        
        
        if ((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg')) {
            //$backgroundColor = imagecolorallocate($imc, 255, 255, 255);
            //imagefill($imc, 0, 0, $backgroundColor);
            imagejpeg($imc, $this->cachedfile, 85);
        }
        
        
        if ((strtolower($ext) == 'gif')) {
            imagegif($imc, $this->cachedfile);
        }
        
        
        if ((strtolower($ext) == 'png')) {
            //echo $imc;     
            imagepng($imc, $this->cachedfile);
        }
        
        //echo $ext . "<br /><br /><br />";
        
        if ($this->output == 1) {
            //echo $imc;
            
            if ((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg')) {
                imagejpeg($imc, null, 85);
            }
            
            
            if ((strtolower($ext) == 'gif')) {
                imagegif($imc);
            }
            
            
            if ((strtolower($ext) == 'png')) {
                //echo $imc;     
                imagealphablending($imc, false);
                imagesavealpha($imc, true);
                imagepng($imc);
            }
            
        }
        
        $this->img = $imc;
        // wm($cachedfile);
        @imagedestroy($imc);
        @imagedestroy($im);
    }
    
    private function h_append($im1, $im2, $w, $h)
    {
        $imgres = imagecreatetruecolor($w, $h);
        imagesavealpha($imgres, true);
        $trans_colour = imagecolorallocatealpha($imgres, 0, 0, 0, 127);
        imagefill($imgres, 0, 0, $trans_colour);
        $im1 = $this->redim_whc($im1, $w, ($h / 2));
        $im2 = $this->redim_whc($im2, $w, ($h / 2));
        imagecopymerge($imgres, $im1, 0, 0, 0, 0, $w, ($h / 2), 100);
        imagecopymerge($imgres, $im2, 0, ($h / 2), 0, 0, $w, ($h / 2), 100);
        //$imgres = redim_wh($im1,$width1,$height1+$height2);
        return $imgres;
        @imagedestroy($imgres);
        @imagedestroy($im1);
        @imagedestroy($im2);
    }
    
    private function wall_append($fna, $w, $h, $c = 0, $r = 0)
    {
        $q = count($fna);
        for ($i = 0; $i < $q; $i++) {
            // echo "<br />FILE: ". $_SESSION["app_path"] . $fna[$i]["filename"]."<br />";
            $ext = strtolower(substr(strrchr($fna[$i]["filename"], '.'), 1));
            
            if ((!file_exists($fna[$i]["filename"])) || ($fna[$i]["filename"] == '')) {
                unset($fna[$i]);
            } else if (($ext != 'gif') && ($ext != 'png') && ($ext != 'jpg') && ($ext != 'jpeg'))
                unset($fna[$i]);
        }
        
        $fna    = array_values($fna);
        $quanti = count($fna);
        
        if (($c == 0) && ($r == 0)) {
            $colonne = floor(sqrt($quanti));
            
            if (($quanti % $colonne) == 0)
                $righe = floor(($quanti / $colonne));
            else
                $righe = floor(($quanti / $colonne)) + 1;
        } else {
            
            if ($c != 0) {
                $colonne = $c;
                $righe   = floor($quanti / $colonne);
            }
            
            
            if ($r != 0) {
                $righe   = $r;
                $colonne = floor($quanti / $righe);
            }
            
        }
        
        $ws     = floor($w / $colonne);
        $hs     = floor($h / $righe);
        //print_r($fna);
        $imgres = imagecreatetruecolor($w, $h);
        imagesavealpha($imgres, true);
        $trans_colour = imagecolorallocatealpha($imgres, 0, 0, 0, 127);
        imagefill($imgres, 0, 0, $trans_colour);
        $cur_col = 0;
        $cur_rig = 0;
        for ($i = 0; $i < count($fna); $i++) {
            // echo $fna[$i]["filename"] . "<br />";
            $ext = substr(strrchr($fna[$i]["filename"], '.'), 1);
            // echo $ext . "<br />";
            $im  = null;
            
            if (strtolower($ext) == 'gif')
                $im = imagecreatefromgif($fna[$i]["filename"]);
            
            if ((strtolower($ext) == 'png')) {
                $im = imagecreatefrompng($fna[$i]["filename"]);
            }
            
            
            if (((strtolower($ext) == 'jpg') || (strtolower($ext) == 'jpeg'))) {
                $im = imagecreatefromjpeg($fna[$i]["filename"]);
            }
            
            // print_r($im);
            
            if ($im) {
                $im = $this->redim_whc($im, $ws, $hs);
                imagecopymerge($imgres, $im, floor($cur_col * $ws), floor($cur_rig * $hs), 0, 0, $ws, $hs, 100);
                $cur_col++;
                $resto = (($cur_col) % $colonne);
                
                if ($resto == 0) {
                    $cur_rig++;
                    $cur_col = 0;
                }
                
                //        echo "immagine: " . $fna[$i]["filename"] . " position: ". floor($cur_col*$i)."/".floor($cur_rig*$i)."/0/0/".$ws."/".$hs."<br />";
                @imagedestroy($im);
            }
            
        }
        
        return $imgres;
    }
    
    private function v_append($im1, $im2, $w, $h)
    {
        $imgres = imagecreatetruecolor($w, $h);
        imagesavealpha($imgres, true);
        $trans_colour = imagecolorallocatealpha($imgres, 0, 0, 0, 127);
        imagefill($imgres, 0, 0, $trans_colour);
        //echo $width2 . "X".$height2 . "<br />";
        $im1 = $this->redim_whc($im1, ($w / 2), $h);
        $im2 = $this->redim_whc($im2, ($w / 2), $h);
        imagecopymerge($imgres, $im1, 0, 0, 0, 0, ($w / 2), $h, 100);
        imagecopymerge($imgres, $im2, ($w / 2), 0, 0, 0, ($w / 2), $h, 100);
        //$imgres = redim_wh($im1,$width1,$height1+$height2);
        return $imgres;
        @imagedestroy($imgres);
        @imagedestroy($im1);
        @imagedestroy($im2);
    }
    
    private function redim_wh($im, $w, $h)
    {
        $width  = imagesx($im);
        $height = imagesy($im);
        
        if ($width > $height) {
            
            if (($width < $w) && ($height < $h)) {
                $new_width  = $width;
                $new_height = $height;
            } else {
                $ratio      = min($w / $width, $h / $height);
                $new_width  = round($width * $ratio);
                $new_height = round($height * $ratio);
            }
            
        } else {
            
            if (($width < $w) && ($height < $h)) {
                $new_width  = $width;
                $new_height = $height;
            } else {
                $ratio      = min($w / $width, $h / $height);
                $new_width  = round($width * $ratio);
                $new_height = round($height * $ratio);
            }
            
        }
        
        $image_p = imagecreatetruecolor($new_width, $new_height);
        imagesavealpha($image_p, true);
        $trans_colour = imagecolorallocatealpha($image_p, 0, 0, 0, 127);
        imagefill($image_p, 0, 0, $trans_colour);
        imagecopyresampled($image_p, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $this->img = $image_pc;
        return $image_p;
        @imagedestroy($image_p);
        @imagedestroy($im);
    }
    
    private function redim_whc($im, $w, $h)
    {
        $width  = imagesx($im);
        $height = imagesy($im);
        
        if ($width > $height) {
            
            if (($width < $w) && ($height < $h)) {
                //echo "sono qui:0<br>"; 
                $new_width  = $width;
                $new_height = $height;
            } else {
                //echo "sono qui:1<br>"; 
                $ratio      = max($w / $width, $h / $height);
                $new_width  = round($width * $ratio);
                $new_height = round($height * $ratio);
                //  echo $new_width . "x" . $new_height . "<br />";
            }
            
        } else {
            
            if (($width < $w) && ($height < $h)) {
                //echo "sono qui:2<br>"; 
                $new_width  = $width;
                $new_height = $height;
            } else {
                //echo "sono qui:3<br>"; 
                $ratio      = max($w / $width, $h / $height);
                $new_width  = round($width * $ratio);
                $new_height = round($height * $ratio);
                //  echo $new_width . "x" . $new_height . "<br />";
            }
            
        }
        
        $im       = $this->redim_wh($im, $new_width, $new_height);
        $width    = imagesx($im);
        $height   = imagesy($im);
        $top_c    = round(($height - $h) / 2);
        $left_c   = round(($width - $w) / 2);
        $image_pc = imagecreatetruecolor($w, $h);
        imagesavealpha($image_pc, true);
        $trans_colour = imagecolorallocatealpha($image_pc, 0, 0, 0, 127);
        imagefill($image_pc, 0, 0, $trans_colour);
        imagecopy($image_pc, $im, 0, 0, $left_c, $top_c, $w, $h);
        // $image_p = $image_pc;
        $this->img = $image_pc;
        return $image_pc;
        @imagedestroy($image_pc);
        @imagedestroy($im);
    }
    
    private function redim_s($im, $s)
    {
        $width  = imagesx($im);
        $height = imagesy($im);
        
        if ($width > $height) {
            $new_width  = $s;
            $new_height = (int) ($s * $height / $width);
        } else {
            $new_width  = (int) ($s * $width / $height);
            $new_height = $s;
        }
        
        $image_p = imagecreatetruecolor($new_width, $new_height);
        imagesavealpha($image_p, true);
        $trans_colour = imagecolorallocatealpha($image_p, 0, 0, 0, 127);
        imagefill($image_p, 0, 0, $trans_colour);
        imagecopyresampled($image_p, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $this->img = $image_pc;
        return $image_p;
        @imagedestroy($image_p);
        @imagedestroy($im);
    }
    
    private function redim_sc($im, $s)
    {
        $width  = imagesx($im);
        $height = imagesy($im);
        
        if ($width > $height) {
            $new_width  = (int) ($s * $width / $height);
            $new_height = $s;
        } else {
            $new_width  = $s;
            $new_height = (int) ($s * $height / $width);
        }
        
        $im       = $this->redim_wh($im, $new_width, $new_height);
        $width    = imagesx($im);
        $height   = imagesy($im);
        $top_c    = round(($height - $s) / 2);
        $left_c   = round(($width - $s) / 2);
        $image_pc = imagecreatetruecolor($s, $s);
        imagesavealpha($image_pc, true);
        $trans_colour = imagecolorallocatealpha($image_pc, 0, 0, 0, 127);
        imagefill($image_pc, 0, 0, $trans_colour);
        imagecopy($image_pc, $im, 0, 0, $left_c, $top_c, $s, $s);
        //$image_p = $image_pc;
        $this->img = $image_pc;
        return $image_pc;
        @imagedestroy($image_pc);
        @imagedestroy($im);
    }
    
}

?>
