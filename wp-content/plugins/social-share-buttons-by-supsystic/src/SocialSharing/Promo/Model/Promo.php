<?php

/**
 * Class SocialSharing_Promo_Model_Promo
 */
class SocialSharing_Promo_Model_Promo extends SocialSharing_Core_BaseModel
{
    private $_apiUrl = '';
    private $_pluginSlug = '';
    private $_buttonArray;

    private function _getApiUrl()
    {
        if (empty($this->_apiUrl)) {
            $this->_initApiUrl();
        }
        return $this->_apiUrl;
    }

    public function welcomePageSaveInfo($d = array())
    {
        $reqUrl = $this->_getApiUrl(). '?mod=options&action=saveWelcomePageInquirer&pl=rcs';
        $d['where_find_us'] = (int) 5;	// Hardcode for now

        $res = wp_remote_post($reqUrl, array(
            'body' => array(
                'site_url' => home_url(),
                'site_name' => get_bloginfo('name'),
                'where_find_us' => $d['where_find_us'],
                'plugin_code' => 'ssb',
            )
        ));

        $this->dontShowWelcomePage();

        return true;
    }

    protected function dontShowWelcomePage()
    {
        update_option($this->environment->getConfig()->get('plugin_name') . '_welcome_page_was_showed', 1);
    }

    protected function _initApiUrl()
    {
        $this->_apiUrl = implode('', array('','h','t','tp',':','/','/u','p','da','t','e','s.','s','u','ps','y','st','i','c.','c','o','m',''));
    }
}
