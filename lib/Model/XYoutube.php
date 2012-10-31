<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 31.10.12
 * Time: 18:30
 * To change this template use File | Settings | File Templates.
 */
namespace x_youtube;
class Model_XYoutube extends \Model_Table {
    public $table = 'x_youtube';
    function init(){
        parent::init(); //$this->debug();
        $this->addField('video_id')->required($this->api->required_message);
        $this->addField('title')->required($this->api->required_message);
        $this->addField('keywords');
        $this->addField('description')->type('text');
        $this->addField('content_description')->type('text');
        $this->addField('link_to_video')->required($this->api->required_message);
        $this->addField('embed_video');
        $this->addField('thumbnail_big')->required($this->api->required_message);
        $this->addField('thumbnail_small')->required($this->api->required_message);
        $this->addField('author_name');
        $this->addField('published');
        $this->addField('updated');
        $this->addField('mobile_view_href');
        $this->addField('author_chanel_href');
        $this->addField('author_atom');
        $this->addField('responses_atom');
        $this->addField('related_videos_atom');
        $this->addField('about_video_atom');
    }
}