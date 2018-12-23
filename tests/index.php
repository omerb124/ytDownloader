<?php
// phpcs:disable

namespace YtDownloader;

require __DIR__ . '\..\vendor\autoload.php';

use YtDownloader\YtVideo;

/*********** YtVideo  *************/
// Video is not found
function videoNotExists()
{
    try {
        $m = new YtVideo("aJOTlE1K910k");
        echo (__FUNCTION__ . " test has been failed");
    } catch (\Exception $e) {
        if ($e->getMessage() === "Video not found") {
            echo (__FUNCTION__ . " test has been completed<br>");
        } else {
            echo (__FUNCTION__ . " test has not been completed successfully.<br>");
        }
    }
}

// Given id is a playlist
function videoIdIsNotAVideoId()
{
    try {
        $m = new YtVideo("PLx0sYbCqOb8TBPRdmBHs5Iftvv9TPboYG");
        echo (__FUNCTION__ . " test has been failed");
    } catch (\Exception $e) {
        if ($e->getMessage() === "Video not found") {
            echo (__FUNCTION__ . " test has been completed<br>");
        } else {
            echo (__FUNCTION__ . " test has not been completed successfully.<br>");
        }
    }
}

// Video has been removed
function videoHasBeenRemoved()
{
    try {
        $m = new YtVideo("aoj40mMLs4g");
        echo (__FUNCTION__ . " test has been failed");
    } catch (\Exception $e) {
        if ($e->getMessage() === "Video has been removed") {
            echo (__FUNCTION__ . " test has been completed<br>");
        } else {
            echo (__FUNCTION__ . " test has not been completed successfully.<br>");
        }
    }
}

// Video is private
function videoIsPrivate()
{
    try {
        $m = new YtVideo("sMH-ycteupQ");
        echo (__FUNCTION__ . " test has been failed");
    } catch (\Exception $e) {
        if ($e->getMessage() === "Video is unavailable") {
            echo (__FUNCTION__ . " test has been completed<br>");
        } else {
            echo (__FUNCTION__ . " test has not been completed successfully.<br>");
        }
    }
}

// Get video file of valid video
function getValidVideoWithVideoFile()
{
    try {
        $m = new YtVideo("1tR9w-mc6rA", "video");
        $a = $m->getDownloadUrl();
        if ($a !== null) {
            echo (__FUNCTION__ . " test has been completed<br>");
        } else {
            echo (__FUNCTION__ . " test has been failed: download url equals to null");
        }
    } catch (\Exception $e) {
        echo (__FUNCTION__ . " test has been failed:" . $e->getMessage());
    }
}

// Get audio file of valid video
function getValidVideoWithAudioFile()
{
    try {
        $m = new YtVideo("1tR9w-mc6rA", "audio");
        $a = $m->getDownloadUrl();
        if ($a !== null) {
            echo (__FUNCTION__ . " test has been completed<br>");
        } else {
            echo (__FUNCTION__ . " test has been failed: download url equals to null");
        }
    } catch (\Exception $e) {
        echo (__FUNCTION__ . " test has been failed:" . $e->getMessage());
    }
}

// Prints download page of video
function downloadValidVideo(){
    try {
        $m = new YtVideo("1tR9w-mc6rA", "video");
        $m->download(false);
    } catch (\Exception $e) {
        echo (__FUNCTION__ . " test has been failed:" . $e->getMessage());
    }
}

downloadValidVideo();
// videoNotExists();
// videoIdIsNotAVideoId();
// videoHasBeenRemoved();
// videoIsPrivate();
// getValidVideoWithVideoFile();
// getValidVideoWithAudioFile();
