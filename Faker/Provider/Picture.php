<?php

namespace Kaliop\eZLoremIpsumBundle\Faker\Provider;

/**
 * Almost the same as Image from Faker, but uses picsum.photos
 * @todo add support for https://loremflickr.com/ as an alternative, or see the list at
 *       https://www.johanbostrom.se/blog/the-best-image-placeholder-services-on-the-web
 */
class Picture extends Base
{
    public function pictureUrl($width = 640, $height = 480, $randomize = true, $word = null, $gray = false)
    {
        $baseUrl = "https://picsum.photos/";
        $url = "{$width}/{$height}/";

        if ($gray) {
            $url = "g/" . $url;
        }

        if ($randomize) {
            $url .= '?random';
        }

        return $baseUrl . $url;
    }

    /**
     * Download a remote random image to disk and return its location
     *
     * Requires curl, or allow_url_fopen to be on in php.ini.
     *
     * @example '/path/to/dir/13b73edae8443990be1aa8f1a483bc27.jpg'
     */
    public function picture($dir = null, $width = 640, $height = 480, $fullPath = true, $randomize = true, $word = null)
    {
        $dir = is_null($dir) ? sys_get_temp_dir() : $dir; // GNU/Linux / OS X / Windows compatible
        // Validate directory path
        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \InvalidArgumentException(sprintf('Cannot write to directory "%s"', $dir));
        }

        // Generate a random filename. Use the server address so that a file
        // generated at the same time on a different server won't have a collision.
        $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $filename = $name .'.jpg';
        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        $url = $this->pictureUrl($width, $height, $randomize, $word);

        // save file
        if (function_exists('curl_exec')) {
            // use cURL
            $fp = fopen($filepath, 'w');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $success = curl_exec($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;

            if ($success) {
                fclose($fp);
            } else {
                unlink($filepath);
            }

            curl_close($ch);
        } elseif (ini_get('allow_url_fopen')) {
            // use remote fopen() via copy()
            $success = copy($url, $filepath);
        } else {
            return new \RuntimeException('The image formatter downloads an image from a remote HTTP server. Therefore, it requires that PHP can request remote hosts, either via cURL or fopen()');
        }

        if (!$success) {
            // could not contact the distant URL or HTTP error - fail silently.
            return null;
        }

        return $fullPath ? $filepath : $filename;
    }
}
