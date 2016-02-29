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

    /**
     * @param array $options
     */
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

    /**
     * @return array
     */
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
     * @param string $path
     * @return array
     */
    private function getFileList($path)
    {
        $files = array();
        $dh  = opendir($path);
        while (false !== ($filename = readdir($dh))) {
            $files[] = $filename;
        }

        $photos = array();
        foreach ($files as $file) {
            if (strpos($file, ".jpg")) {
                $photos[] = $file;
            }
        }
        return $photos;
    }

    /**
     * エラーの場合、エラー画像を返す
     * @TODO $image_pathどうするか
     * @param $filename
     */
    public static function drawImage($filename)
    {
        $image_path = dirname(__FILE__) . "/Kitten/images/";
        $is_kitten = file_exists($image_path . "kitten/{$filename}");
        $is_other = file_exists($image_path . "other/{$filename}");

        header("Content-Type: image/jpg");

        if ($is_kitten || $is_other) {
            if ($is_kitten) {
                $filename = "kitten/" . $filename;
            } else {
                $filename = "other/" . $filename;
            }
            readfile($image_path . $filename);
        } else {
            readfile($image_path . "error.jpg");
        }
    }

    /**
     * @return string
     */
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

    /**
     * @param $kitten
     * @param string $phrase
     * @return bool
     */
    public function verify($kitten, $phrase = '')
    {
        if (is_array($kitten) && isset($kitten['code']) && $phrase == '') {
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
