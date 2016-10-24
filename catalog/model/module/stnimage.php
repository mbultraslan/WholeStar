<?php
class ModelModuleStnimage extends Model {	
	public function getstnimage(){
			$stnimages = array();
			$this->load->model('tool/image');
			$results = $this->db->query("SELECT * FROM " . DB_PREFIX . "stnimage_image ORDER BY banner_width");
			foreach($results->rows as $result){
				if (is_file(DIR_IMAGE . $result['image'])) {
					$image = $result['image'];
					$thumb = $result['image'];
				} else {
					$image = '';
					$thumb = 'no_image.png';
				}
				$stnimages[] = array(
					'banner_title'			   => $result['banner_title'],
					'link'                     => $result['link'],
					'image'                    => $image,
					'banner_width'             => $result['banner_width'],
					'thumb'                    => $this->model_tool_image->resize($thumb, 100, 100),
					'sort_order'               => $result['sort_order']				
				);
			}
			return $stnimages;
	}
}