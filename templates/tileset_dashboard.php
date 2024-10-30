<?php

use BreadButter_WP_Plugin\Base\BaseController;

function tileset_dashboard() {
    define("CONTENT_GATING", "content_gating");
    define("CONTACT_US", "contact_us");
    define("CONTENT_PREVIEW", "content_preview");
    define("POP_UP", "pop_up");


    $api_path = get_option('logon_api_path', '');
    $app_path_mapping = [
//    'https://api-devlab.breadbutter.io' => 'https://local.logon-dev.com:8080',
        'https://api-devlab.breadbutter.io' => 'https://app-devlab.breadbutter.io',
        'https://api-stable.breadbutter.io' => 'https://app-stable.breadbutter.io',
        'https://api.breadbutter.io' => 'https://app.breadbutter.io',
    ];
    $app_path = $app_path_mapping[$api_path];

    $app_id = esc_attr(get_option('logon_app_id', ''));

    function addSVG($type) {
        $svg = "";
        switch($type) {
            case 'google':
                $svg = "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48' class='abcRioButtonSvg'><g><path fill='#EA4335' d='M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z'></path><path fill='#4285F4' d='M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z'></path><path fill='#FBBC05' d='M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z'></path><path fill='#34A853' d='M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z'></path><path fill='none' d='M0 0h48v48H0z'></path></g></svg>";
                break;
            case 'microsoft':
                $svg = "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' viewBox='0 0 150 150' xml:space='preserve'><g><title>Layer 1</title> <path fill='#F0511C' d='m17.17982,74.67l55.94,0c0,-18.67 0,-37.31 0,-55.94l-55.94,0l0,55.94z' id='svg_1'></path> <path fill='#81CC2C' d='m79.18018,74.471802l55.93,0c0,-18.67 0,-37.31 0,-55.94l-55.93,0c0,18.65 0,37.29 0,55.94z' id='svg_2'></path> <path fill='#1EAEEF' d='m16.99964,134.32l55.94,0c0,-18.67 0,-37.31 0,-55.94l-55.94,0l0,55.94z' id='svg_3'></path> <path fill='#FBBC13' d='m79,134.401081l55.94,0l0,-55.93l-55.94,0c0,18.64 0,37.28 0,55.93z' id='svg_4'></path></g></svg>";
                break;
            case 'linkedin':
                $svg = "<svg version='1.1' id='Capa_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px'  viewBox='0 0 430.117 430.117' style='enable-background:new 0 0 430.117 430.117;'  xml:space='preserve'> <g> <path d='M430.117,261.543V420.56h-92.188V272.193c0-37.271-13.334-62.707-46.703-62.707 c-25.473,0-40.632,17.142-47.301,33.724c-2.432,5.928-3.058,14.179-3.058,22.477V420.56h-92.219c0,0,1.242-251.285,0-277.32h92.21 v39.309c-0.187,0.294-0.43,0.611-0.606,0.896h0.606v-0.896c12.251-18.869,34.13-45.824,83.102-45.824 C384.633,136.724,430.117,176.361,430.117,261.543z M52.183,9.558C20.635,9.558,0,30.251,0,57.463 c0,26.619,20.038,47.94,50.959,47.94h0.616c32.159,0,52.159-21.317,52.159-47.94C103.128,30.251,83.734,9.558,52.183,9.558z  M5.477,420.56h92.184v-277.32H5.477V420.56z' fill='#0866c1'/> </g> </svg>";
                break;
            case 'password':
                $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' class='idp-logo'><circle stroke='null' r='15.864' cy='16' cx='16'></circle><g data-name='Group 15579'><g data-name='Group 15578' stroke='null'><g data-name='Group 352' fill='#fff'><path d='M23.013 16.451v-.011l-.009-.031a7.563 7.563 0 00-1.972-3.041l-.046-2.644a5.151 5.151 0 00-10.3.176l.045 2.646a7.539 7.539 0 00-1.866 3.107l-.008.034v.009a7.467 7.467 0 1014.609 2.034 8.236 8.236 0 00-.453-2.279zm-10.052-5.59a2.866 2.866 0 115.73-.098l.02 1.146a7.52 7.52 0 00-5.73.1l-.02-1.147h0zm3.09 13.129a5.153 5.153 0 115.063-5.241 5.131 5.131 0 01-5.066 5.241h.003z' data-name='Path 284'></path><circle r='2.099' cy='18.713' cx='15.965' data-name='Ellipse 5'></circle></g></g></g></svg>";
                break;
            case 'magic_link':
                $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='34' height='34' viewBox='0 0 34 34' class='idp-logo'><defs><radialGradient id='BB-magiclink_icon_svg__a' cx='.5' cy='.5' r='.5' gradientUnits='objectBoundingBox'><stop offset='0' stop-color='#fdd03a'></stop><stop offset='.648' stop-color='#d98b7a'></stop><stop offset='1' stop-color='#9203fa'></stop></radialGradient></defs><g data-name='Group 19110' transform='translate(-3587 -693)'><circle data-name='Ellipse 1428' cx='17' cy='17' r='17' transform='translate(3587 693)' fill='#d0d8e2' opacity='.296'></circle><g data-name='Group 19096'><g data-name='Group 13895'><path data-name='Path 13311' d='M57.655 7.512a.686.686 0 00-.97 0l-2.91 2.91a.686.686 0 00.97.97l2.91-2.91a.686.686 0 000-.97zM51.934 5.1a.686.686 0 00-1.167.485l-.007 4.11a.69.69 0 001.379 0l-.007-4.115a.68.68 0 00-.2-.485zm8.138 8.138a.68.68 0 00-.485-.2l-4.115-.008a.689.689 0 100 1.379l4.115-.008a.686.686 0 00.485-1.167zm-14.056-6.21a.686.686 0 00-.97.97l2.91 2.91a.686.686 0 10.97-.97zm9.214 9.214a.686.686 0 00-.97.97l2.91 2.91a.686.686 0 00.97-.97zM47.365 12.6a.669.669 0 00-.477-.2l-4.115.008a.682.682 0 000 1.364l4.115.008a.681.681 0 00.477-1.175zm5.2 5.2a.681.681 0 00-1.175.477l.008 4.114a.682.682 0 001.364 0l.008-4.115a.669.669 0 00-.2-.477z' transform='translate(3555.894 693.102)' fill='url(#BB-magiclink_icon_svg__a)'></path></g><g data-name='Group 13896'><path data-name='Path 13312' d='M3607.245 704.98a.712.712 0 00-.97 0l-14.085 14.32a.713.713 0 000 .97l1.94 1.94a.713.713 0 00.97 0l14.084-14.32a.712.712 0 000-.97zm-.484 1.455l.97.97-3.88 3.88-.97-.97z'></path></g></g></g></svg>";
                break;
        }
        return $svg;
    }

    function addContent($type) {
        switch($type) {
            case CONTENT_GATING:
                echo "<div class='bb-dashboard-tile-content'>";
                echo "<div class='breadbutter-dashboard-icon breadbutter-dashboard-content-gate'></div>";
                echo "<span>Content Gating</span>";
                echo '<a class="bb-tileset-button" href="#content-gating"><span>Configure</span></a>';
                echo "</div>";
                break;
            case CONTACT_US:
                echo "<div class='bb-dashboard-tile-content'>";
                echo "<div class='breadbutter-dashboard-icon breadbutter-dashboard-contact-us'></div>";
                echo "<span>Contact Us</span>";
                echo '<a class="bb-tileset-button" href="#contactus"><span>Configure</span></a>';
                echo "</div>";
                break;
            case CONTENT_PREVIEW:
                echo "<div class='bb-dashboard-tile-content'>";
                echo "<div class='breadbutter-dashboard-icon breadbutter-dashboard-content-preview'></div>";
                echo "<span>Content Preview</span>";
                echo '<a class="bb-tileset-button" href="#content-preview"><span>Configure</span></a>';
                echo "</div>";
                break;
            case POP_UP:
                echo "<div class='bb-dashboard-tile-content'>";
                echo "<div class='breadbutter-dashboard-icon breadbutter-dashboard-pop-up'></div>";
                echo "<span>Pop Up</span>";
                echo '<a class="bb-tileset-button" href="#newsletter"><span>Configure</span></a>';
                echo "</div>";
                break;
        }
    }
    function addBox($type) {
        echo "<div class='bb-grid-item bb-dashboard-tile-content-box'>";
        addContent($type);
        echo "</div>";
    }
    function addLeftBox() {

        echo "<div class='breadbutter-inline-box breadbutter-tileset'>";

        echo "<div class='breadbutter-grid-tileset'>";
        addBox(POP_UP);
        addBox(CONTENT_GATING);
        addBox(CONTACT_US);
        addBox(CONTENT_PREVIEW);
        echo "</div>";

        echo "</div>";
    }
    function addRightBox($app_id, $app_path) {
        echo "<div class='breadbutter-inline-box breadbutter-tab breadbutter-tileset-side'>";
        echo "<div class='flex-row'>";
        echo "<div class='flex-col bb-dashboard-tile-content-box'>";
        echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
        echo "Opt-in Options";
        echo "</div>";
        echo "<div class='bb-dashboard-tile-content'>";

        echo "<div class='bb-tile-item' data-name='email'>
        <div class='bb-tile-item-show-box'></div>
        <div class='icon-svg'>". addSVG('password') ."</div>
        <span>Email + Password</span>
        </div>";

        echo " <div class='bb-tile-item' data-name='google'>
        <div class='bb-tile-item-show-box'></div>
        <div class='icon-svg'>". addSVG('google') ."</div>
        <span>Google</span>
        <a class='bb-tileset-button' target='_blank' href='$app_path/app/#/app-settings/$app_id?provider=google'><span>Set up</span></a>
        </div>";
        echo "<div class='bb-tile-item' data-name='linkedin'>
        <div class='bb-tile-item-show-box'></div>
        <div class='icon-svg'>". addSVG('linkedin') ."</div>
        <span>LinkedIn</span>
        <a class='bb-tileset-button' target='_blank' href='$app_path/app/#/app-settings/$app_id?provider=linkedin'><span>Set up</span></a>
        </div>";
        echo "<div class='bb-tile-item' data-name='microsoft'>
        <div class='bb-tile-item-show-box'></div>
        <div class='icon-svg'>". addSVG('microsoft') ."</div>
        <span>Microsoft</span>
        <a class='bb-tileset-button' target='_blank' href='$app_path/app/#/app-settings/$app_id?provider=microsoft'><span>Set up</span></a>
        </div>";


        echo "</div>";


        echo '<div class="bb-dashboard-tile-content-footer"><a class="bb-tileset-button" target="_blank" href="'.$app_path.'/app/#/app-settings/'.$app_id.'?ssosettings=true"><span>More Options</span></a></div>';
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    addLeftBox();
    addRightBox($app_id, $app_path);

//    echo "<div class='breadbutter-flex-1  breadbutter-inline-box'>";
//    echo "<div class='flex-row'>";
//    echo "<div class='flex-col'>";
//        echo "<div class='bb-dashboard-tile-content-box breadbutter-tab'>";
//            echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
//                echo "<div class='bb-dashboard-tile-content'>";
//                echo "testing";
//
//                echo "</div>";
//            echo "</div>";
//        echo "</div>";
//    echo "</div>";
//
//    echo "<div class='flex-col'>";
//    echo "<div class='bb-dashboard-tile-content-box '>";
//    echo "<div class='bb-dashboard-tile-title bb-inline-block'>";
//    echo "<div class='bb-dashboard-tile-content'>";
//    echo "testing";
//
//    echo "</div>";
//    echo "</div>";
//    echo "</div>";
//    echo "</div>";
//
//
//    echo "</div>";
//    echo "</div>";
}
?>

<?php tileset_dashboard() ?>
<script>
    window.breadbutterQueue = window.breadbutterQueue || [], window.injectBreadButter = function (e) { "undefined" != typeof BreadButter && BreadButter.init ? e() : window.breadbutterQueue.push(e) };
    injectBreadButter(function () {
        BreadButter.api.getProviders(false, (res)=> {
            // console.log(res);
            res.providers.forEach((provider) => {
                let idp = document.querySelector(`.bb-tile-item[data-name=${provider.idp}]`);
                // console.log(idp);
                if (idp) {
                    idp.classList.add('bb-tile-enabled');
                }
            });
            if (res.settings.password_settings) {
                let idp = document.querySelector(`.bb-tile-item[data-name=email]`);
                if (idp) {
                    idp.classList.add('bb-tile-enabled');
                }
            } else if (res.settings.magic_link_settings && res.settings.magic_link_settings.enabled && res.settings.magic_link_settings.registration_enabled) {
                let idp = document.querySelector(`.bb-tile-item[data-name=email]`);
                if (idp) {
                    idp.classList.add('bb-tile-enabled');
                    idp.querySelector('.icon-svg').innerHTML = `<?php echo addSVG('magic_link'); ?>`;
                    idp.querySelector('span').innerText = 'Passwordless';
                }
            }
        });
    });
</script>
