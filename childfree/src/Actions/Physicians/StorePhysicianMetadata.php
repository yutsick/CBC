<?php

namespace WZ\ChildFree\Actions\Physicians;

use WZ\ChildFree\Actions\Hook;
use WZ\ChildFree\Models\ZipCode;
use WZ\ChildFree\Models\Provider;

class StorePhysicianMetadata extends Hook
{
    /**
     * Action hooks
     *
     * @var string[]
     */
    public static array $hooks = [
        'frm_after_create_entry',
        'frm_after_update_entry'
    ];

    /**
     * Action number of arguments
     *
     * @var int
     */
    public static int $arguments = 2;

    /**
     * Action priority
     *
     * @var int
     */
    public static int $priority = 100;

    /**
     * Formidable Form ID
     *
     * @var int
     */
    private int $form_id = 13;

    /**
     * Update entry meta with latitude/longitude
     *
     * @param $entry_id
     * @param $form_id
     */
    public function __invoke( $entry_id, $form_id ) {
        if ( $form_id !== $this->form_id ) {
            return;
        }

        $address = \FrmEntryMeta::get_entry_meta_by_field( $entry_id, 109 );

        $zipcode = ZipCode::find( $address['zip'] );

        \FrmEntryMeta::add_entry_meta( $entry_id, 128, 'latitude', $zipcode->latitude );
        \FrmEntryMeta::add_entry_meta( $entry_id, 129, 'longitude', $zipcode->longitude );
    }
}


