<?php

/**
 * Class SocialSharing_Shares_Module
 *
 * Sharing module.
 *
 * Handles AJAX requests and clicks for sharing buttons.
 * Builds the statistics for the projects.
 */
class SocialSharing_Shares_Module extends SocialSharing_Core_BaseModule
{
    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        parent::onInit();

        $dispatcher = $this->getEnvironment()->getDispatcher();
        $dispatcher->on('after_ui_loaded', array($this, 'loadShareScripts'));
        $dispatcher->on('after_ui_loaded', array($this, 'loadChartScripts'));
        $dispatcher->on('before_build', array($this, 'filterAddProjectShares'));
        $dispatcher->on('after_build', array($this, 'saveViews'));

        $shareRequestHandler = array($this, 'handleShareRequest');
        $viewRequestHandler = array($this, 'handleViewRequest');
        add_action('wp_ajax_social-sharing-share', $shareRequestHandler);
        add_action('wp_ajax_social-sharing-view', $viewRequestHandler);
        add_action('wp_ajax_nopriv_social-sharing-share', $shareRequestHandler);
    }

    /**
     * Handles the AJAX request for share buttons.
     * @return Rsc_Http_Response
     */
    public function handleShareRequest()
    {
        $request = $this->getRequest();
        $controller = $this->getController();
        $action = 'saveAction';
        $callableHandler = array($controller, $action);

        return call_user_func_array($callableHandler, array($request));
    }

    /**
     * Handles the AJAX request for view buttons.
     * @return Rsc_Http_Response
     */
    public function handleViewRequest()
    {
        $request = $this->getRequest();
        $controller = $this->getController();
        $action = 'saveViewsAction';
        $callableHandler = array($controller, $action);

        return call_user_func_array($callableHandler, array($request));
    }

    public function checkWhetherNeedToSaveShare($projectID)
    {
        $shareModel = $this->getModelsFactory()->get('shares');

        if (! $shareModel->getIsEnableOption($projectID) || ! $shareModel->getSharesLogOption($projectID))
        {
            return false;
        }

        return true;
    }

    public function checkWhetherNeedToSaveShows($projectID)
    {
        $shareModel = $this->getModelsFactory()->get('shares');

        if (! $shareModel->getIsEnableOption($projectID) || ! $shareModel->getViewsLogOption($projectID))
        {
            return false;
        }

        return true;
    }

    /**
     * Saves view to the database.
     * @param SocialSharing_Projects_Project $project
     * @return SocialSharing_Projects_Project
     */
    public function saveViews(SocialSharing_Projects_Project $project)
    {
        $projectId = $project->getId();
        $postId = get_the_ID();

        /** @var SocialSharing_Shares_Model_Views $views */
        $views = $this->getController()->getModelsFactory()->get('views', 'shares');

        if ($this->checkWhetherNeedToSaveShows($projectId))
        {
            try {
                $views->add($projectId, $postId);
            } catch (Exception $e) {
                $logger = new Rsc_Logger(__DIR__ . '/' . 'logs');

                $logger->error(
                    $this->translate(
                        sprintf(
                            'Failed to add current share to the statistic: %s',
                            $e->getMessage()
                        )
                    )
                );
            }
        }

        return $project;
    }

    /**
     * Filters the each project and adds the number of the shares to each network.
     * @param SocialSharing_Projects_Project $project
     * @return SocialSharing_Projects_Project
     */
    public function filterAddProjectShares(SocialSharing_Projects_Project $project)
    {
        if (count($project->getNetworks()) === 0) {
            return $project;
        }

        $networks = array();
		$projectSettings = $project->getSettings();

        /** @var SocialSharing_Shares_Model_Shares $shares */
        $shares = $this->getModelsFactory()->get('shares');
		$shareObjects = $this->getModelsFactory()->get('sharesObject','shares');

        $projectShares = $project->get('shares');

        if (!in_array($projectShares, array('plugin', 'project', 'post'), false)) {
            foreach ($project->getNetworks() as $index => $network) {
                $network->shares = 0;
                $networks[$index] = $network;
            }
        } else {
            $networksId = array();
            $sharesList = array();
			foreach ($project->getNetworks() as $index => $network) {
				$networksId[] = $network->id;
			}

            if ($projectShares == 'plugin') {
                $sharesList = $shares->getListNetworksShares($networksId);
            } else if ($projectShares == 'project') {
                $sharesList = $shares->getListProjectNetworkShares($project->getId(), $networksId);
            } else if ($projectShares == 'post') {
                $postId = get_the_ID();

				if(!empty($projectSettings['membershipParams']['type']) && !empty($projectSettings['membershipParams']['id'])) {
					$sharesList = $shareObjects->getObjectsListProjectPageShares($project->getId(), $networksId, 'mbs' . $projectSettings['membershipParams']['type'], $projectSettings['membershipParams']['id']);
				} else if ($projectSettings['where_to_show'] == 'grid_gallery') {

                $supsystic_galleries = array();
                $postContent = get_the_content();
                $shortcode = 'supsystic-gallery';
                $pattern = get_shortcode_regex();
                if ( preg_match_all( '/'. $pattern .'/s', $postContent, $matches )
                  && array_key_exists( 2, $matches )
                  && in_array( $shortcode, $matches[2] ) )  {
                       $shortcode_atts = array_keys($matches[2], $shortcode);
                     if (!empty($shortcode_atts)) {
                      foreach($shortcode_atts as $att) {
                        preg_match('/id=(\d+)/', $matches[3][$att], $supsystic_gallery);
                        $supsystic_galleries[] = $supsystic_gallery[1];
                      }
                     }
                  }
               $galleryPhotos = array();
               foreach ($supsystic_galleries as $sg) {
                  if (!empty($sg)) {
                     $galleryResources = $shareObjects->getGalleryResources($sg);
                     foreach ($galleryResources as $res) {
                        $galleryAttachments = $shareObjects->getGalleryAttachments($res['resource_id']);
                        $galleryPhotos[$res['resource_id']] = (int)$galleryAttachments[0]['attachment_id'];
                     }
                  }
               }
               $sharesLists = array();
               foreach ($galleryPhotos as $gp) {
                  $sharesLists[$gp] = $shareObjects->getObjectsListProjectPageShares($project->getId(), $networksId, 'gg' . $postId, $gp);
               }

               $project->setNetworksShares($sharesLists);

               $sharesList = $shares->getListProjectPageShares($project->getId(), $networksId, $postId);
            } else {
					$sharesList = $shares->getListProjectPageShares($project->getId(), $networksId, $postId);
				}
			}

            foreach ($project->getNetworks() as $index => $network) {
                $network->shares = isset($sharesList[$network->id]) ? $sharesList[$network->id] : 0;
                $networks[$index] = $network;
            }
        }

        $project->setNetworks($networks);

        return $project;
    }

    /**
     * Loads scripts that handles clicks for the share buttons.
     * @param SocialSharing_Ui_Module $ui
     */
    public function loadShareScripts(SocialSharing_Ui_Module $ui)
    {
        $config = $this->getEnvironment()->getConfig();
        $prefix = $config->get('hooks_prefix');
        $version = $config->get('plugin_version');

        $ui->addAsset($ui->create('script', 'jquery'));
        $ui->addAsset(
            $ui->create('script', 'social-sharing-share')
                ->addDependency('jquery')
                ->setHookName($prefix . 'before_html_build')
                ->setModuleSource($this, 'js/share.js')
                ->setVersion($version)
        );
    }

    /**
     * Loads backend scripts to build the charts for the statistic page.
     * @param SocialSharing_Ui_Module $ui
     */
    public function loadChartScripts(SocialSharing_Ui_Module $ui)
    {
        $hookName = 'admin_enqueue_scripts';

		$ui->addAsset(
			$ui->create('script', 'jquery-ui-dialog')
				->setHookName('admin_enqueue_scripts')
		);

        $ui->addAsset(
            $ui->create('script', 'sss-chartjs')
                ->setHookName($hookName)
                ->setModuleSource($this, 'js/Chart.min.js')
                ->setVersion('master')
        );

        $ui->addAsset(
            $ui->create('script', 'sss-shares-statistic')
                ->setHookName($hookName)
                ->setModuleSource($this, 'js/shares.statistic.js')
				->addDependency('jquery-ui-dialog')
        );

        $ui->addAsset(
            $ui->create('style', 'sss-shares-statistic')
                ->setHookName($hookName)
                ->setModuleSource($this, 'css/shares.statistic.css')
        );

        if (!$this->getEnvironment()->isModule('shares', 'statistic')) {
            return;
        }

        $ui->addAsset(
            $ui->create('script', 'jquery')
                ->setHookName($hookName)
        );
    }
}
