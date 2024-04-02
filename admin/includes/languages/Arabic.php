<?php

function lang($phrase) {
  static $lang = array(

  // navbar links
  'HOME_ADMIN'  => 'الرئيسية',
  'CATEGORIES'  => 'الفئات',
  'ITEMS'       => 'العناصر',
  'MEMBERS'     => 'الأعضاء',
  'STATISTICS'  => 'الإحصائيات',
  'LOGS'        => 'السجلات',
  'EditProfile' => 'تعديل الملف الشخصي',
  'Settings'    => 'الإعدادات',
  'Logout'      => 'تسجيل الخروج',
  '' => '',
  '' => '',
  '' => '',

  );
  return $lang[$phrase];
}
