<?php

use WZ\ChildFree\Shortcodes\CandidateAmountRaised;

add_action('wp_ajax_candidate-pagination-load-posts', 'candidates_pagination_load_posts');
add_action('wp_ajax_nopriv_candidate-pagination-load-posts', 'candidates_pagination_load_posts');
function candidates_pagination_load_posts()
{
    if (!WC()->session->has_session()) {
        WC()->session->set_customer_session_cookie(true);
    }

    $page_container = '';
    $candidates_content = '
    <div class="e-con-inner">
		<div class="elementor-element elementor-element-7fa267f e-con-full e-grid e-con e-child" data-id="7fa267f" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;jet_parallax_layout_list&quot;:[],&quot;container_type&quot;:&quot;grid&quot;,&quot;grid_columns_grid&quot;:{&quot;unit&quot;:&quot;fr&quot;,&quot;size&quot;:1,&quot;sizes&quot;:[]},&quot;grid_rows_grid&quot;:{&quot;unit&quot;:&quot;fr&quot;,&quot;size&quot;:1,&quot;sizes&quot;:[]},&quot;grid_columns_grid_tablet&quot;:{&quot;unit&quot;:&quot;fr&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;grid_columns_grid_mobile&quot;:{&quot;unit&quot;:&quot;fr&quot;,&quot;size&quot;:1,&quot;sizes&quot;:[]},&quot;grid_rows_grid_tablet&quot;:{&quot;unit&quot;:&quot;fr&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;grid_rows_grid_mobile&quot;:{&quot;unit&quot;:&quot;fr&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;grid_auto_flow&quot;:&quot;row&quot;,&quot;grid_auto_flow_tablet&quot;:&quot;row&quot;,&quot;grid_auto_flow_mobile&quot;:&quot;row&quot;}">
				<div class="woocommerce elementor-element elementor-element-00fc411 grid-table '
                    . (isset($_POST['elementor_grid_style']) ? $_POST['elementor_grid_style'] : 'elementor-grid-4')
                    . ' elementor-grid-tablet-2 elementor-grid-mobile-1 elementor-widget elementor-widget-loop-grid" data-id="00fc411" data-element_type="widget" data-settings="{&quot;_skin&quot;:&quot;product&quot;,&quot;template_id&quot;:&quot;36663&quot;,&quot;pagination_type&quot;:&quot;load_more_on_click&quot;,&quot;columns&quot;:&quot;3&quot;,&quot;columns_tablet&quot;:&quot;2&quot;,&quot;columns_mobile&quot;:&quot;1&quot;,&quot;edit_handle_selector&quot;:&quot;[data-elementor-type=\&quot;loop-item\&quot;]&quot;,&quot;load_more_spinner&quot;:{&quot;value&quot;:&quot;fas fa-spinner&quot;,&quot;library&quot;:&quot;fa-solid&quot;},&quot;row_gap&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;row_gap_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;row_gap_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="loop-grid.product">
				<div class="elementor-widget-container">
                <div id="overlay-container" style="display: flex;">
                  <div id="overlay">
                    <div class="overlay-content">
                      <img src="/wp-content/uploads/2023/09/childfree_logo-1-1.svg" width="100%">
                      <img src="/wp-content/uploads/2023/04/candidates-loader.gif" width="80px">
                    </div>
                  </div>
                </div>
                				
				<link rel="stylesheet" href="' . get_site_url() . '/wp-content/plugins/elementor-pro/assets/css/widget-loop-builder.min.css">
				<div class="elementor-loop-container elementor-grid">
				<style id="loop-36663">

                .select-btn{font-family: "Be Vietnam Pro", Sans-serif;
                    font-size: 18px;
                    font-weight: 500;
                    line-height: 27px;
                    fill: #FFFFFF;
                    color: #FFFFFF;
                    background-color: var(--e-global-color-primary);
                    border-style: solid;
                    border-width: 1px 1px 1px 1px;
                    border-color: var(--e-global-color-primary);
                    border-radius: 12px 12px 12px 12px;
                    padding: 10px 24px 10px 24px;
                    text-align:center;
                    cursor: pointer;
                    transition:0.2s;
                }
                .select-btn:hover{
                    transform: scale(1.05,1.05);
                    transition:0.2s;
                }
				.elementor-36663 .elementor-element.elementor-element-7220f95{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--gap:12px 0px;--background-transition:0.3s;--border-radius:12px 12px 12px 12px;--margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;--padding-block-start:0px;--padding-block-end:24px;--padding-inline-start:0px;--padding-inline-end:0px;}.elementor-36663 .elementor-element.elementor-element-7220f95:not(.elementor-motion-effects-element-type-background), .elementor-36663 .elementor-element.elementor-element-7220f95 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-color:#FFFFFF;height:100%;}.elementor-36663 .elementor-element.elementor-element-7220f95, .elementor-36663 .elementor-element.elementor-element-7220f95::before{--border-transition:0.3s;}
				.elementor-36663 .elementor-element.elementor-element-7cdb593 > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;border-radius:12px 12px 0px 0px;}
				.elementor-36663 .elementor-element.elementor-element-7cdb593{width:100%;max-width:100%;}
				.elementor-36663 .elementor-element.elementor-element-7cdb593.elementor-element{--align-self:stretch;}
/*				.elementor-36663 .elementor-element.elementor-element-af71874{
                    height:100%; justify-content: space-between; --display:flex;--flex-direction:column;--container-widget-width:100%;
                    --container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--gap:16px 0px;
                    --background-transition:0.3s;--margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;
                    --padding-block-start:0px;--padding-block-end:0px;--padding-inline-start:12px;--padding-inline-end:12px;}*/
				.elementor-36663 .elementor-element.elementor-element-cb7f323{--display:flex;--flex-direction:row;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--justify-content:space-between;--align-items:center;--gap:0px 0px;--flex-wrap:wrap;--background-transition:0.3s;--margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;--padding-block-start:0px;--padding-block-end:0px;--padding-inline-start:0px;--padding-inline-end:0px;}
				.elementor-36663 .elementor-element.elementor-element-f9b4d53 .elementor-heading-title{color:var( --e-global-color-primary );font-family:"Be Vietnam Pro", Sans-serif;font-size:20px;font-weight:700;line-height:20px;}
				.elementor-36663 .elementor-element.elementor-element-822ce76 .elementor-button .elementor-align-icon-right{margin-left:0px;}
				.elementor-36663 .elementor-element.elementor-element-822ce76 .elementor-button .elementor-align-icon-left{margin-right:0px;}
				.elementor-36663 .elementor-element.elementor-element-822ce76 .elementor-button{
                    font-family:"Be Vietnam Pro", Sans-serif;font-size:14px;font-weight:400;line-height:18px;fill:#478BF2;color:#478BF2;background-color:#EDF3FE;
                    border-style:none;border-radius:12px 12px 12px 12px;padding:4px 8px 4px 8px;
				}
				.elementor-36663 .elementor-element.elementor-element-b63b759{
				    --display:flex;--flex-direction:row;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );
				    --container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;
				    --justify-content:flex-start;--align-items:center;--gap:0px 8px;--flex-wrap:wrap;--background-transition:0.3s;
				    --margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;
				    --padding-block-start:0px;--padding-block-end:0px;--padding-inline-start:0px;--padding-inline-end:0px;
                }
                .elementor-36663 .elementor-element.elementor-element-1575f24 .elementor-button .elementor-align-icon-right{margin-left:2px;}
                .elementor-36663 .elementor-element.elementor-element-1575f24 .elementor-button .elementor-align-icon-left{margin-right:2px;}
                .elementor-36663 .elementor-element.elementor-element-1575f24 .elementor-button{font-family:"Be Vietnam Pro", Sans-serif;font-size:12px;font-weight:400;fill:#478BF2;color:#478BF2;background-color:#EDF3FE;border-style:none;border-radius:12px 12px 12px 12px;padding:4px 6px 4px 6px;}.elementor-36663 .elementor-element.elementor-element-958bb66 .elementor-button .elementor-align-icon-right{margin-left:2px;}.elementor-36663 .elementor-element.elementor-element-958bb66 .elementor-button .elementor-align-icon-left{margin-right:2px;}.elementor-36663 .elementor-element.elementor-element-958bb66 .elementor-button{font-family:"Be Vietnam Pro", Sans-serif;font-size:12px;font-weight:400;fill:var( --e-global-color-secondary );color:var( --e-global-color-secondary );background-color:#EBEBEB80;border-style:none;border-radius:12px 12px 12px 12px;padding:4px 6px 4px 6px;}.elementor-36663 .elementor-element.elementor-element-3d71b57 .elementor-button .elementor-align-icon-right{margin-left:2px;}.elementor-36663 .elementor-element.elementor-element-3d71b57 .elementor-button .elementor-align-icon-left{margin-right:2px;}.elementor-36663 .elementor-element.elementor-element-3d71b57 .elementor-button{font-family:"Be Vietnam Pro", Sans-serif;font-size:12px;font-weight:400;fill:var( --e-global-color-secondary );color:var( --e-global-color-secondary );background-color:#EBEBEB80;border-style:none;border-radius:12px 12px 12px 12px;padding:4px 6px 4px 6px;}.elementor-36663 .elementor-element.elementor-element-5d72a9e{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--gap:8px 0px;--background-transition:0.3s;--margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;--padding-block-start:0px;--padding-block-end:0px;--padding-inline-start:0px;--padding-inline-end:0px;}.elementor-36663 .elementor-element.elementor-element-a5bfd78 .elementor-heading-title{color:var( --e-global-color-text );font-family:"Be Vietnam Pro", Sans-serif;font-size:14px;font-weight:400;line-height:21px;}.elementor-36663 .elementor-element.elementor-element-a643f6b .elementor-progress-wrapper{background-color:#EBEBEB;border-radius:2px;overflow:hidden;}.elementor-36663 .elementor-element.elementor-element-a643f6b 
                .elementor-progress-bar{background-color:#143A62;height:6px;line-height:6px;}.elementor-36663 .elementor-element.elementor-element-ebdea9d{--display:flex;--flex-direction:row;--container-widget-width:initial;--container-widget-height:100%;--container-widget-flex-grow:1;--container-widget-align-self:stretch;--justify-content:space-between;--background-transition:0.3s;--margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;--padding-block-start:0px;--padding-block-end:0px;--padding-inline-start:0px;--padding-inline-end:0px;}.elementor-36663 .elementor-element.elementor-element-effe374 .elementor-heading-title{color:var( --e-global-color-text );font-family:"Be Vietnam Pro", Sans-serif;font-size:16px;font-weight:600;line-height:24px;}.elementor-36663 .elementor-element.elementor-element-3f84327 .elementor-heading-title{color:var( --e-global-color-secondary );font-family:"Be Vietnam Pro", Sans-serif;font-size:16px;font-weight:400;line-height:24px;}.elementor-36663 .elementor-element.elementor-element-567c4b0{--display:flex;--flex-direction:column;--container-widget-width:100%;--container-widget-height:initial;--container-widget-flex-grow:0;--container-widget-align-self:initial;--gap:8px 0px;--background-transition:0.3s;--margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;--padding-block-start:0px;--padding-block-end:0px;--padding-inline-start:0px;--padding-inline-end:0px;}.elementor-36663 .elementor-element.elementor-element-e15b5f2 .elementor-button{font-family:"Be Vietnam Pro", Sans-serif;font-size:18px;font-weight:500;line-height:27px;fill:#FFFFFF;color:#FFFFFF;background-color:var( --e-global-color-primary );border-style:solid;border-width:1px 1px 1px 1px;border-color:var( --e-global-color-primary );border-radius:12px 12px 12px 12px;padding:10px 24px 10px 24px;}.elementor-36663 .elementor-element.elementor-element-ef4ae14 .elementor-button{font-family:"Be Vietnam Pro", Sans-serif;font-size:18px;font-weight:500;line-height:27px;fill:var( --e-global-color-primary );color:var( --e-global-color-primary );background-color:#143A6200;border-style:solid;border-width:1px 1px 1px 1px;border-color:var( --e-global-color-primary );border-radius:12px 12px 12px 12px;padding:10px 24px 10px 24px;}
				.elementor-36663 .elementor-element.elementor-element-7cdb593 img{
				    width:325px;height:300px;object-fit:cover;object-position:center center;border-radius:12px 12px 0px 0px;
                }
                @media(max-width:1024px){
                .elementor-36663 .elementor-element.elementor-element-7cdb593 img{object-fit:cover;}
                .elementor-36663 .elementor-element.elementor-element-af71874{
                    --gap:8px 0px;--margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;--padding-block-start:0px;
                    --padding-block-end:0px;--padding-inline-start:6px;--padding-inline-end:6px;}
                .elementor-36663 .elementor-element.elementor-element-cb7f323{--justify-content:space-between;--margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;--padding-block-start:0px;--padding-block-end:0px;--padding-inline-start:0px;--padding-inline-end:0px;}.elementor-36663 .elementor-element.elementor-element-f9b4d53 .elementor-heading-title{font-size:20px;line-height:13px;}
                .elementor-36663 .elementor-element.elementor-element-f9b4d53 > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}
				.elementor-36663 .elementor-element.elementor-element-822ce76 .elementor-button{font-size:6px;line-height:14px;border-radius:50px 50px 50px 50px;padding:4px 8px 4px 8px;}.elementor-36663 .elementor-element.elementor-element-822ce76 > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}.elementor-36663 .elementor-element.elementor-element-b63b759{--margin-block-start:0px;--margin-block-end:0px;--margin-inline-start:0px;--margin-inline-end:0px;--padding-block-start:0px;--padding-block-end:0px;--padding-inline-start:0px;--padding-inline-end:0px;}.elementor-36663 .elementor-element.elementor-element-1575f24 .elementor-button{font-size:12px;line-height:14px;border-radius:6px 6px 6px 6px;padding:4px 6px 4px 6px;}.elementor-36663 .elementor-element.elementor-element-1575f24 > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}.elementor-36663 .elementor-element.elementor-element-958bb66 .elementor-button{font-size:12px;line-height:14px;border-radius:6px 6px 6px 6px;padding:4px 6px 4px 6px;}.elementor-36663 .elementor-element.elementor-element-958bb66 > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}.elementor-36663 .elementor-element.elementor-element-3d71b57 .elementor-button{font-size:12px;line-height:14px;border-radius:6px 6px 6px 6px;padding:4px 6px 4px 6px;}.elementor-36663 .elementor-element.elementor-element-3d71b57 > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}}@media(min-width:768px){.elementor-36663 .elementor-element.elementor-element-7220f95{--width:100%;}}@media(max-width:767px){.elementor-36663 .elementor-element.elementor-element-7220f95{--gap:8px 0px;}
				.elementor-36663 .elementor-element.elementor-element-7cdb593 img{width:350px;object-fit:cover;border-radius:12px 12px 0px 0px;}
				.elementor-36663 .elementor-element.elementor-element-cb7f323{--align-items:center;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--gap:0px 0px;}.elementor-36663 .elementor-element.elementor-element-f9b4d53 .elementor-heading-title{font-size:9px;}.elementor-36663 .elementor-element.elementor-element-822ce76 > .elementor-widget-container{margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}.elementor-36663 .elementor-element.elementor-element-b63b759{--justify-content:flex-start;--align-items:center;--container-widget-width:calc( ( 1 - var( --container-widget-flex-grow ) ) * 100% );--gap:0px 3px;--flex-wrap:wrap;}.elementor-36663 .elementor-element.elementor-element-b63b759.e-con{--align-self:center;}.elementor-36663 .elementor-element.elementor-element-1575f24 .elementor-button{font-size:9px;line-height:14px;}.elementor-36663 .elementor-element.elementor-element-1575f24{width:auto;max-width:auto;}.elementor-36663 .elementor-element.elementor-element-958bb66 .elementor-button{font-size:9px;}.elementor-36663 .elementor-element.elementor-element-958bb66{width:auto;max-width:auto;}.elementor-36663 .elementor-element.elementor-element-3d71b57 .elementor-button{font-size:9px;}.elementor-36663 .elementor-element.elementor-element-3d71b57{width:auto;max-width:auto;}.elementor-36663 .elementor-element.elementor-element-a5bfd78 .elementor-heading-title{font-size:8px;}.elementor-36663 .elementor-element.elementor-element-effe374 .elementor-heading-title{font-size:7px;}.elementor-36663 .elementor-element.elementor-element-3f84327 .elementor-heading-title{font-size:7px;}.elementor-36663 .elementor-element.elementor-element-567c4b0{--gap:6px 0px;}.elementor-36663 .elementor-element.elementor-element-e15b5f2 .elementor-button{font-size:8px;border-radius:6px 6px 6px 6px;padding:5px 12px 5px 12px;}.elementor-36663 .elementor-element.elementor-element-ef4ae14 .elementor-button{font-size:8px;border-radius:6px 6px 6px 6px;padding:5px 12px 5px 12px;}}/* Start custom CSS for button, class: .elementor-element-e15b5f2 */.card-btn .elementor-widget-container div a span span:before {
                    content: "";
                    background-image: url("/wp-content/uploads/2023/09/Component-1.svg");
                    width: 24px;
                    height: 24px;
                    display: inline-block;
                    position: relative;
                    top: 5px;
                    right: 8px
                }
                
                @media screen and (max-width: 600px) {
                    .card-btn .elementor-widget-container div a span span:before {
                        content: "";
                        background-image: url("/wp-content/uploads/2023/09/donate-mob.svg") !important;
                        background-repeat: no-repeat;
                        width: 12px !important;
                        height: 12px !important;
                        top: 3px !important;
                        right: 5px !important;
                    }
                    a.elementor-button.elementor-button-link.elementor-size-sm.elementor-animation-shrink {
                    padding: 5px 12px;
                    }
                }/* End custom CSS */
                /* Start custom CSS for container, class: .elementor-element-7220f95 */
                .card {
                    box-shadow: 0px 8px 17px 0px rgba(132, 152, 174, 0.10), 0px 31px 31px 0px rgba(132, 152, 174, 0.09), 0px 71px 42px 0px rgba(132, 152, 174, 0.05), 0px 126px 50px 0px rgba(132, 152, 174, 0.01), 0px 196px 55px 0px rgba(132, 152, 174, 0.00);
                    max-width: 422px !important;
                    min-width: 163.5px;
                }
                
                @media (max-width: 600px){
                    .card-title, h2.elementor-heading-title.elementor-size-default {
                    font-size: 14px !important;
                }
                    .elementor-91 .elementor-element.elementor-element-c036a9e .elementor-button, .elementor-91 .elementor-element.elementor-element-767092c .elementor-button, .elementor-91 .elementor-element.elementor-element-fa26caf .elementor-button, .elementor-91 .elementor-element.elementor-element-d421ffb .elementor-button {
                        font-size: 8px;
                        line-height: 12px !important;
                    }
                    
                    .elementor-element.elementor-element-c036a9e.elementor-widget.elementor-widget-button {
                    margin-top: -6px;
                    }
                    
                    a.elementor-button.elementor-button-link.elementor-size-sm.elementor-animation-shrink {
                    padding: 5px 12px;
                    font-size: 10px;
                    line-height: 12px;
                    border-radius: 6px;
                    }
                    
                    .elementor-91 .elementor-element.elementor-element-b8e4381 {
                        gap: 8px !important;
                    }
                    
                    .elementor-91 .elementor-element.elementor-element-1d4d3ef .elementor-heading-title {
                        font-size: 9px !important;
                    }
                    
                    .elementor-element.elementor-element-748cbda.e-con-full.e-flex.e-con.e-child {
                        margin-top: -8px;
                    }
                    
                    .elementor-91 .elementor-element.elementor-element-e4ed70d img {
                        height: 160px !important;
                    }
                    
                    .elementor-element.elementor-element-71e28f9.e-con-full.e-flex.e-con.e-child {
                        gap: 6px !important;
                    }
                }
                
                .button-disabled {
                    pointer-events: none;
                    cursor: not-allowed;
                }
                
                .load_more/**/ {
                    font-family: "Be Vietnam Pro";
                    font-size: 20px;
                    font-weight: 500;
                    line-height: 26px;
                    padding: 10px 24px 10px 24px;
                    color: #143A62;
                    border: 2px solid #143A62;
                    border-radius: 12px;
                }

                button.load_more.load-btn-1:hover {
                    background: #143A62;
                    color: white;
                }

                .cvf-universal-pagination {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-top: 20px;
                }
                .cvf-universal-pagination .active {
                    display: flex;
                    list-style: none;
                    flex-direction: column;
                    align-items: center;
                }
                /* Donation Amount Popup */
                #elementor-popup-modal-58203 {display:none !important;}
                /* Fund All Popup */
                #elementor-popup-modal-58108 {display:none !important;}
                /* End custom CSS */
        </style>
    ';
    // 40 predefined specific candidates that will always appear when candidates page is initially opened.
    $specific_candidates = array(22499, 26229,26008,22633, 26153, 26231, 26207, 22517, 22547, 22611, 22614, 22770, 22697, 22698, 25997, 26087, 26233, 26663, 22713, 22679, 25605, 26598, 25757, 22694, 26151, 25974, 22702, 22665, 22668, 25432, 25674, 25729, 25861, 25995, 26044, 25843, 26159, 26557, 25837, 22764);
    $total_product_ids = array();
    // Add specific candidates to the array
    $total_product_ids = array_merge($total_product_ids, $specific_candidates);

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'fields' => 'ids',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();

            // Skip specific candidate IDs
            if (in_array($product_id, $specific_candidates)) {
                continue;
            }

            // Add remaining product IDs to the array
            $total_product_ids[] = $product_id;
        }
    }
    // Reset the post data
    wp_reset_postdata();

    if (isset($_POST['page'])) {
        $meta_key = '';
        if (isset($_POST['order_by'])) {
            $corder_by = sanitize_text_field($_POST['order_by']);
            $corder = sanitize_text_field($_POST['order']);
            if ($corder_by == "meta_value_num") {
                $meta_key = '_age';
            }
        } else {
            $corder_by = 'sort'; // this is the default sort order (no-order) when the candidates page is initially loaded in browser.
            $corder = 'DESC';
        }

        if (isset($_POST['per_page'])) {
            $per_page = sanitize_text_field($_POST['per_page']);
        } else {
            $per_page = 24;
        }

        if (isset($_POST['search_title'])) {
            $search_title = strtolower(sanitize_text_field($_POST['search_title']));
        } else {
            $search_title = '';
        }

        if (isset($_POST['cmax_price'])) {
            $cmin_price = sanitize_text_field($_POST['cmin_price']);
            $cmax_price = sanitize_text_field($_POST['cmax_price']);
            if ($cmax_price == 10000) {
                $cmax_price = 9999;
            }
        } else {
            $cmax_price = 9999;
            $cmin_price = 100;
        }

        if (isset($_POST['cmax_age'])) {
            $cmax_age = sanitize_text_field($_POST['cmax_age']);
//            $cmax_age = 70;
            $cmin_age = sanitize_text_field($_POST['cmin_age']);
        } else {
            $cmax_age = 40;
            $cmin_age = 18;
        }

        $page = sanitize_text_field($_POST['page']);
        $cur_page = $page;
        $previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;

        $zip_code = sanitize_text_field($_POST['czip_code']);

        $search_gender = sanitize_text_field($_POST['search_gender']);

        $zip_code_array = !empty($zip_code) ? array('key' => '_location', 'value' => $zip_code, 'compare' => '=') : array();

        $sex_array = !empty($search_gender) ? array('key' => '_sex', 'value' => $search_gender, 'compare' => '=') : array();

        $clat = $_POST['clat'];
        $clng = $_POST['clng'];
        $cdestination = $_POST['cdestination'];
//        echo $cdestination;
        //If the user has selected the Radius value.
        if (isset($cdestination) && isset($_POST['clat']) && isset($_POST['clng'])) {
//            echo "In Destination array";
//            echo "<br>Page is: ".$page;
//            echo "<br>Per Page is: ".$per_page;
            $p_id = search_posts_within_radius($clat, $clng, $cdestination);
            $c_Candidates = count($p_id);
            if ($c_Candidates >= 1) {
                $candidates_arg = array(
                    'post_type' => 'product',
                    'posts_per_page' => $page * $per_page,
                    'post__in' => $p_id,
                    'meta_key' => $meta_key,
                    'orderby' => $corder_by,
                    'order' => $corder,
                    'post_status' => 'publish',
                    'starts_with' => $search_title,
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => '_amount_raised',
                            'value' => array($cmin_price, $cmax_price),
                            'compare' => 'BETWEEN',
                            'type' => 'NUMERIC'
                        ),
                        $sex_array,
                        array(
                            'key' => '_age',
                            'value' => array($cmin_age, $cmax_age),
                            'compare' => 'BETWEEN',
                            'type' => 'NUMERIC'
                        )
                    ),

                    // 'geo_query' => array(
                    //     'lat_field' => '_latitude',  // this is the name of the meta field storing latitude
                    //     'lng_field' => '_longitude', // this is the name of the meta field storing longitude
                    //     'latitude'  => $clat,    // this is the latitude of the point we are getting distance from
                    //     'longitude' => $clng,
                    //     'distance'  => $cdestination+1,           // this is the maximum distance to search
                    //     'units'     => 'miles'       // this supports options: miles, mi, kilometers, km
                    // )
                );
            }
        } else if (isset($corder_by) && $corder_by == "sort") {
            $candidates_arg = array(
                'posts_per_page' => $page * $per_page,
                'meta_key' => $meta_key,
                'post__in' => $total_product_ids,
                'orderby' => 'post__in',
                'post_type' => 'product',
                'post_status' => 'publish',
                'starts_with' => $search_title,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => '_amount_raised',
                        'value' => array($cmin_price, $cmax_price),
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC'
                    ),
                    $zip_code_array,
                    $sex_array,
                    array(
                        'key' => '_age',
                        'value' => array($cmin_age, $cmax_age),
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC'
                    )
                )
            );
        } else {
            $candidates_arg = array(
                'posts_per_page' => (int)$page * (int)$per_page,
                'meta_key' => $meta_key,
                'orderby' => $corder_by,
                'order' => $corder,
                'post_type' => 'product',
                'post_status' => 'publish',
                'starts_with' => $search_title,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => '_amount_raised',
                        'value' => array($cmin_price, $cmax_price),
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC'
                    ),
                    $zip_code_array,
                    $sex_array,
                    array(
                        'key' => '_age',
                        'value' => array($cmin_age, $cmax_age),
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC'
                    )
                )
            );
        }

        $candidates = new WP_Query($candidates_arg);

        $total_candidates = $candidates->found_posts;
        $max_num_pages = ceil($total_candidates / $per_page);
        if ($candidates->have_posts()) {

            while ($candidates->have_posts()) {
                $candidates->the_post();

                $candidate_Id = get_the_ID();

                if ($candidate_Id) {
                    $thumbnail_id = get_post_thumbnail_id($candidate_Id);
                }
                else {
                    echo "<br>No candidate ID found for candidate ID: $candidate_Id";
                }
                if ($thumbnail_id) {
                    $image = wp_get_attachment_image_src($thumbnail_id, 'full');
                    if ($image) {
                        $image_url = $image[0];
                    }
                }
                else {
//                    $image_url = 'https://staging.childfreebc.com/wp-content/uploads/woocommerce-placeholder.png';
                    $image_url = 'https://childfreebc.com/wp-content/uploads/woocommerce-placeholder.png';
                }

                // call shortcode for candidate amount raised to update the raised amount in DB
                do_shortcode('[candidate_amount_raised]');
                $amount_raised = get_post_meta($candidate_Id, '_amount_raised', true);

                $goal_amount = get_post_meta($candidate_Id, '_goal', true);
                if ($amount_raised > $goal_amount) {
                    $amount_raised = $goal_amount;
                }

                $location = get_post_meta($candidate_Id, '_location', true);

                global $wpdb;
                $table_name = $wpdb->prefix . 'jet_cct_zipcodes';
                $query = $wpdb->prepare("SELECT * FROM $table_name WHERE zipcode = %s", $location);
                $row = $wpdb->get_row($query);
                $map_marker = sprintf('%s, %s', $row->city, $row->state_code);
                if (isset($clat) && isset($clng)) {
                    $distance = haversine_distance($clat, $clng, get_post_meta($candidate_Id, '_latitude', true), get_post_meta($candidate_Id, '_longitude', true), "M");
                    $distance = round($distance, 1);
                    $map_marker = sprintf('%s, %s - %s miles away', $row->city, $row->state_code, $distance);
                }
                $distance = '<div class="distance"><span>' . $map_marker . '</span></div>';

//                global $wpdb;
                // Set default variables
//                $msg = '';

                if (isset($_POST['referal_page']) && $_POST['referal_page'] == "dashboard_physicians"){
                    $buttons = '<div class="candidate-select__to-physician elementor-element elementor-element-ef4ae14 elementor-align-justify elementor-widget elementor-widget-button" 
                        data-id="'.$candidate_Id.'" data-element_type="widget" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                          <div class="elementor-button-wrapper">
                        
                              <span class="elementor-button-content-wrapper select-btn">
                                <span class="elementor-button-text">Select</span>
                              </span>
                          
                          </div>
                        </div>
                        </div>';
                } else {
                    $buttons = '<div class="elementor-element elementor-element-e15b5f2 elementor-align-justify card-btn elementor-widget elementor-widget-button" 
                      data-id="e15b5f2" data-element_type="widget" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                          <div class="elementor-button-wrapper">
                            <a class="elementor-button elementor-button-link elementor-size-sm elementor-animation-shrink" 
                            href="#donation-popup">
                              <span class="elementor-button-content-wrapper">
                                <span id="'. $candidate_Id .'" class="elementor-button-text">Donate</span>
                              </span>
                            </a>
                          </div>
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-ef4ae14 elementor-align-justify elementor-widget elementor-widget-button" 
                      data-id="ef4ae14" data-element_type="widget" data-widget_type="button.default">
                        <div class="elementor-widget-container">
                          <div class="elementor-button-wrapper">
                            <a class="elementor-button elementor-button-link elementor-size-sm elementor-animation-shrink" 
                            href="' . get_permalink($candidate_Id) . '" >
                              <span class="elementor-button-content-wrapper">
                                <span class="elementor-button-text">Candidate Profile</span>
                              </span>
                            </a>
                          </div>
                        </div>
                      </div>';
                }


                $candidates_content .= '                
                <div data-elementor-type="loop-item" data-elementor-id="36663" class="elementor elementor-36663 e-loop-item e-loop-item-'
                                    . $candidate_Id .' post-'. $candidate_Id .' product type-product status-publish has-post-thumbnail 
                                    product_cat-uncategorized  instock shipping-taxable product-type-simple" 
                                    data-elementor-post-type="elementor_library" data-custom-edit-handle="1">
                  <div class="elementor-element elementor-element-7220f95 e-con-full card e-flex e-con e-parent" data-id="7220f95" 
                  data-element_type="container" id="'. $candidate_Id .'" 
                  data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;content_width&quot;:&quot;full&quot;,&quot;jet_parallax_layout_list&quot;:[],&quot;container_type&quot;:&quot;flex&quot;}" data-core-v316-plus="true">
                    <div class="elementor-element elementor-element-7cdb593 elementor-widget__width-inherit card-img elementor-widget 
                    elementor-widget-image" data-id="7cdb593" data-element_type="widget" data-widget_type="image.default">
                      
                      <div class="elementor-widget-container">
                        <a href="' . get_permalink($candidate_Id) . '">
                          <img id="card-candidate-image" decoding="async" width="161" height="300" 
                          src="' . $image_url . '" 
                          class="attachment-medium size-medium wp-image-'. $thumbnail_id .'" alt="" 
                          srcset="' . $image_url . ' 161w, 
                          ' . $image_url . ' 549w, 
                          ' . $image_url . ' 768w, 
                          ' . $image_url . ' 823w, 
                          ' . $image_url . ' 600w, 
                          ' . $image_url . ' 960w" 
                          sizes="(max-width: 161px) 100vw, 161px">
                        </a>
                      </div>
                    </div>
                    <div class="elementor-element elementor-element-af71874 e-con-full e-flex e-con e-child" 
                    data-id="af71874" data-element_type="container" 
                    data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;jet_parallax_layout_list&quot;:[],&quot;container_type&quot;:&quot;flex&quot;}">
                      <div class="elementor-element elementor-element-cb7f323 e-con-full e-flex e-con e-child" 
                      data-id="cb7f323" data-element_type="container" 
                      data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;jet_parallax_layout_list&quot;:[],&quot;container_type&quot;:&quot;flex&quot;}">
                        <div class="elementor-element elementor-element-f9b4d53 card-title elementor-widget elementor-widget-heading" 
                        data-id="f9b4d53" data-element_type="widget" data-widget_type="heading.default">
                          <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default">
                              <a id="card-candidate-name" href="' . get_permalink($candidate_Id) . '" target="_blank">'. get_the_title() . '</a>
                            </h2>
                          </div>
                        </div>
                        <div class="elementor-element elementor-element-822ce76 button-disabled elementor-widget elementor-widget-button" 
                        data-id="822ce76" data-element_type="widget" data-widget_type="button.default">
                          <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                              <a class="elementor-button elementor-size-sm" role="button" 
                              style="'. (get_post_meta($candidate_Id, '_sex', true) == "female" ? "line-height:18px;color:#9d2d9d;background-color:#fbedfe;" : "") . '">
                                <span class="elementor-button-content-wrapper">
                                  <span id="card-candidate-seeking" class="elementor-button-text">'
                                    . (get_post_meta($candidate_Id, "_sex", true) == "male" ? "Seeking Vasectomy" : "Seeking Tubal Ligation")
                                    . '</span>
                                </span>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-b63b759 e-con-full e-flex e-con e-child" 
                      data-id="b63b759" data-element_type="container" 
                      data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;jet_parallax_layout_list&quot;:[],&quot;container_type&quot;:&quot;flex&quot;}">
                        <div class="elementor-element elementor-element-1575f24 elementor-widget-mobile__width-auto button-disabled elementor-widget elementor-widget-button" data-id="1575f24" data-element_type="widget" data-widget_type="button.default">
                          <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                              <a class="elementor-button elementor-size-sm" role="button" 
                              style="'. (get_post_meta($candidate_Id, '_sex', true) == "female" ? "line-height:18px;color:#9d2d9d;background-color:#fbedfe;" : "") . '">
                                <span class="elementor-button-content-wrapper">
                                  <span class="elementor-button-icon elementor-align-icon-left">
                                    <i class="fas '
                                            . (get_post_meta($candidate_Id, '_sex', true) == "male" ? "fa-mars" : "fa-venus") . '">
                                    </i>
                                  </span>
                                  <span id="card-candidate-sex" class="elementor-button-text">'
                                    . ucfirst(get_post_meta($candidate_Id, '_sex', true))
                                    . '</span>
                                </span>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="elementor-element elementor-element-958bb66 elementor-widget-mobile__width-auto button-disabled elementor-widget elementor-widget-button" data-id="958bb66" data-element_type="widget" data-widget_type="button.default">
                          <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                              <a class="elementor-button elementor-size-sm" role="button">
                                <span class="elementor-button-content-wrapper">
                                  <span class="elementor-button-icon elementor-align-icon-left">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                      <g id="Component 1" clip-path="url(#clip0_424_12987)">
                                        <path id="Vector" d="M13.5 2.5H3.5C3.22386 2.5 3 2.72386 3 3V13C3 13.2761 3.22386 13.5 3.5 13.5H13.5C13.7761 13.5 14 13.2761 14 13V3C14 2.72386 13.7761 2.5 13.5 2.5Z" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path id="Vector_2" d="M11.5 1.5V3.5" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path id="Vector_3" d="M5.5 1.5V3.5" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path id="Vector_4" d="M3 5.5H14" stroke="#143A62" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                      </g>
                                      <defs>
                                        <clipPath id="clip0_424_12987">
                                          <rect width="16" height="16" fill="white" transform="translate(0.5)">
                                          </rect>
                                        </clipPath>
                                      </defs>
                                    </svg>
                                  </span>
                                  <span class="elementor-button-text"><span id="card-candidate-age">' . get_post_meta($candidate_Id, '_age', true) . '</span> years old</span>
                                </span>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="elementor-element elementor-element-3d71b57 elementor-widget-mobile__width-auto button-disabled elementor-widget elementor-widget-button" 
                        data-id="3d71b57" data-element_type="widget" data-widget_type="button.default">
                          <div class="elementor-widget-container">
                            <div class="elementor-button-wrapper">
                              <a class="elementor-button elementor-size-sm" role="button">
                                <span class="elementor-button-content-wrapper">
                                  <span class="elementor-button-icon elementor-align-icon-left">
                                    <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                                  </span>
                                  <span class="elementor-button-text">
                                    <div class="distance">
                                      <span id="card-candidate-location">' . $distance . '</span>
                                    </div>
                                  </span>
                                </span>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-5d72a9e e-con-full e-flex e-con e-child" 
                      data-id="5d72a9e" data-element_type="container" 
                      data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;jet_parallax_layout_list&quot;:[],&quot;container_type&quot;:&quot;flex&quot;}">
                        <div class="elementor-element elementor-element-a5bfd78 elementor-widget elementor-widget-heading" 
                        data-id="a5bfd78" data-element_type="widget" data-widget_type="heading.default">
                          <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default">Progress Towards Goal</h2>
                          </div>
                        </div>
                        <div class="elementor-element elementor-element-a643f6b elementor-widget elementor-widget-progress" 
                        data-id="a643f6b" data-element_type="widget" data-widget_type="progress.default">
                          <div class="elementor-widget-container">
                            <div class="elementor-progress-wrapper" role="progressbar" aria-valuemin="0" aria-valuemax="100" 
                                aria-valuenow="1">
                              <div class="elementor-progress-bar" title="'.do_shortcode('[candidate_progress]').'%" 
                                    data-max="1" style="width: '.do_shortcode('[candidate_progress]').'%;">
                                <span class="elementor-progress-text">
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="elementor-element elementor-element-ebdea9d e-con-full e-flex e-con e-child" 
                        data-id="ebdea9d" data-element_type="container" 
                        data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;jet_parallax_layout_list&quot;:[],&quot;container_type&quot;:&quot;flex&quot;}">
                          <div class="elementor-element elementor-element-effe374 elementor-widget elementor-widget-heading" 
                          data-id="effe374" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                              <h2 class="elementor-heading-title elementor-size-default">
                                <span id="card-candidate-amount-raised">' . wc_price($amount_raised) . '</span> raised
                              </h2>
                            </div>
                          </div>
                          <div class="elementor-element elementor-element-3f84327 elementor-widget elementor-widget-heading" 
                          data-id="3f84327" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                              <h2 class="elementor-heading-title elementor-size-default">
                              <span id="card-candidate-amount-goal">' . wc_price(get_post_meta($candidate_Id, '_goal', true)) . '</span>
                              </h2>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-567c4b0 e-con-full e-flex e-con e-child" 
                      data-id="567c4b0" data-element_type="container" 
                      data-settings="{&quot;content_width&quot;:&quot;full&quot;,&quot;jet_parallax_layout_list&quot;:[],&quot;container_type&quot;:&quot;flex&quot;}">'.$buttons.'
                        
                      </div>
                    </div>
                  </div>
                </div>                
                ';
            }
        } else {
            $next_btn = false;
            $candidates_content .= '<div class="no_found_message">No candidate found.</div>';
        }
        $msg = $candidates_content . "</div></div></div></div></div><br class = 'clear' />";
        $page_container .= "
        <div class='cvf-universal-pagination'>
            <ul>";
        if ($next_btn and $cur_page < $max_num_pages) {
            $nex = $cur_page + 1;
            $page_container .= "
                <li p='$nex' class='active' id='pnumber'>
                    <span class='candidate_loader' style='display:none'>
                        <img src='/wp-content/uploads/2023/04/candidates-loader.gif' width='55px' >
                    </span>
                    <button class='load_more load-btn-1'>Show More Candidates</button>
                </li>";
        }

        $page_container = $page_container . "
            </ul>
        </div>
        ";

        // Final cards output
        echo
            $msg . '<div class = "cvf-pagination-nav">' . $page_container . '</div>';
    }

    exit();
}


function haversine_distance($lat1, $lon1, $lat2, $lon2)
{

    $earth_radius = 3958; // Earth's radius in miles
    $delta_lat = deg2rad($lat2 - $lat1);
    $delta_lon = deg2rad($lon2 - $lon1);
    $a = sin($delta_lat / 2) * sin($delta_lat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($delta_lon / 2) * sin($delta_lon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earth_radius * $c;
    return $distance;
}
function search_posts_within_radius($user_latitude, $user_longitude, $radius)
{
    global $wpdb;

    // Fetch latitude, longitude, and post_id from post_meta table
    $sql = $wpdb->prepare(
        "
        SELECT
            pm.post_id,
            CAST(pm.meta_value AS DECIMAL(10, 6)) AS latitude,
            CAST(pm2.meta_value AS DECIMAL(10, 6)) AS longitude
        FROM
            {$wpdb->prefix}postmeta pm
        INNER JOIN
            {$wpdb->prefix}postmeta pm2 ON pm.post_id = pm2.post_id
        WHERE
            pm.meta_key = '_latitude'
            AND pm2.meta_key = '_longitude'
        "
    );

    $results = $wpdb->get_results($sql);

    // Calculate distance and filter results within the specified radius
    $user_latitude = floatval($user_latitude);
    $user_longitude = floatval($user_longitude);
    $searchRadius = floatval($radius);

    $newLocation = array();

    foreach ($results as $result) {
        if (!empty($result->latitude) && !empty($result->longitude)) {
            $location_lat = floatval($result->latitude);
            $location_long = floatval($result->longitude);

            // Calculate distance using Haversine formula
            $distance = haversine_distance($user_latitude, $user_longitude, $location_lat, $location_long);

            if ($distance <= $searchRadius) {
                $newLocation[] = $result->post_id;
            }

            unset($result->latitude); // Remove latitude index
            unset($result->longitude); // Remove longitude index
        }
    }

    return $newLocation;
}




