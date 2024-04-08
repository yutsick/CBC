<?php

namespace WZ\ChildFree\Actions\Candidate;

use FrmEntry;
use WZ\ChildFree\Actions\Hook;
use WZ\ChildFree\Models\Options;

class AddImagesToProductGallery extends Hook
{
    public static array $hooks = array(
        'frm_after_create_entry',
        'frm_after_update_entry'
    );

    public static int $arguments = 2;
    public static int $priority = 30;

    public function __invoke( $entry_id, $form_id ) {
        if ( 3 !== $form_id && 6 !== $form_id ) {
            return;
        }

        $options = new Options();
        $form_settings = $options->get_form_settings( $form_id );
        $gallery_id = $form_settings['gallery_field'];
        $entry = FrmEntry::getOne( $entry_id );

        update_post_meta( $entry->post_id, '_product_image_gallery', implode( ',', (array) $_POST['item_meta'][ $gallery_id ] ) );
    }
}
