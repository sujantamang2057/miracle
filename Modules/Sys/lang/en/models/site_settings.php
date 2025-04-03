<?php

return [
    'singular' => 'Site Setting',
    'fb_url_error' => 'https://facebook.com/',
    'linkedin_url_error' => 'https://linkedin.com/',
    'twitter_url_error' => 'https://twitter.com/',
    'instagram_url_error' => 'https://instagram.com/',
    'youtube_url_error' => 'https://youtube.com/',
    'fields' => [
        'setting_id' => 'Setting Id',
        'site_name' => 'Site Name',
        'site_logo' => 'Site Logo',
        'meta_key' => 'Meta Key',
        'meta_description' => 'Meta Description',
        'seo_robots' => 'SEO Robots',
        'admin_email' => 'Admin Email',
        'company_address' => 'Company Address',
        'company_tel' => 'Company Tel',
        'company_tel1' => 'Company Tel1',
        'company_email' => 'Company Email',
        'company_website' => 'Company Website',
        'google_map' => 'Google Map',
        'google_analytics' => 'Google Analytics',
        'cc_admin_email' => 'Cc Admin Email',
        'cc_contact_email' => 'Cc Contact Email',
        'remarks' => 'Remarks',
        'updated_by' => 'Updated By',
        'updated_at' => 'Updated At',
    ],
    // validation msg
    'validation' => [
        'company_tel.regex' => 'Company Tel can contain plus with numbers, space and dash only',
        'company_tel1.regex' => 'Company Tel1 can contain plus with numbers, space and dash only',
    ],
];
