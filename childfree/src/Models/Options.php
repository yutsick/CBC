<?php

namespace WZ\ChildFree\Models;

class Options
{
    const PREFIX = 'cbc_';

    /**
     * Data storage for model
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Array of changed keys
     *
     * @var array
     */
    protected array $changed = [];

    /**
     * Get value from data store
     *
     * @param $name
     * @return false|mixed
     */
    public function __get( $name ) {
        if ( ! isset( $this->data[ $name ] ) || is_null( $this->data[ $name ] ) ) {
            $this->data[ $name ] = get_option( self::PREFIX . $name );
        }

        return $this->data[ $name ];
    }

    /**
     * Set value for data store
     *
     * @param $name
     * @param $value
     * @return mixed|void
     */
    public function __set( $name, $value ) {
        $this->data[ $name ] = $value;

        if ( ! in_array( $name, $this->changed ) ) {
            $this->changed[] = $name;
        }
    }

    /**
     * Get form values
     *
     * @param $form_id
     * @return array
     */
    public function get_form_settings( $form_id ) {
        $settings = $this->{"form_settings_{$form_id}"};

        return maybe_unserialize( $settings );
    }

    /**
     * Set form values
     *
     * @param $form_id
     * @param $values
     */
    public function set_form_settings( $form_id, $values ) {
        $this->{"form_settings_{$form_id}"} = maybe_unserialize( $values );
    }

    /**
     * Save changes to options
     */
    public function save() {
        foreach ( $this->changed as $key ) {
            update_option( self::PREFIX . $key, $this->data[ $key ], false );
        }
    }
}
