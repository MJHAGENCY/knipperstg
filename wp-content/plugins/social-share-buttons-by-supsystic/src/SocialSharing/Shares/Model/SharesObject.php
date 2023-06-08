<?php

class SocialSharing_Shares_Model_SharesObject extends SocialSharing_Core_BaseModel {

	public function addObject($shareId, $code, $itemId) {

		$insertQuery = $this->db->prepare(
			"INSERT INTO " . $this->getPrefix() . 'shares_object'
			. "(`share_id`, `code`, `item_id`)
			VALUES ('%d', '%s', '%d');",
			array($shareId, $code, $itemId)
		);
		$this->db->query($insertQuery);
		return true;
	}

	public function getObjectsListProjectPageShares($projectId, array $networksId, $itemCode, $itemId) {

		$query = $this->getQueryBuilder()
			->select(array('network_id', 'COUNT(*) AS total_shares'))
			->from($this->getPrefix() . 'shares')
			->join($this->getPrefix() . 'shares_object')
			->on('share_id', '=', 'id')
			->where('project_id', '=', (int)$projectId)
			->andWhere('network_id', 'in', implode(',', $networksId))
			->andWhere('code', '=', $itemCode)
			->andWhere('item_id', '=', (int) $itemId)
			->groupBy('network_id');

		$dbresult = $this->db->get_results($query->build());
		$list = array();

		if(count($networksId)) {
			foreach($networksId as $oneNwkId) {
				$list[$oneNwkId] = 0;
			}
		}

		if(is_array($dbresult) && count($dbresult)) {
			foreach ($dbresult as $item) {
				$list[$item->network_id] = $item->total_shares;
			}
		}

		return $list;
	}
   public function getGalleryResources($galleryId) {
		$query = $this->getQueryBuilder()
			->select(array('resource_id'))
			->from($this->db->prefix . 'gg_galleries_resources')
			->where('gallery_id', '=', (int)$galleryId);
		$dbresult = $this->db->get_results($query->build(), ARRAY_A);
		return $dbresult;
	}
   public function getGalleryAttachments($resourceId) {
		$query = $this->getQueryBuilder()
			->select(array('attachment_id'))
			->from($this->db->prefix . 'gg_photos')
			->where('id', '=', (int)$resourceId);
		$dbresult = $this->db->get_results($query->build(), ARRAY_A);
		return $dbresult;
	}
}
