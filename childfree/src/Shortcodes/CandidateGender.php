<?php

namespace WZ\ChildFree\Shortcodes;

class CandidateGender
{
    public function __invoke()
    {
        global $post;

        echo '
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <a class="elementor-button elementor-size-sm" role="button" 
                style="'. (get_post_meta($post->ID, '_sex', true) == "female" ? "line-height:18px;color:#9d2d9d;background-color:#fbedfe;" : "line-height:18px;color:#478BF2;background-color:#EDF3FE;") . '">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-icon elementor-align-icon-left">
                        <i class="fas '. (get_post_meta($post->ID, '_sex', true) == "male" ? "fa-mars" : "fa-venus") . '">
                        </i>
                    </span>
                    <span class="elementor-button-text">'
                        . ucfirst(get_post_meta($post->ID, '_sex', true))
                    . '</span>
                </span>
            </a>
        ';
    }
}