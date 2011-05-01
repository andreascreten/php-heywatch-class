HeyWatch php class
=============

[Hey!Watch](http://heywatch.com) provides a simple and robust encoding platform. The service allows developers to access a fast, scalable and inexpensive web service to encode videos easier. The API can be easily integrated in any web or desktop applications.

The documentation of the API can be found at http://wiki.heywatch.com/API_Documentation

Usage
-------------

**Get a list of all videos in the account**

    <?php

    include 'heywatch.php';
    
    // Connect to Hey!Watch
    $hw = new HeyWatch(HEYWATCH_USER, HEYWATCH_PASS);
    
    // Print the list of videos
    print_r($hw->getVideos());

**Encode a video and upload it to S3**

    include 'heywatch.php';
    
    // Get the Hey!Watch video id (for example in combination with their transfer callback hook)
    $video_id = $_POST['video_id'];
    
    // Connect to Hey!Watch
    $hw = new HeyWatch(HEYWATCH_USER, HEYWATCH_PASS);

    // Create a new job to encode and upload the video to an S3 bucket
    // MP4 H.264 = 175 (.mp4)
    $result = $hw->createJob($video_id, 175, array(
        's3_directive' => 's3://'.AWS_ACCESS_KEY.':'.AWS_SECRET_KEY.'@'.AWS_BUCKET.'/'.$video_id.'.mp4',
        'keep_video_size' => 'true'
    ));