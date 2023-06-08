<?php

/**
 * Class SocialSharing_Featuredplugins_Controller
 * Featuredplugins page controller
 *
 * @package SocialSharing\Featuredplugins
 */
class SocialSharing_Featuredplugins_Controller extends SocialSharing_Core_BaseController
{
    /**
     * @param Rsc_Http_Request $request
     */
    public function indexAction(Rsc_Http_Request $request)
    {
		$environment = $this->getEnvironment();


    $pluginsUrl = 'https://supsystic.com/plugins/';
		$uploadsUrl = SSS_PLUGIN_URL.'src/SocialSharing/Featuredplugins/assets/img/';
		$downloadsUrl = 'https://downloads.wordpress.org/plugin/';
		$promoCampaign = 'socialbuttons';
		$pluginsList = array(
			array('label' => $environment->translate('Popup Plugin'), 'url' => $pluginsUrl. 'popup-plugin/', 'img' => $uploadsUrl. 'Popup_256.png', 'desc' => $environment->translate('The Best WordPress PopUp option plugin to help you gain more subscribers, social followers or advertisement. Responsive pop-ups with friendly options.'), 'download' => $downloadsUrl. 'popup-by-supsystic.zip'),
			array('label' => $environment->translate('Slider Plugin'), 'url' => $pluginsUrl. 'slider/', 'img' => $uploadsUrl. 'Slider_256.png', 'desc' => $environment->translate('Creating slideshows with Slider plugin is fast and easy. Simply select images from your WordPress Media Library, Flickr, Instagram or Facebook, set slide captions, links and SEO fields all from one page.'), 'download' => $downloadsUrl. 'slider-by-supsystic.zip'),
			array('label' => $environment->translate('Photo Gallery Plugin'), 'url' => $pluginsUrl. 'photo-gallery/', 'img' => $uploadsUrl. 'Gallery_256.png', 'desc' => $environment->translate('Photo Gallery Plugin with a great number of layouts will help you to create quality respectable portfolios and image galleries.'), 'download' => $downloadsUrl. 'gallery-by-supsystic.zip'),
			array('label' => $environment->translate('Data Tables Generator'), 'url' => $pluginsUrl. 'data-tables-generator-plugin/', 'img' => $uploadsUrl. 'Data_Tables_256.png', 'desc' => $environment->translate('Create and manage beautiful data tables with custom design. No HTML knowledge is required.'), 'download' => $downloadsUrl. 'data-tables-generator-by-supsystic.zip'),
			array('label' => $environment->translate('Social Share Buttons'), 'url' => $pluginsUrl. 'social-share-plugin/', 'img' => $uploadsUrl. 'Social_Buttons_256.png', 'desc' => $environment->translate('Social share buttons to increase social traffic and popularity. Social sharing to Facebook, Twitter and other social networks.'), 'download' => $downloadsUrl. 'social-share-buttons-by-supsystic.zip'),
			array('label' => $environment->translate('Live Chat Plugin'), 'url' => $pluginsUrl. 'live-chat/', 'img' => $uploadsUrl. 'Live_Chat_256.png', 'desc' => $environment->translate('Be closer to your visitors and customers with Live Chat Support by Supsystic. Help you visitors, support them in real-time with exceptional Live Chat WordPress plugin by Supsystic.'), 'download' => $downloadsUrl. 'live-chat-by-supsystic.zip'),
			array('label' => $environment->translate('Pricing Table'), 'url' => $pluginsUrl. 'pricing-table/', 'img' => $uploadsUrl. 'Pricing_Table_256.png', 'desc' => $environment->translate('It’s never been so easy to create and manage pricing and comparison tables with table builder. Any element of the table can be customise with mouse click.'), 'download' => $downloadsUrl. 'pricing-table-by-supsystic.zip'),
			array('label' => $environment->translate('Coming Soon Plugin'), 'url' => $pluginsUrl. 'coming-soon-plugin/', 'img' => $uploadsUrl. 'Coming_Soon_256.png', 'desc' => $environment->translate('Coming soon page with drag-and-drop builder or under construction | maintenance mode to notify visitors and collects emails.'), 'download' => $downloadsUrl. 'coming-soon-by-supsystic.zip'),
			array('label' => $environment->translate('Backup Plugin'), 'url' => $pluginsUrl. 'backup-plugin/', 'img' => $uploadsUrl. 'Backup_256.png', 'desc' => $environment->translate('Backup and Restore WordPress Plugin by Supsystic provides quick and unhitched DropBox, FTP, Amazon S3, Google Drive backup for your WordPress website.'), 'download' => $downloadsUrl. 'backup-by-supsystic.zip'),
			array('label' => $environment->translate('Google Maps Easy'), 'url' => $pluginsUrl. 'google-maps-plugin/', 'img' => $uploadsUrl. 'Google_Maps_256.png', 'desc' => $environment->translate('Display custom Google Maps. Set markers and locations with text, images, categories and links. Customize google map in a simple and intuitive way.'), 'download' => $downloadsUrl. 'google-maps-easy.zip'),
			array('label' => $environment->translate('Digital Publication Plugin'), 'url' => $pluginsUrl. 'digital-publication-plugin/', 'img' => $uploadsUrl. 'Digital_Publication_256.png', 'desc' => $environment->translate('Digital Publication WordPress Plugin by Supsystic for Magazines, Catalogs, Portfolios. Convert images, posts, PDF to the page flip book.'), 'download' => $downloadsUrl. 'digital-publications-by-supsystic.zip'),
			array('label' => $environment->translate('Contact Form Plugin'), 'url' => $pluginsUrl. 'contact-form-plugin/', 'img' => $uploadsUrl. 'Contact_Form_256.png', 'desc' => $environment->translate('One of the best plugin for creating Contact Forms on your WordPress site. Changeable fonts, backgrounds, an option for adding fields etc.'), 'download' => $downloadsUrl. 'contact-form-by-supsystic.zip'),
		);
		foreach($pluginsList as $i => $p) {
			if(empty($p['external'])) {
				$pluginsList[$i]['url'] = $pluginsList[$i]['url'] . '?utm_source=plugin&utm_medium=featured_plugins&utm_campaign=' . $promoCampaign;
			}
		}

        return $this->response(
            '@featuredplugins/index.twig',
            array(
                'pluginsList' => $pluginsList,
                'bundleUrl' => 'https://supsystic.com/product/plugins-bundle/'. '?utm_source=plugin&utm_medium=featured_plugins&utm_campaign='. $promoCampaign,
            )
        );
    }
}
