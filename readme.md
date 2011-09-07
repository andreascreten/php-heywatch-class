HeyWatch php class
=============

[Hey!Watch](http://heywatch.com) provides a simple and robust encoding platform. The service allows developers to access a fast, scalable and inexpensive web service to encode videos easier. The API can be easily integrated in any web or desktop applications.

The documentation of the API can be found at http://wiki.heywatch.com/API_Documentation

Usage
-------------

**Get a list of all videos in the account**

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
    
License
---------

*Copyright 2011 [madewithlove](http://madewithlove.be/). All rights reserved.*

Redistribution and use in source and binary forms, with or without modification, are
permitted provided that the following conditions are met:

   1. Redistributions of source code must retain the above copyright notice, this list of
      conditions and the following disclaimer.
   2. Redistributions in binary form must reproduce the above copyright notice, this list
      of conditions and the following disclaimer in the documentation and/or other materials
      provided with the distribution.

THIS SOFTWARE IS PROVIDED BY MADEWITHLOVE ''AS IS'' AND ANY EXPRESS OR IMPLIED
WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL MADEWITHLOVE OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

The views and conclusions contained in the software and documentation are those of the
authors and should not be interpreted as representing official policies, either expressed
or implied, of madewithlove.