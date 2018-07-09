<?php

namespace PandaGroup\LooknbuyExtender\Controller\Cart;

class Add extends \Magedelight\Looknbuy\Controller\Cart\Add
{
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $this->_optionModel = $objectManager->create('\Magento\Catalog\Model\Product\Option');
        $this->_bundleOption = $objectManager->create('\Magento\Bundle\Model\Option');
        $this->resultJsonFactory = $objectManager->create('\Magento\Framework\Controller\Result\JsonFactory');

        $resultJson = $this->resultJsonFactory->create();

        $response = new \Magento\Framework\DataObject();
        $error = false;

        $bundleMessages = array();
        $posts = $this->getRequest()->getPost();

        $resultRedirect = $this->resultRedirectFactory->create();

        foreach ($posts as $key => $option) {
            if ($key == 'super_attribute') {
                $productId = key($option);
                $opType = $key;

                if ($opType == 'super_attribute') {
                    foreach ($option as $k => $opt) {
                        if (is_array($opt)) {
                            foreach ($opt as $ke => $o) {
                                $attrId = $ke;
                                $optionArr[$k]['super_attribute'][$attrId] = $o;
                            }
                        } else {
                            $attrId = key($opt);
                            $optionArr[$k]['super_attribute'][$attrId] = $opt[$attrId];
                        }
                    }
                }
            } elseif ($key == 'options') {
                foreach ($option as $optId => $opt) {
                    $optionValues = $this->_optionModel->load($optId);
                    $productId = $optionValues->getData('product_id');
                    $opType = $optionValues->getType();

                    if (in_array($opType, ['field', 'area', 'drop_down', 'radio'])) {
                        $optionArr[$productId]['options'][$optId] = $opt;
                    } elseif (in_array($opType, ['checkbox', 'multiple'])) {
                        $optionArr[$productId]['options'][$optId] = $opt;
                    } elseif (in_array($opType, ['date', 'date_time', 'time'])) {
                        foreach ($opt as $valueType => $value) {
                            $optionArr[$productId]['options'][$optId][$valueType] = $value;
                        }
                    }
                }
            } elseif (isset($key) && in_array($key, ['bundle_option', 'bundle_option_qty'])) {
                foreach ($option as $optKey => $opt) {
                    $optionId = $optKey;
                    $bundleOp = $this->_bundleOption->load($optionId);
                    $productId = $bundleOp->getData('parent_id');
                    $opType = 'bundle_option';

                    $optionArr[$productId][$key][$optionId] = $opt;
                }
            } elseif ($key == 'links') {
                $productId = key($option);

                foreach ($option as $k => $opt) {
                    if (is_array($opt)) {
                        foreach ($opt as $ke => $o) {
                            $attrId = $ke;
                            $optionArr[$k]['links'][$attrId] = $o;
                        }
                    } else {
                        $attrId = key($opt);
                        $optionArr[$k]['links'][$attrId] = $opt[$attrId];
                    }
                }
            }
        }

        $postParams = $this->getRequest()->getParams();

        /* Add one product from all look */
        $productIdToAdd = $this->getRequest()->getParam('product_id', null);
        /* Add one product from all look */

        $products = $posts['qty'];

        $filter = new \Zend_Filter_LocalizedToNormalized(
            ['locale' => $this->_objectManager->get('Magento\Framework\Locale\ResolverInterface')->getLocale()]
        );

        try {

            foreach ($products as $productId => $qty) {

                /* Add one product from all look */
                if (null !== $productIdToAdd && $productId != $productIdToAdd) {
                    continue;
                }
                /* Add one product from all look */

                if ($qty > 0) {
                    $productParams['qty'] = $filter->filter($qty);

                    $product = $this->initProduct($productId);
                    $params = ['qty' => $qty];

                    if (isset($optionArr[$productId])) {
                        if (isset($optionArr[$productId]['options'])) {
                            $params['options'] = $optionArr[$productId]['options'];
                        }

                        if (isset($optionArr[$productId]['super_attribute'])) {
                            $params['super_attribute'] = $optionArr[$productId]['super_attribute'];
                        }

                        if (isset($optionArr[$productId]['links'])) {
                            $params['links'] = $optionArr[$productId]['links'];
                        }

                        if (isset($optionArr[$productId]['bundle_option'])) {
                            $params['bundle_option'] = $optionArr[$productId]['bundle_option'];
                            $params['bundle_option_qty'] = $optionArr[$productId]['bundle_option_qty'];
                        }
                    }


                    $this->cart->addProduct($product, $params);


                    $this->_eventManager->dispatch(
                        'checkout_cart_add_product_complete', ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
                    );
                    if (!$this->_checkoutSession->getNoCartRedirect(true)) {
                        if (!$this->cart->getQuote()->getHasError()) {
                            $bundleMessages[] = __(
                                'You added %1 to your shopping cart.', $product->getName()
                            );
                        }
                    }
                }
            }

            $this->_eventManager->dispatch(
                'looknbuy_look_add_ids', ['look_id' => $postParams['look_id'], 'cart' => $this->cart]
            );
            $this->cart->save();

            if (!$this->_checkoutSession->getNoCartRedirect(true)) {
                if (!$this->cart->getQuote()->getHasError()) {
                    foreach ($bundleMessages as $bundleMessage) {
                        $this->messageManager->addSuccessMessage($bundleMessage);
                    }
                }

                $backUrl = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getBaseUrl().'checkout/cart';

                $response->setError($error);
                $response->setMessage($bundleMessage);
                $response->setUrl($backUrl);
                $resultJson->setJsonData($response->toJson());

                return $resultJson;
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());

            $response->setError(true);
            $response->setMessage($e->getMessage());
            $resultJson->setJsonData($response->toJson());

            return $resultJson;
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $response->setError(true);
            $response->setMessage($e->getMessage());
            $resultJson->setJsonData($response->toJson());

            return $resultJson;
        }
    }
}
