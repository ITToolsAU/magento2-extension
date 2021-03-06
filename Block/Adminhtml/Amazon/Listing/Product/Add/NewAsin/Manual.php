<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  M2E LTD
 * @license    Commercial use is forbidden
 */
namespace Ess\M2ePro\Block\Adminhtml\Amazon\Listing\Product\Add\NewAsin;

/**
 * Class \Ess\M2ePro\Block\Adminhtml\Amazon\Listing\Product\Add\NewAsin\Manual
 */
class Manual extends \Ess\M2ePro\Block\Adminhtml\Magento\Grid\AbstractContainer
{
    //########################################

    public function _construct()
    {
        parent::_construct();

        // Initialization block
        // ---------------------------------------
        $this->setId('newAsinDescriptionTemplateManual');
        // ---------------------------------------

        // Set header text
        // ---------------------------------------
        $this->_controller = 'adminhtml_amazon_listing_product_add_newAsin_manual';
        // ---------------------------------------

        // Set buttons actions
        // ---------------------------------------
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->removeButton('delete');
        $this->removeButton('add');
        $this->removeButton('save');
        $this->removeButton('edit');
        // ---------------------------------------

        // ---------------------------------------
        $this->addButton('back', [
            'label'     => $this->__('Back'),
            'onclick'   => 'ListingGridHandlerObj.stepNewAsinBack()',
            'class'     => 'back'
        ]);
        // ---------------------------------------

        $url = $this->getUrl('*/*/index', ['_current' => true, 'step' => 5]);
        // ---------------------------------------
        $this->addButton('save_and_go_to_listing_view', [
            'id'      => 'amazon_listing_category_continue_btn',
            'label'   => $this->__('Continue'),
            'onclick' => 'ListingGridHandlerObj.checkManualProducts(\''.$url.'\')',
            'class'   => 'action-primary forward'
        ]);
        // ---------------------------------------
    }

    public function getGridHtml()
    {
        $listing = $this->getHelper('Data\GlobalData')->getValue('listing_for_products_add');

        $viewHeaderBlock = $this->createBlock(
            'Listing_View_Header',
            '',
            ['data' => ['listing' => $listing]]
        );

        return $viewHeaderBlock->toHtml() . parent::getGridHtml();
    }

    protected function _toHtml()
    {
        // TEXT
        $this->jsTranslator->addTranslations([
            'templateDescriptionPopupTitle' => $this->__('Assign Description Policy'),
            'setDescriptionPolicy' => $this->__('Set Description Policy'),
            'Add New Description Policy' => $this->__('Add New Description Policy')
        ]);
        // ---------------------------------------

        // URL
        $this->jsUrl->addUrls($this->getHelper('Data')->getControllerActions('Amazon_Listing_Product'));
        $this->jsUrl->addUrls(
            $this->getHelper('Data')->getControllerActions('Amazon_Listing_Product_Template_Description')
        );

        $this->jsUrl->add($this->getUrl('*/amazon_listing_product_template_description/viewGrid', [
            'map_to_template_js_fn' => 'selectTemplateDescription'
        ]), 'amazon_listing_product_template_description/viewGrid');

        $this->jsUrl->add(
            $this->getUrl('*/amazon_listing_product_add/checkNewAsinManualProducts', ['_current' => true]),
            'amazon_listing_product_add/checkNewAsinManualProducts'
        );

        $this->jsUrl->add(
            $this->getUrl('*/amazon_listing_product_add/resetDescriptionTemplate', ['_current' => true]),
            'amazon_listing_product_add/resetDescriptionTemplate'
        );
        // ---------------------------------------

        $this->js->add(
            <<<JS
    selectTemplateDescription = function (el, templateId, mapToGeneralId)
    {
        ListingGridHandlerObj.mapToTemplateDescription(el, templateId, mapToGeneralId);
    };

    require([
        'M2ePro/Amazon/Listing/Product/Add/NewAsin/Template/Description/Grid',
    ],function() {
        Common.prototype.scrollPageToTop = function() { return; }

        window.ListingGridHandlerObj = new AmazonListingProductAddNewAsinTemplateDescriptionGrid(
            '{$this->getChildBlock('grid')->getId()}',
            {$this->getRequest()->getParam('id')}
        );

        ListingGridHandlerObj.afterInitPage();
    });
JS
        );

        return '<div id="search_asin_products_container">' .
                parent::_toHtml() .
            '</div>';
    }

    //########################################
}
