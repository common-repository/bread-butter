( function( plugins, editPost, element, components, data, compose, hooks ) {

    const el = element.createElement;

    const { Fragment } = element;
    const { registerPlugin } = plugins;
    const { PluginSidebar, PluginSidebarMoreMenuItem } = editPost;
    const { PanelBody, TextControl, TextareaControl, CheckboxControl, ToggleControl, SelectControl, RangeControl } = components;
    const { withSelect, withDispatch } = data;
    const { addFilter } = hooks;
    const breadIcon = el('svg',
        {
            width: 24,
            height: 24,
            viewBox: '0 0 50 50'
        },
        el( 'path',
            {
                d: "m32.365,20.745l-9.75,-4.21c-0.91,-0.34 -1.92,0.11 -2.27,1.02l-3.87,10.91c-0.34,0.91 0.11,1.92 1.02,2.27l10.98,3.81c0.91,0.34 1.92,-0.11 2.27,-1.02l2.64,-10.52c0.34,-0.9 -0.12,-1.91 -1.02,-2.26zm12.91,1.55c1.66,-1.65 2.49,-4.15 2.49,-7.44c0,-8.08 -8.1,-12.63 -22.83,-12.81c-14.65,0.17 -22.71,4.72 -22.71,12.81c0,3.29 0.84,5.79 2.49,7.44c0.18,0.18 0.37,0.35 0.57,0.51c-2.92,5.92 -3.06,18.83 -3.06,21.41c0.03,3.17 2.62,5.74 5.79,5.74l33.97,0c3.16,0 5.76,-2.58 5.79,-5.76c0,-2.57 -0.15,-15.47 -3.06,-21.39c0.19,-0.17 0.38,-0.34 0.56,-0.51zm-0.23,21.87c-0.02,1.69 -1.39,3.06 -3.07,3.06l-33.96,0c-1.68,0 -3.05,-1.36 -3.07,-3.02c0,-6.71 0.73,-16.03 2.78,-20.2c0.57,-1.15 0.25,-2.55 -0.76,-3.34c-0.12,-0.09 -0.23,-0.19 -0.33,-0.3c-1.12,-1.12 -1.69,-2.97 -1.69,-5.51c0,-8.55 12.51,-9.99 19.99,-10.08c7.53,0.09 20.11,1.53 20.11,10.08c0,2.54 -0.57,4.39 -1.69,5.51c-0.11,0.11 -0.22,0.2 -0.33,0.3c-1.01,0.8 -1.33,2.19 -0.76,3.34c2.05,4.17 2.78,13.48 2.78,20.16z"
                // d: "m18.01462,8.53517a4.00366,4.00366 0 0 0 0.98622,-2.94035c0,-3.1953 -3.20598,-4.99218 -9.02332,-5.06088c-5.78909,0.06946 -8.97828,1.86634 -8.97828,5.06088a4.00061,4.00061 0 0 0 0.98622,2.94035a2.49151,2.49151 0 0 0 0.229,0.19999c-1.15186,2.34113 -1.2114,7.44246 -1.2114,8.46151a2.29533,2.29533 0 0 0 2.28999,2.26938l13.41933,0a2.29991,2.29991 0 0 0 2.28999,-2.27701c0,-1.01676 -0.0603,-6.11427 -1.2114,-8.45311a2.37701,2.37701 0 0 0 0.229,-0.19999m-14.24602,0.11145a0.31373,0.31373 0 0 0 0.14427,-0.35189a0.31755,0.31755 0 0 0 -0.30075,-0.23663c-0.05725,0 -1.40758,-0.05496 -1.40758,-2.4625c0,-3.27087 4.87004,-3.82123 7.80047,-3.85634l0,0c2.90752,0.03435 7.78596,0.58166 7.78596,3.85634c0,2.39915 -1.34117,2.46174 -1.40834,2.4625a0.31602,0.31602 0 0 0 -0.15267,0.58853c1.13965,0.66028 1.56483,5.59063 1.56483,8.53937a1.08698,1.08698 0 0 1 -1.08393,1.074l-13.42849,0a1.08545,1.08545 0 0 1 -1.08316,-1.06866c0,-2.9518 0.42365,-7.88138 1.56406,-8.54242"
            }
        )
    );


    const MetaRangeControl = compose.compose(
        withDispatch( function( dispatch, props ) {
            return {
                setMetaValue: function( metaValue ) {
                    dispatch( 'core/editor' ).editPost(
                        { meta: { [ props.metaKey ]: metaValue } }
                    );
                }
            }
        } ),
        withSelect( function( select, props ) {
            return {
                metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
            }
        } ) )( function( props ) {
            return el( RangeControl, {
                label: props.title,
                value: props.metaValue,
                min: props.minValue,
                max: props.maxValue,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
            });
        }
    );

    const MetaTextControl = compose.compose(
        withDispatch( function( dispatch, props ) {
            return {
                setMetaValue: function( metaValue ) {
                    dispatch( 'core/editor' ).editPost(
                        { meta: { [ props.metaKey ]: metaValue } }
                    );
                }
            }
        } ),
        withSelect( function( select, props ) {
            return {
                metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
            }
        } ) )( function( props ) {
            return el( TextControl, {
                label: props.title,
                value: props.metaValue,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
            });
        }
    );
    const MetaToggleControl = compose.compose(
        withDispatch( function( dispatch, props ) {
            return {
                setMetaValue: function( metaValue ) {
                    dispatch( 'core/editor' ).editPost(
                        { meta: { [ props.metaKey ]: metaValue } }
                    );
                }
            }
        } ),
        withSelect( function( select, props ) {
            return {
                metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
            }
        } ) )( function( props ) {
            return el( ToggleControl, {
                label: props.title,
                checked: props.metaValue ? true : false,
                onChange: function( content ) {
                    props.setMetaValue( content );
                    // if (props.onChange) {
                    //     props.onChange(content);
                    // }
                },
            });
        }
    );

    const MetaSelectControl = compose.compose(
        withDispatch( function( dispatch, props ) {
            return {
                setMetaValue: function( metaValue ) {
                    dispatch( 'core/editor' ).editPost(
                        { meta: { [ props.metaKey ]: metaValue } }
                    );
                }
            }
        } ),
        withSelect( function( select, props ) {
            return {
                metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
            }
        } ) )( function( props ) {
            return el( SelectControl, {
                label: props.title,
                value: props.metaValue,
                options: props.options,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
            });
        }
    );

    registerPlugin( 'breadbutter-connect', {
        render: function() {
            return el( Fragment, {},
                el( PluginSidebarMoreMenuItem,
                    {
                        target: 'breadbutter-connect',
                        icon: breadIcon,
                    },
                    'Bread & Butter'
                ),
                el( PluginSidebar, {
                        name: 'breadbutter-connect',
                        icon: breadIcon,
                        title: 'Bread & Butter Setup',
                    },
                    // el( PanelBody, {initialOpen: true},
                    //     // Field 1
                    //     // Field 3
                    // ),
                    el( PanelBody, {title: '\'Continue with\' Tool', initialOpen: true},
                        // Field 1
                        // Field 3
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_enabled',
                                title : 'Enable page specific options',
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_continue_with',
                                title : 'Display on page load',
                            }
                        ),
                    ),
                    el( PanelBody, { title: '\'Continue with\' Settings', initialOpen: false },
                        el( MetaSelectControl,
                            {
                                metaKey: 'breadbutter_continue_with_position_vertical',
                                title : 'Continue With Position (Vertical)',
                                options: [
                                    {label: 'Default', value: 'default'},
                                    {label: 'Top', value: 'top'},
                                    {label: 'Bottom', value: 'bottom'},
                                ]
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_continue_with_position_vertical_px',
                                title : 'Margin (px)'
                            }
                        ),
                        el( MetaSelectControl,
                            {
                                metaKey: 'breadbutter_continue_with_position_horizontal',
                                title : 'Continue With Position (Horizontal)',
                                options: [
                                    {label: 'Default', value: 'default'},
                                    {label: 'Right', value: 'right'},
                                    {label: 'Left', value: 'left'},
                                ]
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_continue_with_position_horizontal_px',
                                title : 'Margin (px)'
                            }
                        ),
                        // el( MetaToggleControl,
                        //     {
                        //         metaKey: 'breadbutter_post_expand_email_address',
                        //         title : 'Show Email Address Field',
                        //     }
                        // ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_user_profile_tool',
                                title : 'User Profile Tool',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_continue_with_popup_delay_seconds',
                                title : '‘Continue with’ popup delay (Seconds)',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_continue_with_success_seconds',
                                title : 'Succces message seconds',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_continue_with_success_header',
                                title : 'Success message header',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_continue_with_success_text',
                                title : 'Success message',
                            }
                        ),
                    ),
                    el( PanelBody, { title: '\'Continue with\' Header', initialOpen: false },
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_header_1',
                                title : 'New User (no Display Name set)'
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_header_2',
                                title : 'New User (Display Name set)'
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_header_back_1',
                                title : 'Returning User (no Display Name set)'
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_header_back_2',
                                title : 'Returning User (Display Name set)'
                            }
                        ),
                    ),
                    el( PanelBody, { title: 'Authentication Prompt', initialOpen: false },
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_show_login_focus',
                                title : 'Enable Authentication Prompt',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_blur_text_1',
                                title : 'First Paragraph'
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_blur_text_2',
                                title : 'Second Paragraph'
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_blur_text_3',
                                title : 'Third Paragraph'
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_blur_text_3_2',
                                title : 'Expanded Third Paragraph'
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_blur_more_text',
                                title : 'Expand label'
                            }
                        ),
                    ),
                    el( PanelBody, { title: 'Advanced Settings (Optional)', initialOpen: false },
                        el( MetaSelectControl,
                            {
                                metaKey: 'breadbutter_post_button_theme',
                                title : 'Button Theme',
                                options: [
                                    {label: 'Icons', value: 'round-icons'},
                                    {label: 'Tiles', value: 'tiles'},
                                ]
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_app_name',
                                title : 'Display Name',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_destination_url',
                                title : 'Destination URL',
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_as_destination_url',
                                title : 'Use current page for Destination URL',
                            }
                        ),//
                        // Field 3
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_callback_url',
                                title : 'Callback URL',
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_is_restricred',
                                title : 'Blur Screen Prompt',
                            }
                        ),
                        // el( MetaToggleControl,
                        //     {
                        //         metaKey: 'breadbutter_post_is_gated',
                        //         title : 'Gate page to signed in users',
                        //     }
                        // ),
                        // el( MetaTextControl,
                        //     {
                        //         metaKey: 'breadbutter_post_client_data',
                        //         title : 'Client Data',
                        //     }
                        // ),
                        // el( MetaSelectControl,
                        //     {
                        //         metaKey: 'breadbutter_post_force_reauthentication',
                        //         title : 'Force reauthentication',
                        //         options: [
                        //             {label: 'Off', value: 'off'},
                        //             {label: 'Attempt', value: 'attempt'},
                        //             {label: 'Force', value: 'force'},
                        //         ]
                        //     }
                        // ),
                    ),
                    // el( PanelBody, {initialOpen: true},
                    //     // Field 1
                    //     // Field 3
                    //     el( MetaToggleControl,
                    //         {
                    //             metaKey: 'breadbutter_post_is_gated',
                    //             title : 'Enable Content Gating',
                    //         }
                    //     ),
                    // ),
                    el( PanelBody, { title: 'Content Gating', initialOpen: false },
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_is_gated',
                                title : 'Enable Content Gating',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_gating_header',
                                title : 'Header',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_gating_subheader',
                                title : 'Sub Header',
                            }
                        ),
                        el( MetaSelectControl,
                            {
                                metaKey: 'breadbutter_post_content_gating_custom_image_type',
                                title : 'Header Image',
                                options: [
                                    {label: 'Default Image', value: 'default'},
                                    {label: 'Fill', value: 'fill'},
                                    {label: 'Center', value: 'center'},
                                    {label: 'No Image', value: 'none'}
                                ]
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_gating_custom_image',
                                title : 'Custom Image URL (Optional)',
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_content_gating_override_dest',
                                title : 'Override Registration Destination URL',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_gating_scroll_limit',
                                title : 'Percent scrolled until enabled (%)',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_gating_time_limit',
                                title : 'Seconds until enabled',
                            }
                        )
                    ),

                    // el( PanelBody, {initialOpen: true},
                    //     // Field 1
                    //     // Field 3
                    //     el( MetaToggleControl,
                    //         {
                    //             metaKey: 'breadbutter_post_content_preview_enabled',
                    //             title : 'Enable Content Preview',
                    //         }
                    //     ),
                    // ),
                    el( PanelBody, { title: 'Content Preview', initialOpen: false },
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_enabled',
                                title : 'Enable Content Preview',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_text_1',
                                title : 'Header',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_text_2',
                                title : 'Sub Header',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_text_3',
                                title : 'Bottom Paragraph',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_text_3_2',
                                title : 'Expanded Bottom Paragraph',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_text_more',
                                title : 'Expand label',
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_override_dest',
                                title : 'Override Registration Destination URL',
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_clickable_content',
                                title : 'Allow page to be clickable',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_scroll_limit',
                                title : 'Percent scrolled until enabled (%)',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_time_limit',
                                title : 'Seconds until enabled',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_content_preview_height',
                                title : 'Maximum height (%)',
                            }
                        )
                    ),

                    // el( PanelBody, {initialOpen: true},
                    //     el( MetaToggleControl,
                    //         {
                    //             metaKey: 'breadbutter_post_newsletter_enabled',
                    //             title : 'Enable Newsletter Signup',
                    //         }
                    //     ),
                    // ),
                    el( PanelBody, { title: 'Opt-in Popup', initialOpen: false },
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_enabled',
                                title : 'Enable Opt-in Popup',
                            }
                        ),
                        // el( MetaSelectControl,
                        //     {
                        //         metaKey: 'breadbutter_post_newsletter_type',
                        //         title : 'Choose Opt-in type',
                        //         options: [
                        //             {label: 'Custom', value: 'custom'},
                        //             {label: 'Contest', value: 'contest'},
                        //             {label: 'Special Offer', value: 'special_offer'},
                        //             {label: 'Newsletter Signup', value: 'newsletter_signup'},
                        //         ]
                        //     }
                        // ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_header',
                                title : 'Header',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_main_message',
                                title : 'Main Message',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_success_header',
                                title : 'Success Header',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_success_message',
                                title : 'Success Message',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_delay_popup',
                                title : 'Popup Delay (Seconds)',
                            }
                        ),
                        el( MetaSelectControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_custom_image_type',
                                title : 'Header Image',
                                options: [
                                    {label: 'Default Image', value: 'default'},
                                    {label: 'Fill', value: 'fill'},
                                    {label: 'Center', value: 'center'},
                                    {label: 'No Image', value: 'none'},
                                ]
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_custom_image',
                                title : 'Header Image URL (Optional)',
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_override_dest',
                                title : 'Override Registration Destination URL',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_newsletter_event_code',
                                title : 'User event code',
                            }
                        )
                    ),

                    // el( PanelBody, {initialOpen: true},
                    //     // Field 1
                    //     // Field 3
                    //     el( MetaToggleControl,
                    //         {
                    //             metaKey: 'breadbutter_post_contactus_enabled',
                    //             title : 'Enable Contact Us',
                    //         }
                    //     ),
                    // ),
                    el( PanelBody, { title: 'Contact Us Tool', initialOpen: false },
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_contactus_enabled',
                                title : 'Enable Contact Us',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_icon_message',
                                title : 'Welcome icon message',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_custom_image',
                                title : 'Welcome icon URL (Optional)',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_header',
                                title : 'Signed out header',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_paragraph_1',
                                title : 'First Paragraph',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_paragraph_2',
                                title : 'Second Paragraph',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_sub_header',
                                title : 'Signed in header',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_button_label',
                                title : 'Submit button label',
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_success_message',
                                title : 'Success message',
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_contactus_show_phone',
                                title : 'Show phone number',
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_contactus_show_company',
                                title : 'Show company name',
                            }
                        ),
                        el( MetaSelectControl,
                            {
                                metaKey: 'breadbutter_post_contactus_position_vertical',
                                title : 'Contact Us Position (Vertical)',
                                options: [
                                    {label: 'Bottom', value: 'bottom'},
                                    {label: 'Top', value: 'top'}
                                ]
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_position_vertical_px',
                                title : 'Margin (px)'
                            }
                        ),
                        el( MetaSelectControl,
                            {
                                metaKey: 'breadbutter_post_contactus_position_horizontal',
                                title : 'Contact Us Position (Horizontal)',
                                options: [
                                    {label: 'Right', value: 'right'},
                                    {label: 'Left', value: 'left'},
                                ]
                            }
                        ),
                        el( MetaTextControl,
                            {
                                metaKey: 'breadbutter_post_contactus_position_horizontal_px',
                                title : 'Margin (px)'
                            }
                        ),
                        el( MetaToggleControl,
                            {
                                metaKey: 'breadbutter_post_contactus_override_dest',
                                title : 'Override Registration Destination URL',
                            }
                        ),
                    )
                    // el( PanelBody, { title: 'Custom Events', initialOpen: false },
                    //     el( MetaToggleControl,
                    //         {
                    //             metaKey: 'breadbutter_post_custom_event_select',
                    //             title : 'Select Element Inside Editor',
                    //             onChange: function(event) {
                    //                 console.log(event);
                    //                 const elements = document.querySelectorAll('.edit-post-visual-editor__content-area *');
                    //                 elements.forEach(function(element) {
                    //                     element.addEventListener('mouseover', function(event) {
                    //                         event.target.classList.add('bb-hightlight');
                    //                         console.log(event.target.id)
                    //                     });
                    //                     element.addEventListener('mouseout', function(event) {
                    //                         event.target.classList.remove('bb-hightlight');
                    //                     });
                    //                     element.addEventListener('click', function(event) {
                    //                         console.log('click', event.target);
                    //                     });
                    //                 });
                    //             },
                    //         }
                    //     ),
                    //     el( MetaInputControl,
                    //         {
                    //             metaKey: 'breadbutter_post_custom_event_data',
                    //             // title : '',
                    //             inputType: 'hidden',
                    //         }
                    //     ),
                        
                    // )
                )
            );
        }
    } );

    function addBBAttribute(settings, name) {
        if (typeof settings.attributes !== 'undefined') {
            settings.attributes = Object.assign(settings.attributes, {
                gateToSignedIn: {
                    type: 'boolean',
                }
            });
        }
        return settings;
    }
     
    addFilter(
        'blocks.registerBlockType',
        'bb/custom-attribute',
        addBBAttribute
    );

    var withInspectorControls = wp.compose.createHigherOrderComponent( function (
        BlockEdit
    ) {
        return function ( props ) {
            const { attributes, setAttributes, isSelected, name } = props;
            console.log('attributes', attributes);
            return !attributes ? null : el(
                Fragment,
                {},
                el( BlockEdit, props ),
                isSelected && 
                el(
                    wp.blockEditor.InspectorControls,
                    {},
                    el( PanelBody, {title: 'Gating Settings', initialOpen: false},
                        el( ToggleControl, {
                            label: wp.i18n.__('Gate to signed in users', 'bb'),
                            checked: !!attributes.gateToSignedIn,
                            onChange: function( content ) {
                                setAttributes({ gateToSignedIn: !attributes.gateToSignedIn });
                            },
                        })
                    )
                )
            );
        };
    },
    'withInspectorControls' );
     
    addFilter(
        'editor.BlockEdit',
        'bb/with-inspector-controls',
        withInspectorControls
    );

    function bbApplyExtraClass(props, blockType, attributes) {
        const { gateToSignedIn, content } = attributes;
        let className = (props.className != undefined) ? props.className : '';
        if (typeof gateToSignedIn !== 'undefined' && gateToSignedIn) {
            className += className.includes('bb-gate-to-signed-in-section') ? '' : ' bb-gate-to-signed-in-section';
        }
        else {
            className = className.replace('bb-gate-to-signed-in-section', '');
        }
        props.className = className;
        return props;
    }
     
    addFilter(
        'blocks.getSaveContent.extraProps',
        'bb/apply-class',
        bbApplyExtraClass
    );

} )(
    window.wp.plugins,
    window.wp.editPost,
    window.wp.element,
    window.wp.components,
    window.wp.data,
    window.wp.compose,
    window.wp.hooks
);