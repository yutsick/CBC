<?php

?>
<div id="candidate_form_entry" class="panel woocommerce_options_panel padded" style="text-align: left;">
    <?php

    $show_args = array(
        'id'             => $candidate_entry->id,
        'entry'          => $candidate_entry,
        'include_blank'  => true,
        'include_extras' => 'page, section, password',
        'inline_style'   => 1,
        'class'          => 'frm-alt-table',
        'show_filename'  => true,
        'show_image'     => true,
        'size'           => 'thumbnail',
        'add_link'       => true,
    );

    /**
     * Allows modifying the arguments when showing entry in the Entries page.
     *
     * @since 5.0.16
     *
     * @param array $show_args The arguments.
     * @param array $args      Includes `form`.
     */
    $show_args = apply_filters( 'frm_entries_show_args', $show_args );

    echo FrmEntriesController::show_entry_shortcode( $show_args );

    ?>
</div>
