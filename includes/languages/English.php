<?php

function lang($phrase) {
  static $lang = array(

  // navbar links
  'HOME_ADMIN'  => 'Home',
  'CATEGORIES'  => 'Categories',
  'ITEMS'       => 'Items',
  'MEMBERS'     => 'Members',
  'COMMENTS'     => 'Comments',
  'STATISTICS'  => 'Statistics',
  'LOGS'        => 'Logs',
  'EditProfile' => 'Edit Profile',
  'Settings'    => 'Settings',
  'Logout'      => 'Logout',
  ''            => '',
  ''            => '',
  ''            => '',

  );
  return $lang[$phrase];
}
