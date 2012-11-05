<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 31.10.12
 * Time: 20:30
 * To change this template use File | Settings | File Templates.
 */
namespace x_youtube;
class CRUD_List extends \CRUD {
    public $grid_class='x_youtube/Grid';
    function init() {
        parent::init();
        $m = $this->add('x_youtube/Model_XYoutube');
        $this->setModel($m,
                array('title','keywords','content_description','description','link_to_video',),
                array('thumbnail_small','title','keywords','description')
        );
        if ($this->grid) {
            $this->grid->addPaginator(10);
            $this->grid->addFormatter('thumbnail_small','image');
            $this->grid->addFormatter('keywords','wrap');
            $this->grid->addFormatter('description','wrap');
            $this->grid->addFormatter('title','linkToVideo');
        }
    }
}
class Grid extends \Grid_Advanced {

    function format_linkToVideo($field){
        $this->current_row[$field] = $this->current_row[$field];
    }
}