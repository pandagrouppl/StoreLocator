<?php

namespace PandaGroup\StoreLocator\Block\Adminhtml\System\Config;

class ButtonCoordinates extends \Magento\Config\Block\System\Config\Form\Field
{
//    /** @var UrlInterface */
//    protected $_urlBuilder;

    /** @var \Magento\Framework\Json\Helper\Data */
    protected $jsonHelper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        array $data = []
    ) {
//        $this->_urlBuilder = $context->getUrlBuilder();
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
    }

    /**
     * Set template
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('PandaGroup_StoreLocator::system/config/coordinates_button.phtml');
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'pandagroup_set_coordinates_by_country_button',
                'label' => __('Get Coordinates By Country'),
                'onclick' => 'javascript:location.reload();',
            ]
        );

        return $button->toHtml();
    }
    
//    public function getAdminUrl(){
//        return $this->_urlBuilder->getUrl('magepalGmailsmtpapp/test', ['store' => $this->_request->getParam('store')]);
//    }

    public function getCoordinatesByCountry($country = 'PL')
    {
        $apiKey = 'AIzaSyD06oeZOxRpKwKCg3G0pEilZmgunVdgTUA';
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $country . '&key=' . $apiKey;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

//        $countryInformation = json_encode($output);
//        $countryInformation = $this->helper(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($output);

        $countryInformation = $this->jsonHelper->jsonEncode($output);


        var_dump($countryInformation['results']['geometry']['location']['lat']); exit;

        $coordinates['lat'] = $countryInformation['results']['geometry']['location']['lat'];
        $coordinates['lng'] = $countryInformation['results']['geometry']['location']['lng'];

        return $coordinates;
    }

    /**
     * Render button
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        // Remove scope label
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }
}
