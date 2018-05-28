<?php

namespace PandaGroup\CronSchedulerExtender\Ui\Component\Listing\Column;

class IndexActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /** Url path */
    const URL_PATH_JOB_RUN = 'cronschedulerextender/job/run';

    /** @var \Magento\Framework\UrlInterface  */
    protected $urlBuilder;


    /**
     * constructor
     *
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['code'])) {
                    $item[$this->getData('name')] = [
                        'run'   => [
                            'href'  => $this->urlBuilder->getUrl(
                                static::URL_PATH_JOB_RUN,
                                [
                                    'code' => $item['code']
                                ]
                            ),
                            'label' => __('Run now')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
