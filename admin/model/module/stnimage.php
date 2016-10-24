<?php

class ModelModuleStnimage extends Model {

    public function addBanner($data) {
//        echo '<pre>';
//        print_r($data);
//        print_r($this->request->post);
//        die;

        $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "stnimage_image");
        foreach ($data as $stndata) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "stnimage_image SET banner_title = '" . $stndata['stnimage_description'] . "', sort_order = '" . $stndata['sort_order'] . "', link = '" . $stndata['link'] . "', image = '" . $stndata['image'] . "'");
        }
        //$this->event->trigger('pre.admin.banner.add', $data);
        /*
          $this->db->query("INSERT INTO " . DB_PREFIX . "stnimage_image SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "'");

          $banner_id = $this->db->getLastId();

          if (isset($data['banner_image'])) {
          foreach ($data['banner_image'] as $banner_image) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "', sort_order = '" . (int)$banner_image['sort_order'] . "'");

          $banner_image_id = $this->db->getLastId();


          }
          }

          $this->event->trigger('post.admin.banner.add', $banner_id);

          return $banner_id; */
    }

    public function getstnimage() {
        $stnimages = array();
        $this->load->model('tool/image');
        $results = $this->db->query("SELECT * FROM " . DB_PREFIX . "stnimage_image ORDER BY sort_order");
        foreach ($results->rows as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $result['image'];
                $thumb = $result['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }
            $stnimages[] = array(
                'stn_image_id' => $result['stn_image_id'],
                'banner_title' => $result['banner_title'],
                'link' => $result['link'],
                'image' => $image,
                'banner_width' => $result['banner_width'],
                'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
                'sort_order' => $result['sort_order']
            );
        }
        return $stnimages;
    }

}
