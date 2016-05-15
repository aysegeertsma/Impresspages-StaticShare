<?php
/**
 * @package   ImpressPages
 */

namespace Plugin\StaticShare;


use Plugin\StaticShare\Widget\StaticShareButton\Controller;

class AdminController
{

    /**
     * Button.js ask to provide widget management popup HTML. This controller does this.
     * @return \Ip\Response\Json
     * @throws \Ip\Exception\View
     */
    public function widgetPopupHtml()
    {
        $widgetId = ipRequest()->getQuery('widgetId');
        $widgetRecord = \Ip\Internal\Content\Model::getWidgetRecord($widgetId);
        $widgetData = $widgetRecord['data'];

        //create form prepopulated with current widget data
        $form = $this->managementForm($widgetData);

        //Render form and popup HTML
        $viewData = array(
            'form' => $form,
        );
        $popupHtml = ipView('view/editPopup.php', $viewData)->render();
        $data = array(
            'popup' => $popupHtml,
        );

        //Return rendered widget management popup HTML in JSON format
        return new \Ip\Response\Json($data);
    }


    /**
     * Check widget's posted data and return data to be stored or errors to be displayed
     */
    public function checkForm()
    {
        $data = ipRequest()->getPost();
        $form = $this->managementForm();
        $data = $form->filterValues($data); //filter post data to remove any non form specific items
        $errors = $form->validate($data); //http://www.impresspages.org/docs/form-validation-in-php-3
        if ($errors) {
            //error
            $data = array(
                'status' => 'error',
                'errors' => $errors,
            );
        } else {
            //success
            unset($data['aa']);
            unset($data['securityToken']);
            unset($data['antispam']);
            $data = array(
                'status' => 'ok',
                'data'   => $data,

            );
        }

        return new \Ip\Response\Json($data);
    }

    protected function managementForm($widgetData = array())
    {
        $form = new \Ip\Form();

        $form->setEnvironment(\Ip\Form::ENVIRONMENT_ADMIN);

        //setting hidden input field so that this form would be submitted to 'errorCheck' method of this controller. (http://www.impresspages.org/docs/controller)
        $field = new \Ip\Form\Field\Hidden(
            array(
                'name'  => 'aa',
                'value' => 'StaticShare.checkForm',
            )
        );

        $form->addField($field);

        //Input fields to adjust widget settings

        // get available types
        $types = Controller::getTypes();
        reset($types);

        // define the default for new widget
        $default = key($types);

        // get the default for existing widget
        if (isset($widgetData['type'])) {
            $default = $widgetData['type'];
        }

        // arrange the types for the select box
        $values = array();
        foreach ($types as $key => $type) {
            $values[] = array($key, $type['name']);
        }

        $field = new \Ip\Form\Field\Select(
            array(
                'name'   => 'type',
                'label'  => __('Type', 'StaticShare', FALSE),
                'value'  => $default,
                'values' => $values,
            ));
        $form->addField($field);


        $field = new \Ip\Form\Field\Text(
            array(
                'name'  => 'title',
                'label' => __('Title', 'StaticShare'),
                'value' => empty($widgetData['title']) ? NULL : $widgetData['title'],
            ));

        $form->addField($field);

        $field = new \Ip\Form\Field\Textarea(
            array(
                'name'  => 'description',
                'label' => __('Description', 'StaticShare'),
                'value' => empty($widgetData['description']) ? NULL : $widgetData['description'],
            ));

        $form->addField($field);


        //ADD YOUR OWN FIELDS

        return $form;
    }


}
