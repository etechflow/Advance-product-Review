<?php
/**
 * ETechFlow_AdvancedProductReviewsHyva
 *
 * @author ETechFlow <etechflow0@gmail.com>
 */
declare(strict_types=1);

namespace ETechFlow\AdvancedProductReviewsHyva\Block;

use ETechFlow\AdvancedProductReviews\Model\Config;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Hyvä PDP block. Supplies the product, store code and GraphQL endpoint to the
 * Tailwind/Alpine template, which then drives the whole reviews UI through this
 * module's GraphQL API.
 */
class ProductReviews extends Template
{
    /**
     * @param Context $context
     * @param Registry $registry
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param array<string,mixed> $data
     */
    public function __construct(
        Context $context,
        private readonly Registry $registry,
        private readonly Config $config,
        private readonly StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    /**
     * @return bool
     */
    public function isTranslationEnabled(): bool
    {
        return $this->config->isTranslationEnabled();
    }

    /**
     * @return bool
     */
    public function isQaEnabled(): bool
    {
        return $this->config->isFlag(Config::XML_PATH_ENABLE_QA);
    }

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        $product = $this->registry->registry('current_product');
        return $product instanceof ProductInterface ? $product : null;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        $product = $this->getProduct();
        return $product ? (int) $product->getId() : 0;
    }

    /**
     * @return string
     */
    public function getProductSku(): string
    {
        $product = $this->getProduct();
        return $product ? (string) $product->getSku() : '';
    }

    /**
     * Current store code — sent as the GraphQL "Store" header for multi-store.
     *
     * @return string
     */
    public function getStoreCode(): string
    {
        try {
            return (string) $this->storeManager->getStore()->getCode();
        } catch (\Exception $e) {
            return 'default';
        }
    }

    /**
     * Absolute GraphQL endpoint URL.
     *
     * @return string
     */
    public function getGraphQlUrl(): string
    {
        return $this->getUrl('graphql', ['_secure' => true]);
    }
}
