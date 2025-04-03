<?php

return [
    'singular' => 'サイト設定',
    'fb_url_error' => 'https://facebook.com/',
    'linkedin_url_error' => 'https://linkedin.com/',
    'twitter_url_error' => 'https://twitter.com/',
    'instagram_url_error' => 'https://instagram.com/',
    'youtube_url_error' => 'https://youtube.com/',
    'fields' => [
        'setting_id' => '設定ID',
        'site_name' => 'サイト名',
        'site_logo' => 'サイトロゴ',
        'meta_key' => 'メタキー',
        'meta_description' => 'メタ説明',
        'seo_robots' => 'SEOロボット',
        'admin_email' => '管理メール',
        'company_address' => '会社住所',
        'company_tel' => '電話番号',
        'company_tel1' => '会社電話番号',
        'company_email' => '会社メールアドレス',
        'company_website' => '会社ウェブサイト',
        'google_map' => 'Googleマップ',
        'google_analytics' => 'Googleアナリティクス',
        'cc_admin_email' => 'CC管理メール',
        'cc_contact_email' => 'CC連絡メール',
        'remarks' => '備考',
        'updated_by' => '更新者',
        'updated_at' => '更新日',
    ],
    // validation msg
    'validation' => [
        'company_tel.regex' => 'Company Tel can contain plus with numbers, space and dash only',
        'company_tel1.regex' => 'Company Tel1 can contain plus with numbers, space and dash only',
    ],
];
