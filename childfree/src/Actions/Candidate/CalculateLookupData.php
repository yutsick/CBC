<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;
use WZ\ChildFree\Models\Candidate;

class CalculateLookupData extends Hook
{
    public static array $hooks = array(
        'frm_after_create_entry',
        'frm_after_update_entry'
    );

    /**
     * Number of arguments accepted
     *
     * @var int
     */
    public static int $arguments = 2;

    /**
     * Action priority
     *
     * @var int
     */
    public static int $priority = 50;

    /**
     * Formidable Form ID
     *
     * @var int
     */
    private int $form_id = 3;

    /**
     * Calculate candidate lookup data
     *
     * @param $entry_id
     * @param $form_id
     */
    public function __invoke( $entry_id, $form_id ) {
        if ( $this->form_id !== 3 && $this->form_id !== 6 ) {
            return;
        }

        $entry = \FrmEntry::getOne( $entry_id );

        wc_update_user_last_active( $entry->user_id );

        $candidate = new Candidate( $entry->post_id );
        $candidate->set_sku( $entry->post_id );
        $candidate->set_user_id( $entry->user_id );
        $candidate->generate_lookup_data();
        $candidate->generate_funding_data();
        $candidate->generate_progress_data();
        $candidate->save();
    }
}

