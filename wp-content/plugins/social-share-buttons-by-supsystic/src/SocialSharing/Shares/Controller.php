<?php


class SocialSharing_Shares_Controller extends SocialSharing_Core_BaseController
{
    /**
     * Saves share to the database.
     * @param Rsc_Http_Request $request
     * @return Rsc_Http_Response
     */
    public function saveAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request) && !$this->_checkNonceFrontend($request)) die();
        $projectId = $request->post->get('project_id');
        $networkId = $request->post->get('network_id');
        $postId = $request->post->get('post_id');

		$additionalObjectCode = $request->post->get('additional_object_code', null);
		$additionalObjectItemId = $request->post->get('additional_object_item_id', null);
		$additionalObjectItemType = $request->post->get('additional_object_item_type', null);

        /** @var SocialSharing_Shares_Model_Shares $shares */
        $shares = $this->modelsFactory->get('shares');

        if ($this->getEnvironment()->getModule('shares')->checkWhetherNeedToSaveShare($projectId))
        {
            try {
				$newSharesId = $shares->add($projectId, $networkId, $postId);
				if($additionalObjectCode == 'mbs' && $newSharesId) {
					$shareObject = $this->modelsFactory->get('sharesObject','shares');
					$shareObject->addObject($newSharesId, $additionalObjectCode . $additionalObjectItemType, $additionalObjectItemId);
				} else if ($additionalObjectCode == 'gg' && $newSharesId){
               $shareObject = $this->modelsFactory->get('sharesObject','shares');
					$shareObject->addObject($newSharesId, $additionalObjectCode . $additionalObjectItemType, $additionalObjectItemId);
            }
            } catch (Exception $e) {
                return $this->ajaxError(
                    $this->translate(
                        sprintf(
                            'Failed to add current share to the statistic: %s',
                            $e->getMessage()
                        )
                    )
                );
            }
        }

        return $this->ajaxSuccess();
    }

    public function setOptionEnableStatAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        $isEnable = (bool) $request->post->get('isEnable');

        $shares = $this->modelsFactory->get('shares');

        $shares->setIsEnableOption($isEnable);

        return $this->ajaxSuccess();
    }

    public function setOptionViewsLogAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        $shares = $this->modelsFactory->get('shares');

        $shares->setViewsLogOption($request->post->get('isEnable'));

        return $this->ajaxSuccess();
    }

    public function setOptionSharesLogAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        $shares = $this->modelsFactory->get('shares');

        $shares->setSharesLogOption($request->post->get('isEnable'));

        return $this->ajaxSuccess();
    }

    public function clearDataAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        $projectId = $request->post->get('project_id');
        $shares = $this->modelsFactory->get('shares');
        $views = $this->modelsFactory->get('views', 'shares');

        $shares->removeDataByProjectID($projectId);
        $views->removeDataByProjectID($projectId);

        return $this->ajaxSuccess(array('clearStatus' => 1));
    }

    public function statisticAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        $project = $this->modelsFactory->get('projects')->get(
            $request->query->get('project_id')
        );

        return $this->response('@shares/statistic.twig', array(
            'project' => $project
        ));
    }

    public function getTotalSharesAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        try {
            /** @var SocialSharing_Shares_Model_Shares $shares */
            $shares = $this->modelsFactory->get('shares');
            $stats = $shares->getProjectStats($request->post->get('project_id'));
        } catch (Exception $e) {
            return $this->ajaxError($e->getMessage());
        }

        return $this->ajaxSuccess(array('stats' => $stats));
    }

    public function getTotalViewsAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        try {
            /** @var SocialSharing_Shares_Model_Shares $shares */
            $views = $this->modelsFactory->get('views', 'shares');
            $stats = $views->getProjectTotalViews($request->post->get('project_id'));
        } catch (Exception $e) {
            return $this->ajaxError($e->getMessage());
        }

        return $this->ajaxSuccess(array('stats' => $stats));
    }

    public function getTotalSharesByDaysAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        try {
            $days = $request->post->get('days', 30);
            $to = new DateTime();
            $from = new DateTime();

            if ($days < 1) {
                $days = 1;
            }

            $modifier = '-'.$days . ' days';
            $from->modify($modifier);

            /** @var SocialSharing_Shares_Model_Shares $shares */
            $shares = $this->modelsFactory->get('shares');
            $stats = $shares->getProjectStatsForPeriod(
                $request->post->get('project_id'),
                $from,
                $to
            );
        } catch (Exception $e) {
            return $this->ajaxError($e->getMessage());
        }

        return $this->ajaxSuccess(array('stats' => $stats));
    }

    public function getPopularPagesByDaysAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        try {
            $days = $request->post->get('days', 30);
            $to = new DateTime();
            $from = new DateTime();

            if ($days < 1) {
                $days = 1;
            }

            $modifier = '-'.$days . ' days';
            $from->modify($modifier);

            /** @var SocialSharing_Shares_Model_Shares $shares */
            $shares = $this->modelsFactory->get('shares');
            $stats = $shares->getPopularPostsForPeriod(
                $request->post->get('project_id'),
                $from,
                $to
            );
        } catch (Exception $e) {
            return $this->ajaxError($e->getMessage());
        }

        if (is_array($stats) && count($stats) > 0) {
            foreach ($stats as $index => $row) {
                $post = get_post($row->post_id);
                $stats[$index]->post = $post;
            }
        }

        return $this->ajaxSuccess(array('stats' => $stats));
    }

    public function getPopularPagesByDaysViewsAction(Rsc_Http_Request $request)
    {
      if (!$this->_checkNonce($request)) die();
        try {
            $days = $request->post->get('days', 30);
            $to = new DateTime();
            $from = new DateTime();

            if ($days < 1) {
                $days = 1;
            }

            $modifier = '-'.$days . ' days';
            $from->modify($modifier);

            /** @var SocialSharing_Shares_Model_Shares $shares */
            $views = $this->modelsFactory->get('views', 'shares');
            $stats = $views->getPopularPostsForPeriod(
                $request->post->get('project_id'),
                $from,
                $to
            );
        } catch (Exception $e) {
            return $this->ajaxError($e->getMessage());
        }

        if (is_array($stats) && count($stats) > 0) {
            foreach ($stats as $index => $row) {
                $post = get_post($row->post_id);
                $stats[$index]->post = $post;
            }
        }

        return $this->ajaxSuccess(array('stats' => $stats));
    }

	public function checkReviewNoticeAction(Rsc_Http_Request $request) {
    if (!$this->_checkNonce($request)) die();
		$showNotice = get_option('showSharingRevNotice');
		$show = false;

		if(!$showNotice) {
			update_option('showSharingRevNotice', array(
				'date' => new DateTime(),
				'is_shown' => false
			));
		} else {
			$currentDate = new DateTime();

			if(($currentDate->diff($showNotice['date'])->d > 7) && $showNotice['is_shown'] != 1) {
				$show = true;
			}
		}

		return $this->response(
			Rsc_Http_Response::AJAX,
			array('show' => $show)
		);
	}

	public function checkNoticeButtonAction(Rsc_Http_Request $request) {
    if (!$this->_checkNonce($request)) die();
		$code  = $request->post->get('buttonCode');
		$showNotice = get_option('showSharingRevNotice');

		if($code == 'is_shown') {
			$showNotice['is_shown'] = true;
		} else {
			$showNotice['date'] = new DateTime();
		}

		update_option('showSharingRevNotice', $showNotice);

		return $this->response(Rsc_Http_Response::AJAX);
	}

}
