<?php

namespace Gallery\Form;

use Zend\Form\Form,
    ZfcBase\Form\ProvidesEventsForm;

class Upload extends ProvidesEventsForm
{
    public function init()
    {
        $this->setMethod('post');

        $this->addDecorator('FormErrors')
             ->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'dl', 'class' => 'login_form'))
             ->addDecorator('FormDecorator');

        $this->addElement('file','file', array(
            'required'   => true,
            'label'      => 'Photo',
            'validators' => array(
                array('Extension', false, array('jpg,png,jpeg', 'messages'=>'The file must be a JPG or PNG file')),
            ),
            'valuedisabled' => true,
        ));

        $this->addElement('text', 'title', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => 'Title',
            'filters'    => array(
                'StripTags',
                'StringTrim',
            ),
        ));
        //$this->file->setValueDisabled(true);
        //$this->photo->setDestination(APPLICATION_PATH . "/tmp/");
        //$this->photo->addValidator('NotExists', false);

        $this->addElement('textarea', 'description', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => 'Description',
            'rows'       => '4',
            'filters'    => array(
                'StripTags',
                'StringTrim',
            ),
        ));

        // $this->addElement('hash', 'csrf', array(
        //     'ignore'     => true,
        //     'decorators' => array('ViewHelper'),
        // ));

        $this->addElement('submit', 'upload', array(
            'ignore'   => true,
            'label'    => 'Upload',
        ));

        $this->events()->trigger('init', $this);
    }
}
