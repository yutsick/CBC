<?php

namespace WZ\ChildFree\Actions\FormidableForms;

use FrmForm;
use WZ\ChildFree\Actions\Hook;

class RemoveRegistrationErrorsOfEmailUsername extends Hook
{
    public static array $hooks = array(
        'frm_validate_field_entry',
    );
    public static int $arguments = 3;
    public static int $priority = 25;

    public function __invoke($errors, $field, $value)
    {
        // Form IDs
        $form_id_candidate = 3;
        $form_id_advocate = 13;
        $form_id_donor = 8;
        $form_id_physician = 11;
        // Field IDs of Email
        $email_field_id_candidate = 9;
        $email_field_id_advocate = 97;
        $email_field_id_donor = 58;
        $email_field_id_physician = 74;
        // Field IDs of Username
        $username_field_id_candidate = 10;
        $username_field_id_advocate = 98;
        $username_field_id_donor = 59;
        $username_field_id_physician = 75;

        // Candidate email (Role: Candidate)
        if ($field->id == $email_field_id_candidate) {
            //Unset the Error
            unset($errors['field' . $field->id]);
            //Get the email from the Form
            $email_entered = $_POST['item_meta'][$email_field_id_candidate];
            //Find the user in the database from the form
            $user = get_user_by('email', $email_entered);
            if ($user) {
                // The user was found - do something with $user
                $user->add_role('candidate');
                wp_update_user($user);
                $form = FrmForm::getOne($form_id_candidate);
                //SKIPPING THE FORM ACTION 422
                $this->add_skip_form_action_filter(422);
            }
        }

        // Advocate email (Role: Customer)
        if ($field->id == $email_field_id_advocate) {
            //Unset the Error
            unset($errors['field' . $field->id]);
            //Get the email from the Form
            $email_entered = $_POST['item_meta'][$email_field_id_advocate];
            //Find the user in the database from the form
            $user = get_user_by('email', $email_entered);
            if ($user) {
                // The user was found - do something with $user
                $user->add_role('customer');
                wp_update_user($user);
                $form = FrmForm::getOne($form_id_advocate);
                //SKIPPING THE FORM ACTION 792
//               add_filter('frm_skip_form_action', 'form_action_conditions', 10, 2);
            }
        }

        // Donor email (Role: Subscriber)
        if ($field->id == $email_field_id_donor) {
            //Unset the Error
            unset($errors['field' . $field->id]);
            //Get the email from the Form
            $email_entered = $_POST['item_meta'][$email_field_id_donor];
            //Find the user in the database from the form
            $user = get_user_by('email', $email_entered);
            if ($user) {
                // The user was found - do something with $user
                $user->add_role('subscriber');
                wp_update_user($user);
                $form = FrmForm::getOne($form_id_donor);
                //SKIPPING THE FORM ACTION 58467
//              add_filter('frm_skip_form_action', 'form_action_conditions', 10, 2);
            }
        }

        // Physician email (Role: Medical Provider)
        if ($field->id == $email_field_id_physician) {
            //Unset the Erroe
            unset($errors['field' . $field->id]);
            //Get the email from the Form
            $email_entered = $_POST['item_meta'][$email_field_id_physician];
            //Find the user in the database from the form
            $user = get_user_by('email', $email_entered);
            if ($user) {
                // The user was found - do something with $user
                $user->add_role('medical_provider');
                wp_update_user($user);
                $form = FrmForm::getOne($form_id_physician);
                //SKIPPING THE FORM ACTION 58461
            //    add_filter('frm_skip_form_action', 'form_action_conditions', 10, 2);
            }
        }


        //Checking for the username Field error for Candidate
        if (
            $field->id == $username_field_id_candidate
            && isset($errors['field' . $field->id])
            && $errors['field' . $field->id] == 'This username is already registered.'
        ) {
            unset($errors['field' . $field->id]);
        }

        if (
            $field->id == $username_field_id_advocate &&
            isset($errors['field' . $field->id]) &&
            $errors['field' . $field->id] == 'This username is already registered.'
        ) {
            unset($errors['field' . $field->id]);
        }

        if (
            $field->id == $username_field_id_donor &&
            isset($errors['field' . $field->id]) &&
            $errors['field' . $field->id] == 'This username is already registered.'
        ) {
            unset($errors['field' . $field->id]);
        }

        if (
            $field->id == $username_field_id_physician
            && isset($errors['field' . $field->id])
            && $errors['field' . $field->id] == 'This username is already registered.'
        ) {
            unset($errors['field' . $field->id]);
        }

        return $errors;
    }

    // Define the form_action_conditions function outside of __invoke
    public function form_action_conditions($skip_this_action, $args)
    {
        // You can find this action ID in formidable form > settings > register user
        // And search for "Action ID:" (You'll find it at bottom-right of the "Register User" action.)
        $target_action_id = isset($args[2]) ? $args[2] : 0; // Ensure the target action ID is provided
        if ($args['action']->ID == $target_action_id) {
            $skip_this_action = true;
        }
        return $skip_this_action;
    }

    // Helper function to add the skip form action filter
    private function add_skip_form_action_filter($target_action_id)
    {
        add_filter('frm_skip_form_action', array($this, 'form_action_conditions'), 50, 2);
        add_filter('frm_skip_form_action', function ($skip, $args) use ($target_action_id) {
            return $this->form_action_conditions($skip, $args, $target_action_id);
        }, 50, 2);
    }
}