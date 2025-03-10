<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SiteSetting extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    public static $keys = [

        "website_title" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Website Title"
        ],
        "logo" => [
            "type" => "image",
            "element" => "image",
            "visible" => 1,
            "display_text" => "Upload App Logo"
        ],
        "address" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Address"
        ],
        "contact_no" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Contact No"
        ],
        "home_page_banner_1" => [
            "type" => "image",
            "element" => "image",
            "visible" => 1,
            "display_text" => "Home Page Banner"
        ],
        "home_page_banner_2" => [
            "type" => "image",
            "element" => "image",
            "visible" => 1,
            "display_text" => "Home Page Banner"
        ],
        "home_page_banner_3" => [
            "type" => "image",
            "element" => "image",
            "visible" => 1,
            "display_text" => "Home Page Banner"
        ],
        "privacy_policy" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Privacy Policy"
        ],
        "terms_conditions" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Terms and Conditions"
        ],
        "facebook_link" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Facebook Link"
        ],
        "instagram_link" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Instagram Link"
        ],
        "tiktok_link" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Tiktok Link"
        ],
        "whatsapp_number" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Whatsapp Number"
        ],
        "footer_background_color" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Footer Background Color"
        ],
        "footer_text_color" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Footer Text Color"
        ],
        "primary_nav_background_color" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Primary Nav Background Color"
        ],
        "primary_nav_text_color" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Primary Nav Background Color"
        ],
        "secondary_nav_background_color" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Secondary Nav Background Color"
        ],
        "secondary_nav_text_color" => [
            "type" => "text",
            "element" => "text",
            "visible" => 1,
            "display_text" => "Secondary Nav Text Color"
        ],

    ];
}
