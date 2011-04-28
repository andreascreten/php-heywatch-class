HeyWatch php class
=============

Examples
-------------

    $hw = new HeyWatch('username', 'password');
    print_r($hw->getVideos());
    print_r($hw->getVideo(123456));
    print_r($hw->updateVideo(123456, 'test') ? 'true' : 'false');
    print_r($hw->createDownload('http://heywatch.com/encoded_video/123456.bin', array('title'=> 'test')));
    print_r($hw->getDownload(123456));
    print_r($hw->createJob(123456, 31);