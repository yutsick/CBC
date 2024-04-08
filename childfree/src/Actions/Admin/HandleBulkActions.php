<?php

namespace WZ\ChildFree\Actions\Admin;

use Wz\Childfree\Actions\Hook;

class HandleBulkActions extends Hook
{
    public static array $hooks = array(
        'handle_bulk_actions-edit-product'
    );
    public static int $arguments = 3;

    public function __invoke( $redirect, $action, $object_ids ) {
        if ( 'cbc_refresh_lookup' === $action ) {
            foreach ( $object_ids as $id ) {
                $product = wc_get_product( $id );

                if ( $product->get_type() !== 'candidate' ) {
                    continue;
                }

                $product->generate_lookup_data();
                $product->save();
            }
        }

        if ( 'cbc_refresh_progress' === $action ) {
            foreach ( $object_ids as $id ) {
                $product = wc_get_product( $id );

                if ( $product->get_type() !== 'candidate' ) {
                    continue;
                }

                $product->generate_progress_data();
                $product->save();
            }
        }

        if ( 'cbc_refresh_honorarium' === $action ) {
            foreach ( $object_ids as $id ) {
                $product = wc_get_product( $id );

                if ( $product->get_type() !== 'candidate' ) {
                    continue;
                }

                $product->generate_funding_data();
                $product->save();
            }
        }

        return $redirect;
    }
}