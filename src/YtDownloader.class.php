<?php
/**
 * YTDownloader class
 *
 * Songs & Playlists downloading from youtube
 *
 * @category Libraries
 * @package  YTDownloader
 * @author   Down4Me <down4me.net@example.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://down4me.net
 * @since    1.0.0
 */
// phpcs:disable
namespace YtDownloader;

//use YtDownloader\YtPlaylist;
use YtDownloader\YtVideo;

class YtDownloader
{

    /**
     * Downloading video/playlist from youtube
     * @param String $object_id id of content (playlist id/ video id on youtube)
     *
     * @void
     */
    public static function download($object_id)
    {
        // Find content type
        $content_type = self::checkContentType($object_id);

        if (!$content_type) {
            return;
        } else if ($content_type === "video") {
            // Video
            $output = new YtVideo($object_id);
        } else if ($content_type === "playlist") {
            // Playlist
            $output = new YtPlaylist($object_id);
        } else {
            return;
        }
    }

    /**
     * Checking content type (does it a video or playlist)
     * @param String $object_id id of content to check
     *
     * @return (String|Bool)
     * False on error/content is not found
     * String ("video"/"playlist") on success
     */
    public static function checkContentType($object_id)
    {
        if (strlen($object_id) === 11) {
            return "video";
        } else if (strlen($object_id) === 34) {
            return "playlist";
        } else {
            return false;
        }
    }
}
