<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 30.10.12
 * Time: 17:54
 * To change this template use File | Settings | File Templates.
 */
namespace x_youtube;
class AdminYoutube extends \AbstractView {
    public $user = false;
    function init() {
        parent::init();
    }
    function getView() {
        $b = $this->add('Button')->set('Refresh Data From Youtube');
        $m = $this->add('x_youtube/Model_XYoutube');
        $cr = $this->add('CRUD');
        $cr->setModel($m,
            array('title','keywords','content_description','description','link_to_video',),
            array('thumbnail_small','title','keywords','description')
        );
        if ($cr->grid) {
            $cr->grid->addFormatter('thumbnail_small','image');
            $cr->grid->addFormatter('keywords','wrap');
            $cr->grid->addFormatter('description','wrap');
        }
        if ($b->isClicked()) {
            $this->populateDB();
            $this->js(null,$cr->grid->js()->reload())->univ()->alert('Done')->execute();
        }
    }
    function setUser($name){
        $this->user = $name;
    }
    function getUrl(){
        if (!$this->user) throw $this->exception('Provide Youtube user.');
        return 'https://gdata.youtube.com/feeds/api/users/'.$this->user.'/uploads';
    }
    function populateDB() {
        $yt_array = $this->getData();

    }
    private function getData() {
        // http://www.ibm.com/developerworks/xml/library/x-youtubeapi/
        //https://gdata.youtube.com/feeds/api/users/bobeen2/uploads

        $sxml = simplexml_load_file($this->getUrl()); //var_dump($sxml);
        if (!is_object($sxml)) return false;

        // $this->author_atom
        $this->author_atom = (is_object($sxml->link[0]))? $sxml->link[0]->attributes()->href->__toString(): '';
        if($this->author_atom == '') $this->author_atom = (is_object($sxml->author))? $sxml->author->uri->__toString(): '';

        // $author_chanel_href
        $this->author_chanel_href = (is_object($sxml->link[1]))? $sxml->link[1]->attributes()->href->__toString(): '';

        // $author_name
        $this->author_name = (is_object($sxml->author))?$sxml->author->name->__toString(): '';

        foreach ($sxml->entry as $entry) {
            $this->id = $this->published = $this->updated = $this->title = $this->content_description =
            $this->embed_video = $this->link_to_video = $this->responses_atom = $this->related_videos_atom =
            $media = $this->keywords = $this->description = $this->watch = $this->thumbnail_big = $this->thumbnail_small =
            $yt = $this->length = '';

            if (!is_object($entry)) throw $this->exception('$entry is not an object');

            // video_id
            $this->id = $entry->id->__toString();

            // published updated title content_description
            $this->published = $entry->published->__toString();
            $this->updated = $entry->updated->__toString();
            $this->title = $entry->title->__toString();
            $this->content_description = $entry->content->__toString();
            $this->embed_video = '';

            $this->link_to_video = (is_object($entry->link[0]))? $entry->link[0]->attributes()->href->__toString(): '';
            $this->responses_atom = (is_object($entry->link[1]))? $entry->link[1]->attributes()->href->__toString(): '';
            $this->related_videos_atom = (is_object($entry->link[2]))? $entry->link[2]->attributes()->href->__toString(): '';
            $this->mobile_view_href = (is_object($entry->link[3]))? $entry->link[3]->attributes()->href->__toString(): '';
            $this->about_video_atom = (is_object($entry->link[4]))? $entry->link[4]->attributes()->href->__toString(): '';

            // MEDIA
            // get nodes in media: namespace for media information
            $media = $entry->children('http://search.yahoo.com/mrss/');
            if (!is_object($media)) throw $this->exception('$media is not an object');
            if (!is_object($media->group)) throw $this->exception('$media->group is not an object');

            // get keywords
            $this->keywords = $media->group->keywords->__toString();
            if ($this->title == '') $this->title = $media->group->title->__toString();
            $this->description = $media->group->description->__toString();

            // get video player URL
            $this->watch = (is_object($media->group->player))? $media->group->player->attributes()->url->__toString(): '';

            // get video thumbnails
            $this->thumbnail_big = (is_object($media->group->thumbnail[0]))? $media->group->thumbnail[0]->attributes()->url->__toString(): '';
            $this->thumbnail_small = (is_object($media->group->thumbnail[3]))? $media->group->thumbnail[3]->attributes()->url->__toString(): '';

            // get <yt:duration> node for video length
            $yt = $media->children('http://gdata.youtube.com/schemas/2007');
            $this->length = (is_object($yt->duration))? $yt->duration->attributes()->seconds->__toString(): 0;

            //$this->debugYTRespond();

            $this->dbInsert(array(
                'video_id' => $this->id,
                'title' => $this->title,
                'keywords' => $this->keywords,
                'desctiption' => $this->description,
                'content_description' => $this->content_description,
                'link_to_video' => $this->watch,
                'embed_video' => $this->embed_video,
                'thumbnail_big' => $this->thumbnail_big,
                'thumbnail_small' => $this->thumbnail_small,
                'author_name' => $this->author_name,
                'published' => $this->published,
                'updated' => $this->updated,
                'mobile_view_href' => $this->mobile_view_href,
                'author_chanel_href' => $this->author_chanel_href,
                'author_atom' => $this->author_atom,
                'responses_atom' => $this->responses_atom,
                'related_videos_atom' => $this->related_videos_atom,
                'about_video_atom' => $this->about_video_atom,
            ));
        }
    }
    private function dbInsert($values = array()) {
        /*
            video_id $id

            title $title
            keywords $keywords
            desctiption $desctiption
            content_description $content_description
            link_to_video $link_to_video
            thumbnail_big $thumbnail_big
            thumbnail_small $thumbnail_small

            author_name $author_name
            published $published
            updated $updated

            mobile_view_href $mobile_view_href
            author_chanel_href $author_chanel_href

            author_atom $author_href
            responses_atom $responses_atom_url
            related_videos_atom $related_videos_atom
            about_video_atom $about_video_atom
        */
        $m = $this->add('x_youtube/Model_XYoutube');
        $m->tryLoadBy('video_id',$values['video_id']);

        $m
           ->set('video_id',$values['video_id'])
           ->set('title',$values['title'])
           ->set('keywords',$values['keywords'])
           ->set('desctiption',$values['desctiption'])
           ->set('content_description',$values['content_description'])
           ->set('link_to_video',$values['link_to_video'])
           ->set('thumbnail_big',$values['thumbnail_big'])
           ->set('thumbnail_small',$values['thumbnail_small'])
           ->set('author_name',$values['author_name'])
           ->set('published',$values['published'])
           ->set('updated',$values['updated'])
           ->set('mobile_view_href',$values['mobile_view_href'])
           ->set('author_chanel_href',$values['author_chanel_href'])
           ->set('author_atom',$values['author_atom'])
           ->set('responses_atom',$values['responses_atom'])
           ->set('related_videos_atom',$values['related_videos_atom'])
           ->set('about_video_atom',$values['about_video_atom'])
        ->update();

//        echo '<br>-----<pre>';
//        var_dump($m->get());
//        echo '</pre>-----<br>';
    }
    private function debugYTRespond() {
        ?>
        <div class="item">
            $author_atom = <?= $this->author_atom ?><br>
            $author_chanel_href = <?= $this->author_chanel_href ?><br>
            $author_name = <?= $this->author_name ?><br>
            $id = <?= $this->id ?><br>
            $published = <?= $this->published ?><br>
            $updated = <?= $this->updated ?><br>
            $responses_atom = <?= $this->responses_atom ?><br>
            $title = <?= $this->title ?><br>
            $link_to_video = <?= $this->link_to_video ?><br>
            $related_videos_atom = <?= $this->related_videos_atom ?><br>
            $mobile_view_href = <?= $this->mobile_view_href ?><br>
            $about_video_atom = <?= $this->about_video_atom ?><br>
            $keywords = <?= $this->keywords ?><br>
            $description = <?= $this->description ?><br>
            $content_description = <?= $this->content_description ?><br>
          <span class="title">
            <a href="<?php echo $this->watch; ?>"><?php echo $this->title; ?></a>
          </span>
          <p><?php echo $this->description; ?></p>
          <p>
            <span class="thumbnail">
              <a href="<?php echo $this->watch; ?>"><img src="<?php echo $this->thumbnail_big;?>" /></a>
              <a href="<?php echo $this->watch; ?>"><img src="<?php echo $this->thumbnail_small;?>" /></a>
            </span>
            <span class="attr">By:</span> <?php echo $this->author_name; ?> <br/>
            <span class="attr">Duration:</span> <?php printf('%0.2f', $this->length/60); ?> min.<br/>
          </p>
            <hr>
        </div>
      <?php

    }
}