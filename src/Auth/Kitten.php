<?php
/**
 * Auth_Kitten
 *
 * @category Auth
 */

/**
 * Auth_Kitten
 *
 * @category Auth
 */
class Auth_Kitten
{
    private $image_path = "";
    private $photos = array();
    private $type = "checkbox";

    public function __construct($options = array())
    {
        $this->image_path = dirname(__FILE__) . "/Kitten/images/";

        foreach ($options as $key => $value) {
            if (in_array($key, array('image_path'))) {
                $this->$key = $value;
            }
        }
    }

    /**
     * buildHtml
     *
     * @todo error handling
     * @param string $viewer_path
     * @return string $data
     */
    public function buildHtml($viewer_path)
    {
        if (count($this->photos) == 0) {
            $this->photos = $this->getPhotosJpg();
        }

        if ($this->type == 'checkbox') {
            $array = '[]';
            $data  = "<input type=\"hidden\" name=\"kitten[code]\" value=\"" . $this->getHiddenPhrase() . "\" />\n";
        } else {
            $array = '';
            $data  = "<input type=\"hidden\" name=\"kitten_code\" value=\"" . $this->getHiddenPhrase() . "\" />\n";
        }

        $i     = 1;
        $data .= "<table>\n<tr>\n";

        foreach ($this->photos as $file => $type) {
            $data .= "<td>";
            $data .= "<label for=\"{$file}\">\n";
            $data .= "<img src=\"{$viewer_path}{$file}\" />";
            $data .= "<br />";
            $data .= "<input id=\"{$file}\" type=\"{$this->type}\" name=\"kitten{$array}\" value=\"{$file}\" />\n";
            $data .= "click!</label>\n";
            $data .= "</td>\n";
            if ($i % 3 == 0) {
                $data .= "</tr>\n<tr>\n";
            }
            $i++;
        }

        $data .= "</tr>\n</table>";

        return $data;
        
    }

    private function getPhotosJpg()
    {
        if ($this->type == "radio") {
            $kittens = $this->getFileList($this->image_path . "kitten");
            $kitten_key = array_rand($kittens);
            $kitten = array($kittens[$kitten_key] => 'kitten');

            $others = $this->getFileList($this->image_path . "other");
            $other_keys = array_rand($others, 8);
            $other = array();
            foreach ($other_keys as $key) {
                $other[$others[$key]] = "other";
            }
        } else {
            $kittens = $this->getFileList($this->image_path . "kitten");
            $kitten_keys = array_rand($kittens, 3);
            $kitten = array();
            foreach ($kitten_keys as $key) {
                $kitten[$kittens[$key]] = "kitten";
            }

            $others = $this->getFileList($this->image_path . "other");
            $other_keys = array_rand($others, 6);
            $other = array();
            foreach ($other_keys as $key) {
                $other[$others[$key]] = "other";
            }
         }

        $photos = array_merge($kitten, $other);

        //shuffle array
        foreach ($photos as $key => $type) {
            $buf[] = array($key => $type);
        }

        shuffle($buf);

        foreach ($buf as $photo) {
            $data[key($photo)] = $photo[key($photo)];
        }
        
        return $data;
    }

    /**
     * getFileList
     */
    private function getFileList($path)
    {
        $dh  = opendir($path);
        while (false !== ($filename = readdir($dh))) {
            $files[] = $filename;
        }

        foreach ($files as $file) {
            if (strpos($file, ".jpg")) {
                $photos[] = $file;
            }
        }
        return $photos;
    }

    public static function drawImage($file)
    {
        $is_kitten = file_exists($this->image_path . "kitten/{$file}");
        $is_other = file_exists($this->image_path . "other/{$file}");

        header("Content-Type: image/jpg");

        if ($is_kitten || $is_other) {
            if ($is_kitten) {
                $file = "kitten/" . $file;
            } else {
                $file = "other/" . $file;
            }
            readfile($this->image_path . $file);
        } else {
            readfile($this->image_path . "error.jpg");
        }
    }

    private function getHiddenPhrase()
    {
        if ($this->type == "radio") {
            $photos = array_flip($this->photos);
            return md5($photos['kitten']);
        } else {
            $kittens = array_keys($this->photos, 'kitten');
            sort($kittens);
            return md5(implode('', $kittens));
        }
    }

    public function verify($kitten, $phrase = '')
    {
        if (isset($kitten['code']) && $phrase == '') {
            $phrase = $kitten['code'];
            unset($kitten['code']);
        }

        if (is_array($kitten))  {
            sort($kitten);
            if(md5(implode('', $kitten)) == $phrase) {
                return true;
            }
        } else if ($this->type == 'radio') {
            if(md5($kitten) == $phrase) {
                return true;
            }
        }

        return false;
    }
}
