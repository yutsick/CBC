<?php

namespace WZ\ChildFree\Actions;

use WZ\ChildFree\Integrations\GoogleDrive;

class UploadFileToGoogleDrive extends Hook
{
    /**
     * Action to hook to
     *
     * @var array|string[]
     */
    public static array $hooks = array('frm_after_create_entry');

    /**
     * Number of arguments accepted
     *
     * @var int
     */
    public static int $arguments = 2;

    /**
     * Formidable Form ID
     *
     * @var int
     */
    private int $form_id = 12;

    /**
     * Formidable file field id
     *
     * @var int
     */
    private int $upload_id = 96;

    /**
     * Upload file to Google Drive
     *
     * @param $entry_id
     * @param $form_id
     */
    public function __invoke( $entry_id, $form_id ) {
        if ( $form_id !== $this->form_id ) {
            return;
        }

        $googleDrive = new GoogleDrive();

        $entry = \FrmEntry::getOne( $entry_id, true );
        $file = get_attached_file( $entry->metas[ $this->upload_id ] );

        $googleDrive->upload( $file );
    }
}
