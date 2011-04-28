<?php

/**
 * A HeyWatch API class
 *
 * @copyright madewithlove, 30 April, 2010
 * @author Andreas Creten
 * @version 1
 **/
class HeyWatch {
    var $errors = array(
        100 => 'Unknown error',
        101 => 'Unsupported audio codec',
        102 => 'Unsupported video codec',
        103 => 'This video cannot be encoded in this format',
        104 => 'Wrong settings for audio',
        105 => 'Wrong settings for video',
        106 => 'Cannot retrieve info from this video',
        107 => 'Not a video file',
        108 => 'Video too long',
        109 => 'Unsupported video container',
        110 => 'The audio can’t be resampled',
        201 => '404 Not Found',
        202 => 'Bad address',
        300 => 'No more credit available',
        301 => 'Not enough credits'
    );
    
    var $responses = array(
        200 => 'Response OK',
        201 => 'The resource has been created successfully',
        204 => 'The resource has been updated or deleted successfully',
        400 => 'Your request ended to an error. It will be displayed in the response',
        404 => 'The resource you requested is not available',
        406 => 'The format you asked is not available'
    );
    
    var $log = '';
    
    /**
     * The HeyWatch API credentials
     **/
    var $auth = '';

    /**
     * The HeyWatch API Location
     **/
    static $api_location = 'http://heywatch.com/';
    
    /**
     * The construct for this API
     *
     * @param string $username 
     * @param string $password 
     * @return void
     * @author Andreas Creten
     */
    function __construct($username, $password) {
        $this->auth = $username.':'.$password;
    }
    
    /**
     * Get all the encoded videos
     *
     * @return SimpleXMLObject The encoded videos
     * @author Andreas Creten
     **/
    function getEncodedVideos() {
        return $this->api_call('encoded_video');
    }
    
    /**
     * Get one encoded video
     *
     * @param integer $encoded_video_id 
     * @return SimpleXMLObject The video
     * @author Andreas Creten
     */
    function getEncodedVideo($encoded_video_id) {
        return $this->api_call('encoded_video/'.$encoded_video_id);
    }
    
    /**
     * Get the thumbnail url for an encoded video
     *
     * @param integer $encoded_video_id 
     * @param string $height 
     * @param string $width 
     * @param string $seconds Offset in second (position in the video)
     * @return string The video url
     * @author Andreas Creten
     */
    function getEncodedVideoThumbnail($encoded_video_id, $height = null, $width = null, $seconds = 1) {
        $url = sprintf("http://heywatch.com/encoded_video/%s.jpg?start=%d", $encoded_video_id, $seconds);
        $url .= $height ? '&height='.$height : '';
        $url .= $width ? '&width='.$width : '';
        return $url;
    }
    
    /**
     * Get the thumbnail data for an encoded video
     *
     * @param integer $encoded_video_id 
     * @param string $height 
     * @param string $width 
     * @param string $seconds Offset in second (position in the video)
     * @return string The video data
     * @author Andreas Creten
     */
    function getEncodedVideoThumbnailData($encoded_video_id, $height = null, $width = null, $seconds = 1) {
        $url = $this->getEncodedVideoThumbnail($encoded_video_id, $height = null, $width = null, $seconds = 1);
        return file_get_contents(str_replace('http://', 'http://'.$this->auth.'@', $url));
    }
    
    /**
     * Update an encoded video
     *
     * @param integer $encoded_video_id
     * @param string $title
     * @return boolean
     * @author Andreas Creten
     */
    function updateEncodedVideo($encoded_video_id, $title) {
        return $this->api_call('encoded_video/'.$encoded_video_id, 'put', array('title' => $title));
    }
    
    /**
     * Delete an encoded video
     *
     * @param integer $encoded_video_id 
     * @return boolean
     * @author Andreas Creten
     */
    function deleteEncodedVideo($encoded_video_id) {
        return $this->api_call('encoded_video/'.$encoded_video_id, 'delete');
    }
    
    /**
     * Get all the videos
     *
     * @return SimpleXMLObject The videos
     * @author Andreas Creten
     **/
    function getVideos() {
        return $this->api_call('video');
    }
    
    /**
     * Get one video
     *
     * @param integer $video_id 
     * @return SimpleXMLObject The video
     * @author Andreas Creten
     */
    function getVideo($video_id) {
        return $this->api_call('video/'.$video_id);
    }
    
    /**
     * Update a video
     *
     * @param integer $video_id
     * @param string $title
     * @return boolean
     * @author Andreas Creten
     */
    function updateVideo($video_id, $title) {
        return $this->api_call('video/'.$video_id, 'put', array('title' => $title));
    }
    
    /**
     * Delete a video
     *
     * @param integer $video_id 
     * @return boolean
     * @author Andreas Creten
     */
    function deleteVideo($video_id) {
        return $this->api_call('video/'.$video_id, 'delete');
    }
    
    /**
     * Get the list of all the formats
     *
     * @return videos
     * @author Andreas Creten
     **/
    function getFormats() {
        return $this->api_call('format');
    }
    
    /**
     * Retrieve information about the format ID
     *
     * @param integer $format_id 
     * @return SimpleXMLObject The format
     * @author Andreas Creten
     */
    function getFormat($format_id) {
        return $this->api_call('download/'.$download_id);
    }
    
    /**
     * Create a format
     *
     * @param string $name
     * @param array $optionals
     * @return boolean
     * @author Andreas Creten
     */
    function createFormat($name, $optionals = array()) {
        $args = array_merge(array('name' => $name), $optionals);
        
        /*
        category: format category; can be formats, devices—Default is ‘formats’
        container: video format; can be avi, mp4, 3gp, psp, mpeg, mpegts, dvd, flv, svcd, vcd, vob, asf, mov, rm, mjpeg, mpeg2video, 3g2, mp3, ogg
        video_codec: video codec; can be mpeg4, xvid, flv, h263, mjpeg, mpeg1video, mpeg2video, qtrle, svq3, wmv1, wmv2, huffyuv, rv20, h264
        video_bitrate: video bitrate in kbit/s, value must be between 50 and 10000
        fps: Frame per Second; can be 7.5, 10.0, 14.985, 15.0, 23.98, 25.0, 29.97, 30.0
        width: width in pixel of the image, must be between 50 and 1920
        height: height in pixel of the image, must be between 50 and 1280. If 0, it will be calculated automatically
        audio_channel: Audio channel, can be 0 to disable audio, 1 for mono and 2 for stereo
        audio_codec: Audio codec; can be mp3, mp2, aac, pcm, amr_nb, ac3, vorbis, flac, pcm_u8—Not necessary if audio_channel is 0
        audio_bitrate: Audio bitrate in kbit/s, up to 512kbits—Not necessary if audio_channel is 0
        sample_rate: Sample rate; can be 8000, 11025, 16000, 22000, 22050, 24000, 32000, 44000, 44100, 48000—Not necessary if audio_channel is 0
        two_pass: 2-pass encoding option; can be true or false
        
        */
        return $this->api_call('format', 'post', $args);
    }
    
    /**
     * Destroy a format
     *
     * @param integer $format_id 
     * @return boolean
     * @author Andreas Creten
     */
    function deleteFormat($format_id) {
        return $this->api_call('format/'.$format_id, 'delete');
    }
    
    /**
     * Get the list of all the jobs
     *
     * @return jobs
     * @author Andreas Creten
     **/
    function getJobs() {
        return $this->api_call('job');
    }
    
    /**
     * Get one job
     *
     * @param integer $job_id 
     * @return SimpleXMLObject The job
     * @author Andreas Creten
     */
    function getJob($job_id) {
        return $this->api_call('job/'.$job_id);
    }
    
    /**
     * Create a job
     *
     * @param integer $video_id
     * @param integer $format_id 
     * @param array $optionals
     * @return boolean
     * @author Andreas Creten
     */
    function createJob($video_id, $format_id = 0, $optionals = array()) {
        $args = array_merge(array('video_id' => $video_id, 'format_id' => $format_id), $optionals);
        return $this->api_call('job', 'post', $args);
    }
    
    /**
     * Cancel a job
     *
     * @param integer $job_id 
     * @return boolean
     * @author Andreas Creten
     */
    function cancelJob($job_id) {
        return $this->api_call('job/'.$job_id, 'delete');
    }
    
    /**
     * Upload a file
     *
     * @param string $file 
     * @param string $optionals 
     * @return boolean
     * @author Andreas Creten
     */
    function upload($file, $optionals = array()) {
        $args = $optionals;
        $args['data'] = '@'.$file;
        /*
        title: the title of the video
        max_length: length in second. If you don’t want your users upload 45min of video
        custom fields: if you activate the ping after transfer, you will receive the custom fields in the request
        */
        
        return $this->api_call('upload', 'post', $args);
    }
    
    /**
     * Get the list of all the downloads
     *
     * @return videos
     * @author Andreas Creten
     **/
    function getDownloads() {
        return $this->api_call('download');
    }
    
    /**
     * Get one download
     *
     * @param integer $download_id 
     * @return SimpleXMLObject The download
     * @author Andreas Creten
     */
    function getDownload($download_id) {
        return $this->api_call('download/'.$download_id);
    }
    
    /**
     * Create a download
     *
     * @param integer $video_id
     * @param string $url 
     * @param array $optionals
     * @return boolean
     * @author Andreas Creten
     */
    function createDownload($url, $optionals = array()) {
        $args = array_merge(array('url' => $url), $optionals);
        return $this->api_call('download', 'post', $args);
    }
    
    /**
     * Delete a download
     *
     * @param integer $video_id 
     * @return boolean
     * @author Andreas Creten
     */
    function deleteDownload($download_id) {
        return $this->api_call('download/'.$download_id, 'delete');
    }
    
    /**
     * Get all info about your account
     *
     * @return SimpleXMLObject Your account information
     * @author Andreas Creten
     */
    function account() {
        return $this->api_call('account');
    }
    
    /**
     * Do a call to the heywatch API
     *
     * @param string $url The url to be called
     * @param string $method The method to be called
     * @param array $arguments The arguments for this call 
     * @return SimpleXMLObject The heywatch response
     * @author Andreas Creten
     **/
    private function api_call($url, $method = 'get', $arguments = array()) {
        $this->log('out', strtoupper($method).' '.$url.' '.http_build_query($arguments));
        // Initialize the curl session
        $ch = curl_init();

        // Set the target url
        curl_setopt($ch, CURLOPT_URL, self::$api_location.$url.'.xml');
        
        // Enable return transfer
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // Specify api credentials
        curl_setopt($ch, CURLOPT_USERPWD, $this->auth);
        
        if($method != 'post') {
            // Set a custom request
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }
        else {
            // Enable post
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        
        if(!empty($arguments)) {
            // Add the arguments to curl
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arguments);
        }
        
        // Execute the curl request
        $result = curl_exec($ch);
        
        if(!curl_errno($ch)) {
            // Get the http status code
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
        
        // Close curl
        curl_close($ch);
        
        $this->log('in', $http_status.' '.$this->responses[$http_status]);
        
        if(isset($http_status)) {
            if($http_status == 202 && $method == 'post') {
                return true;
            }
            elseif($http_status == 201 && ($method == 'put' || $method == 'delete')) {
                return true;
            }
        }
        
        if(!empty($result)) {
            // Parse the response as an SimpleXML object
            $xml = simplexml_load_string($result);
            
            if(isset($xml['status']) && $xml['status'] == 'fail') {
                return false;
            }
            
            // Return the response
            return $xml;
        }
        
        return false;
    }
    
    private function log($direction, $string) {
        if($direction == 'out') {
            $direction = 'PUT > ';
        }
        else {
            $direction = 'GET < ';
        }
        $this->log .= $direction.$string."\n";
    }
}