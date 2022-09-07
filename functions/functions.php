<?php

// Add custom post type
function add_custom_post_type_tab(){
    register_post_type( 'ld-ct',
    array(
      'labels' => array(
       'name' => __( 'Custom Tabs' ),
       'singular_name' => __( 'Custom Tabs' ),
      ),
      'public' => true,
      'has_archive' => false,
      'rewrite' => array('slug' => 'ld-ct'),
      'supports' => array( 'title', 'editor', 'custom-fields' ),
      'show_in_menu' => 'admin.php',
      'menu_position' => 10,
     )
    );
}
add_action( 'init', 'add_custom_post_type_tab' );


// Add custom Tab
function ldss_add_submenu( $submenus ) {
  $submenus['ld-ct'] = array(
      'name' => _x( 'Custom Tabs', 'Custom Tabs', 'ld-ct' ),
        'cap' => 'edit_courses',
        'link' => 'edit.php?post_type=ld-ct',
              
  );
  return $submenus;
}
add_filter( 'learndash_submenu', 'ldss_add_submenu' );


//Metabox functions - List users
function ldct_get_users(){
  $users = get_users();
  $users_list[] = '';
  $users_list[0] = 'All Users';
  foreach( $users as $user ) {
      $users_list[$user->ID] = $user->display_name;
  }
  return $users_list;
}

//Metabox functions - list courses

function ldct_get_all_course() {
  global $wpdb;

	$course_list[] = '';
  $course_list[0] = 'All Courses';

  $rows = $wpdb->get_results('select * from wp_posts where post_type = "sfwd-courses"');
  foreach($rows as $row){
    $course_list[$row->ID] = $row->post_title;
  }
  return $course_list;
  wp_reset_query();
}

//Metabox functions -- list lessons
function ldct_get_all_lesson() {

  global $wpdb;

	$lesson_list[] = '';
  $lesson_list[0] = 'All Lesson';

  $rows = $wpdb->get_results('select * from wp_posts where post_type = "sfwd-lessons"');
  foreach($rows as $row){
    $lesson_list[$row->ID] = $row->post_title;
  }
  return $lesson_list;
  wp_reset_query();
}

//Metabox functions list topics

function ldct_get_all_topic() {

  global $wpdb;

	$topic_list[] = '';
  $topic_list[0] = 'All Topics';

  $rows = $wpdb->get_results('select * from wp_posts where post_type = "sfwd-topic"');
  foreach($rows as $row){
    $topic_list[$row->ID] = $row->post_title;
  }
  return $topic_list;
  wp_reset_query();

}

//Metabox functions - list quizes

function ldct_get_all_quiz() {

  global $wpdb;

	$quiz_list[] = '';
  $quiz_list[0] = 'All Quiz';

  $rows = $wpdb->get_results('select * from wp_posts where post_type = "sfwd-quiz"');
  foreach($rows as $row){
    $quiz_list[$row->ID] = $row->post_title;
  }
  return $quiz_list;
  wp_reset_query();

}

// Add Meta fields
function ld_ct_meta_boxes($meta_boxes){
  $meta_boxes[] = [
    'title' => esc_html__('Custom Tab Settings', 'ld-ct'),
    'post_types' => [ 'ld-ct' ],
    'context' => 'advanced',
   'fields' => [
     
    [
      'name'   => esc_html__('Display Tab to', 'ld-ct'),
       'id'    => "ld_display_to",
      'type' 	 => 'select',
      'options'  => ldct_get_users()
    ],
    
    [
      'name'   => esc_html__('Display Tab on', 'ld-ct'),
      'id'    	=> "ld_display_on",
      'type' 	=> 'select',
      'options'         => [
          'all'      => 'All Pages',
          'Course'   => 'Courses',
          'Lesson'   => 'Lessons',
          'Topic'    => 'Topics',
          'Quiz'    => 'Quizzes',
        ],
      ],
      
      [
      'name'   => esc_html__('Select Course', 'ld-ct'),
      'id'    	=> "ld_course",
      'type' 	=> 'select',
      'options' => ldct_get_all_course()

      ],

      [
      'name'   => esc_html__('Select Lesson', 'ld-ct'),
      'id'    	=> "ld_lesson",
      'type' 	=> 'select',
      'options' => ldct_get_all_lesson()

      ],

      [
      'name'   => esc_html__('Select Topic', 'ld-ct'),
      'id'    	=> "ld_topic",
      'type' 	=> 'select',
      'options' => ldct_get_all_topic( )
      ],

      [
      'name'   => esc_html__('Select Quiz', 'ld-ct'),
      'id'    	=> "ld_quiz",
      'type' 	=> 'select',
      'options' => ldct_get_all_quiz( )
      ],

      [
        'name'   => esc_html__('Icon', 'ld-ct'),
        'id'    	=> "ld_icon",
        'type' 	=> 'text',
        'std'   	=> ''
      ],

     ],
  ];
  return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes',  'ld_ct_meta_boxes' );

// Add Tab to frontend LearnDash
add_filter(
  'learndash_content_tabs',
  function( $tabs = array(), $context = '', $course_id = 0, $user_id = 0 ) {

     
    $ld_ct_id = '';
    $ld_ct_title = '';
    $ld_ct_content = '';
      $args = array(
        'post_type' => 'ld-ct',
      );
      $loop = new WP_Query($args);

      while($loop->have_posts()): $loop->the_post();
        $ld_ct_id =  get_the_ID();
        $ld_ct_title = get_the_title();
        $ld_ct_content = get_the_content();
        
      endwhile;

      wp_reset_query();

    // Meta data
    $ld_display_to = get_post_meta($ld_ct_id, 'ld_display_to',true); //user role condition
    $ld_display_on = get_post_meta($ld_ct_id, 'ld_display_on',true);// specific pages //courses,lessons,topics,quizes
    $ld_course = get_post_meta($ld_ct_id, 'ld_course',true);
    $ld_lesson = get_post_meta($ld_ct_id, 'ld_lesson',true);
    $ld_topic = get_post_meta($ld_ct_id, 'ld_topic',true);
    $ld_quiz = get_post_meta($ld_ct_id, 'ld_quiz',true);
    $ld_icon = get_post_meta($ld_ct_id, 'ld_icon',true);
    
   // conditional logic

  // get current post type
  $post = get_queried_object();
  $postType = get_post_type_object(get_post_type($post));
  if ($postType) {
      $ld_post_type = esc_html($postType->labels->singular_name);
  }

  //Show only to this user if user is selected
  if($ld_display_to == 0 || $ld_display_to == get_current_user_id()){

    // specific pages //courses,lessons,topics,quizes
 
    // echo $ld_post_type . '<br>';


    if($ld_post_type == 'Course'){
       // Show on course
      if($ld_display_on== 'all' || $ld_display_on == $ld_post_type){
        if($ld_course == 0 || $ld_course == learndash_get_course_id() ){
          if ( ! isset( $tabs['download'] ) ) {
            $tabs['download'] = array(
                'id'      => 'download',
                'icon'    => $ld_icon,
                'label'   => $ld_ct_title,
                'content' => '<p>'.$ld_ct_content.'</p>',
            );
          }
        }
      }
    }
    
    else if($ld_post_type == 'Lesson'){
       // Show on lesson

      if($ld_display_on== 'all' || $ld_display_on == $ld_post_type){
        if($ld_lesson == 0 || $ld_lesson == get_the_ID()){
          if ( ! isset( $tabs['download'] ) ) {
            $tabs['download'] = array(
                'id'      => 'download',
                'icon'    => $ld_icon,
                'label'   => $ld_ct_title,
                'content' => '<p>'.$ld_ct_content.'</p>',
            );
          }
        }

      }
    }

    else if($ld_post_type == 'Topic'){
      // Show on lesson
     if($ld_display_on== 'all' || $ld_display_on == $ld_post_type){
       if($ld_topic == 0 || $ld_topic == get_the_ID()){
         if ( ! isset( $tabs['download'] ) ) {
           $tabs['download'] = array(
               'id'      => 'download',
               'icon'    => $ld_icon,
               'label'   => $ld_ct_title,
               'content' => '<p>'.$ld_ct_content.'</p>',
           );
         }
       }

     }
   }

   else if($ld_post_type == 'Quiz'){
 
    // Show on lesson
   if($ld_display_on== 'all' || $ld_display_on == $ld_post_type){
     if($ld_quiz == 0 || $ld_quiz == get_the_ID()){
       if ( ! isset( $tabs['download'] ) ) {
         $tabs['download'] = array(
             'id'      => 'download',
             'icon'    => $ld_icon,
             'label'   => $ld_ct_title,
             'content' => '<p>'.$ld_ct_content.'</p>',
         );
       }
     }

   }
 }

 else{}
  }
  
  return $tabs;
  },
  10,
  4
);



