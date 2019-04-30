<?php
/**
 * Created by PhpStorm.
 * User: florianfazer
 * Date: 2019-04-15
 * Time: 12:23
 */

namespace App\Utils;


use Symfony\Component\Form\Form;

class FormError
{
    public static function getErrors(Form $form) {

        $errors = [];

        foreach ($form->getErrors(true) as $error) {
            $errors[] = [ 'field' => $error->getOrigin()->getConfig()->getName(), 'message' => $error->getMessage()];
        }

        return $errors;
    }
}