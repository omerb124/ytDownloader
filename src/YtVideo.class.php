<?php
// phpcs:disable
namespace YtDownloader;

use \Exception;

class YtVideo
{

    /**
     *  @param String $type
     */
    public $type;

    /**
     *  @param String $_video_info
     */
    private $_video_info;

    /**
     *  @param String $video_id
     */
    public $video_id;

    /**
     * @param Array<String> $data_urls
     */
    public $data_urls;

    /**
     * @param String $video_title
     */
    public $video_title;

    /**
     * Construct
     *
     * @param String $object_id unique id of content (video/playlist)
     * @param String $type type of file ("audio"/"video")
     * @param Int $quality quality level of file(possible values: 1 - lowest/ 2 - average/ 3 - best)
     */
    public function __construct($object_id, $type = "audio", $quality = 2)
    {
        // Check AUTH
        if(!$this->_checkAuth()){
            throw new \Exception("Not authorized");
        }

        // Validate quality
        if ($quality > 3 || $quality < 1) {
            $quality = 2;
        }
        $this->quality = $quality;

        // Validate file type
        if ($type !== "audio" && $type !== "video") {
            $type = "audio";
        }
        $this->type = $type;

        // Set video id
        $this->video_id = $object_id;

        try {
            $this->_getVideoInfo();

            $this->_getBestVideoData();

            //var_dump($this->data_urls);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * Check auth according to existing of cookie named 't_d34'
     * @return Boolean is authorized
     */
    private function checkAuth(){
        return isset($_COOKIE['t_d34']);
    }

    /**
     * Sanitize video title in order to make it valid for being a file name
     * @return String sanitized title
     */
    public static function sanitizeTitleForFileName($name)
    {
        // remove illegal file system characters https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
        $name = str_replace(array_merge(
            array_map('chr', range(0, 31)),
            array('<', '>', ':', '"', '/', '\\', '|', '?', '*')
        ), '', $name);
        // maximise filename length to 255 bytes http://serverfault.com/a/9548/44086
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $name = mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');
        return $name;
    }

    /**
     * Getting video info from youtube
     * @void
     */
    private function _getVideoInfo()
    {
        $data = file_get_contents(
            sprintf("http://www.youtube.com/get_video_info?video_id=%s&el=detailpage&hl=en_US", $this->video_id)
        );
        parse_str($data, $video_info);

        if (isset($video_info['reason'])) {
            // Error
            $reason = ($video_info['reason']);
            if (strpos($reason, 'removed') !== false) {
                // Video has been removed
                throw new Exception("Video has been removed");
            } else if (strpos($reason, 'Invalid parameters') !== false) {
                // Video is not exists
                throw new Exception("Video not found");
            } else if (strpos($reason, "video is unavailable") !== false) {
                // Private video/ cant access
                throw new Exception("Video is unavailable");
            }

        }

        // Set video info
        $this->_video_info = $video_info;
        
        // Set video title
        $this->video_title = $video_info['title'];
    }

    /**
     * Getting the best video data for the given file type
     * @void
     */
    private function _getBestVideoData()
    {
        $urls_array = [];
        $urls = explode(',', $this->_video_info['adaptive_fmts']);
        foreach ($urls as $url) {
            parse_str($url, $a);
            if (strpos($a['type'], $this->type . "/mp4") !== false) {
                array_push($urls_array, $a);
            }
        }

        $this->data_urls = $urls_array;

        if (empty($this->data_urls)) {
            // If empty download urls array
            throw new Exception("Download urls hasn't been found for this video.");
        }
    }

    /**
     * Get download url for given video
     * @return String download url
     */
    private function _getDownloadUrl()
    {
        if (!empty($this->data_urls)) {
            $size = sizeof($this->data_urls);
            if ($size == 1) {
                return $this->data_urls[0]['url'];
            } else {
                $reuslt = null;
                switch ($this->quality) {
                    case 1:
                        // Low quality
                        $result = $this->data_urls[sizeof($this->data_urls) - 1]['url'];
                        break;
                    case 2:
                        // Average quality
                        $result = $this->data_urls[intval(sizeof($this->data_urls) / 2)]['url'];
                        break;
                    case 3:
                        // Best quality
                        $result = $this->data_urls[0]['url'];
                        break;
                    default:
                        // Average quality
                        $result = $this->data_urls[intval(sizeof($this->data_urls) / 2)]['url'];
                }
                return $result;
            }
        } else {
            return null;
        }
    }

    /**
     * Print download page
     * @void
     */
    public function download($inline = false)
    {
        $download_url = $this->_getDownloadUrl();
        parse_str($download_url, $params);
        $clen = $params['clen'];
        if (!$clen || empty($clen)) {
            throw new \Exception("clen (content-length) param is not exists");
        }

        // File size
        $fileSize = $clen;
        
        // File name
        $fileName = self::sanitizeTitleForFileName($this->video_title);

        // Output headers
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $this->type . '/mp4');
        header("Accept-Ranges: bytes");
        header("Original-File-Size: " . $fileSize, true);
        header('Content-Length: ' . $fileSize, true);
        if($inline){
            header('Content-Disposition: inline; filename="' . $fileName . '.mp4"');
        } else {
            header('Content-Disposition: attachment; filename="' . $fileName . '.mp4"');
        }
        header("Pragma: public");
        header("Expires: -1");
        header("Content-Transfer-Encoding: binary");
        header("Cache-Control: no-cache");
        header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
        header('Connection: close');

        // ob_clean();

        // flush();
        if (ob_get_level()) {
            ob_end_clean();
        }

        @readfile($download_url);
        if (ob_get_level()) {
            ob_end_clean();
        }

        exit;

    }
}
