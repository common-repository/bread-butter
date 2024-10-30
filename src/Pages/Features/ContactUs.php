<?php
/**
 * @package breadbutter-connect
 */

namespace BreadButter_WP_Plugin\Pages\Features;

class ContactUs extends Base {
    public function setSettings() {
        $this->settings = array(
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_pages',
                'default' => [],
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_pages_enabled',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_icon_note',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_custom_image',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_header',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),

            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_blur_paragraph_1',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_blur_paragraph_2',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_blur_paragraph_3',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
//            array(
//                'group' => 'breadbutter_contactus_groups',
//                'name' => 'breadbutter_contactus_blur_paragraph_3_2',
//                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
//            ),
//            array(
//                'group' => 'breadbutter_contactus_groups',
//                'name' => 'breadbutter_contactus_blur_more',
//                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
//            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_sub_header',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_collapse_message',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_button',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_success',
                'default' => '',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_show_phone',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_show_company',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_position_vertical',
                'default' => 'bottom',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_position_vertical_px',
                'default' => 10,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_position_horizontal',
                'default' => 'right',
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_position_horizontal_px',
                'default' => 10,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            ),
            array(
                'group' => 'breadbutter_contactus_groups',
                'name' => 'breadbutter_contactus_override_dest',
                'default' => false,
                'callback' => array($this->callbacks, 'breadbutterClientOptionGroups')
            )
        );
    }

    public function setSections() {
        $this->sections = array(
            array(
                'id' => 'breadbutter_contactus_config',
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_contactus_config_enabled',
                'page' => 'breadbutter_connect'
            ),   
            array(
                'id' => 'breadbutter_contactus_config_signed_out',
                'callback' => array($this->callbacks, 'breadbutterContactUsSignoutSection'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_contactus_config_signed_in',
                'callback' => array($this->callbacks, 'breadbutterContactUsSigninSection'),
                'page' => 'breadbutter_connect'
            ),
            array(
                'id' => 'breadbutter_contactus_config_position',
                'callback' => array($this->callbacks, 'breadbutterContactUsPositionSection'),
                'page' => 'breadbutter_connect'
            ),
        );
    }

    public function setFields() {
        $this->fields = array(
            array(
                'id' => 'breadbutter_contactus_pages_enabled',
                'title' => 'Enable Contact Us',
                'callback' => array($this->callbacks, 'breadbutterContactUsPagesEnabled'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_enabled',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_pages_enabled'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_dashboard_position',
                'title' => 'Contact Us Position',
                'callback' => array($this->callbacks, 'breadbutterContactUsDashboardPosition'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_enabled',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_dashboard_position'
                )
            ),

            array(
                'id' => 'breadbutter_contactus_pages',
                'title' => 'Contact us pages',
                'callback' => array($this->callbacks, 'breadbutterContactUsPages'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_pages'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_icon_note',
                'title' => 'Welcome icon message',
                'callback' => array($this->callbacks, 'breadbutterContactUsIconNote'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_out',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_icon_note'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_custom_image',
                'title' => 'Welcome icon URL',
                'callback' => array($this->callbacks, 'breadbutterContactUsCustomImage'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_out',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_custom_image'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_header',
                'title' => 'Signed out header',
                'callback' => array($this->callbacks, 'breadbutterContactUsHeader'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_out',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_header'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_blur_paragraph_1',
                'title' => 'First Paragraph',
                'callback' => array($this->callbacks, 'breadbutterContactUsBlurParagraph1'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_out',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_blur_paragraph_1'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_blur_paragraph_2',
                'title' => 'Second Paragraph',
                'callback' => array($this->callbacks, 'breadbutterContactUsBlurParagraph2'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_out',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_blur_paragraph_2'
                )
            ),
//            array(
//                'id' => 'breadbutter_contactus_blur_paragraph_3',
//                'title' => 'Third Paragraph',
//                'callback' => array($this->callbacks, 'breadbutterContactUsBlurParagraph3'),
//                'page' => 'breadbutter_connect',
//                'section' => 'breadbutter_contactus_config_signed_out',
//                'args' => array(
//                    'label_for' => 'breadbutter_contactus_blur_paragraph_3'
//                )
//            ),
//            array(
//                'id' => 'breadbutter_contactus_blur_paragraph_3_2',
//                'title' => 'Expanded Third Paragraph',
//                'callback' => array($this->callbacks, 'breadbutterContactUsBlurParagraph32'),
//                'page' => 'breadbutter_connect',
//                'section' => 'breadbutter_contactus_config_signed_out',
//                'args' => array(
//                    'label_for' => 'breadbutter_contactus_blur_paragraph_3_2'
//                )
//            ),
//            array(
//                'id' => 'breadbutter_contactus_blur_more',
//                'title' => 'Expand Label',
//                'callback' => array($this->callbacks, 'breadbutterContactUsBlurMore'),
//                'page' => 'breadbutter_connect',
//                'section' => 'breadbutter_contactus_config_signed_out',
//                'args' => array(
//                    'label_for' => 'breadbutter_contactus_blur_more'
//                )
//            ),
            array(
                'id' => 'breadbutter_contactus_sub_header',
                'title' => 'Signed in header',
                'callback' => array($this->callbacks, 'breadbutterContactUsSubHeader'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_in',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_sub_header'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_button',
                'title' => 'Submit button label',
                'callback' => array($this->callbacks, 'breadbutterContactUsButton'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_in',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_button'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_success',
                'title' => 'Success message',
                'callback' => array($this->callbacks, 'breadbutterContactUsSuccess'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_in',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_success'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_show_phone',
                'title' => 'Show phone number',
                'callback' => array($this->callbacks, 'breadbutterContactUsShowPhone'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_in',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_show_phone'
                )
            ),

            array(
                'id' => 'breadbutter_contactus_show_company',
                'title' => 'Show company name',
                'callback' => array($this->callbacks, 'breadbutterContactUsShowCompany'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_signed_in',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_show_company'
                )
            ),

            array(
                'id' => 'breadbutter_contactus_position_vertical',
                'title' => 'Contact Us Position (Vertical)',
                'callback' => array($this->callbacks, 'breadbutterContactUsPositionVertical'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_position',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_position_vertical'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_position_vertical_px',
                'title' => 'Margin',
                'callback' => array($this->callbacks, 'breadbutterContactUsPositionVerticalPx'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_position',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_position_vertical_px'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_position_horizontal',
                'title' => 'Contact Us Position (Horizontal)',
                'callback' => array($this->callbacks, 'breadbutterContactUsPositionHorizontal'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_position',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_position_horizontal'
                )
            ),
            array(
                'id' => 'breadbutter_contactus_position_horizontal_px',
                'title' => 'Margin',
                'callback' => array($this->callbacks, 'breadbutterContactUsPositionHorizontalPx'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_position',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_position_horizontal_px'
                )
            ),

            array(
                'id' => 'breadbutter_contactus_override_dest',
                'title' => 'Override Registration Destination URL',
                'callback' => array($this->callbacks, 'breadbutterContactUsOverrideDest'),
                'page' => 'breadbutter_connect',
                'section' => 'breadbutter_contactus_config_position',
                'args' => array(
                    'label_for' => 'breadbutter_contactus_override_dest'
                )
            ),

        );
    }
}