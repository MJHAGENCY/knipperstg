<?php

class SocialSharing_Projects_Widget extends WP_Widget
{
	/**
	 * @var SocialSharing_Projects_Handler
	 */
	protected $sharer;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(SocialSharing_Projects_Module $sharer)
	{
		parent::__construct(
			'supsystic_social_sharing',
			'Social Sharing',
			array('description' => 'Social sharing project')
		);

		$this->sharer = $sharer;
	}

	/**
	 * {@inheritdoc}
	 */
	public function widget($args, $instance)
	{
		$title = apply_filters( 'widget_title', $instance['title'] );

		if(!empty($instance['project'])) {
			$project = $this->sharer->getController()
				->getModelsFactory()
				->get('projects', $this->sharer)
				->get($instance['project']);

			if($project && !empty($project->settings['where_to_show']) && $project->settings['where_to_show'] == 'widget') {
				$handler = new SocialSharing_Projects_Handler(
					new SocialSharing_Projects_Project((array)$project),
					$this->sharer->getEnvironment()
				);

				if($project) {
					echo sprintf(
						'%1$s%2$s%3$s%4$s',
						wp_kses_post($args['before_widget']),
						wp_kses_post($title ? $args['before_title'] . $title . $args['after_title'] : ''),
						wp_kses_post($handler->build()),
						wp_kses_post($args['after_widget'])
					);
				}
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function form($instance)
	{
		$instance = wp_parse_args((array)$instance, array('title' => '', 'project' => ''));
		$title = strip_tags($instance['title']);
		$project = strip_tags($instance['project']);

		$projects = $this->sharer->getController()
			->getModelsFactory()
			->get('projects', $this->sharer)
			->all();

		$options = '';
		if (is_array($projects) && count($projects) > 0) {
			foreach ($projects as $pr) {
				if(!empty($pr->settings['where_to_show']) && $pr->settings['where_to_show'] == 'widget') {
					$options .= '<option value="'.esc_attr($pr->id).'"'.($project == esc_attr($pr->id) ? ' selected="selected"' : '').'>'.esc_html($pr->title).'</option>';
				}
			}
		}

		$allowed_html = array(
			'select' => array(
				'id'  => true,
				'name' => true,
				'class' => true,
			),
			'option'     => array(
				'id'  => true,
				'name' => true,
				'class' => true,
			),
		);

		echo '<p>' .
			'<label for="'.esc_attr($this->get_field_id('title')).'">Title:</label>' .
			'<input class="widefat" id="'.esc_attr($this->get_field_id('title')).'" name="'.esc_attr($this->get_field_name('title')).'" value="'.esc_html($title).'">' .
			'</p>'.
			'<p>' .
			'<label for="'.esc_attr($this->get_field_id('project')).'">Project:</label>' .
			(empty($options)
				? '<div><strong>No widget projects</strong></div>'
				: '<select style="width:100%" id="'.esc_attr($this->get_field_id('project')).'" name="'.esc_attr($this->get_field_name('project')).'">'.wp_kses( $options, $allowed_html ).'</select>'
			).
			'</p>';
	}

	/**
	 * {@inheritdoc}
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['project'] = strip_tags($new_instance['project']);

		return $instance;
	}
}
