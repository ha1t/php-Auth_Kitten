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
        $photos = $this->getPhotosJpg();

        $array = '[]';
        $data  = "<input type=\"hidden\" name=\"kitten[code]\" value=\"" . $this->getHiddenPhrase($photos) . "\" />\n";

        $i     = 1;
        $data .= "<table>\n<tr>\n";

        foreach ($photos as $file => $type) {
            $data .= "<td>";
            $data .= "<label for=\"{$file}\">\n";
            $data .= "<img src=\"{$viewer_path}{$file}\" />";
            $data .= "<br />";
            $data .= "<input id=\"{$file}\" type=\"checkbox\" name=\"kitten{$array}\" value=\"{$file}\" />\n";
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
        $kittens = $this->getFileList($this->image_path . "kitten");
        $others = $this->getFileList($this->image_path . "other");

        $kitten_keys = array_rand($kittens, 3);
        $kitten = array();
        foreach ($kitten_keys as $key) {
            $kitten[$kittens[$key]] = "kitten";
        }

        $other_keys = array_rand($others, 6);
        $other = array();
        foreach ($other_keys as $key) {
            $other[$others[$key]] = "other";
        }

        $photos = array_merge($kitten, $other);

        //shuffle array
        foreach ($photos as $key => $type) {
            $buf[] = array($key => $type);
        }

        shuffle($buf);

        $data = array();
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
        return array_map('basename', glob($path . '/*.jpg'));
    }

    /**
     * エラーの場合、エラー画像を返す
     * @param string $filename
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
     * @param array $photos
     * @return string
     */
    private function getHiddenPhrase(array $photos)
    {
        $kittens = array_keys($photos, 'kitten');
        sort($kittens);
        return md5(implode('', $kittens));
    }

    /**
     * @param array $kitten
     * @param string $phrase
     * @return bool
     */
    public function verify($kitten, $phrase = '')
    {
        if (!is_array($kitten)) {
            return false;
        }

        if (isset($kitten['code']) && $phrase == '') {
            $phrase = $kitten['code'];
            unset($kitten['code']);
        }

        sort($kitten);
        if(md5(implode('', $kitten)) == $phrase) {
            return true;
        }

        return false;
    }
}
