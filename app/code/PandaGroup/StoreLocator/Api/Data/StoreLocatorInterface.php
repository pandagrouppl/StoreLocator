<?php

namespace PandaGroup\StoreLocator\Api\Data;

/**
 * Interface StoreLocatorInterface
 *
 * @package PandaGroup\StoreLocator\Api\Data
 */
interface StoreLocatorInterface
{
    /**#@+
     * Constants for keys of data array.
     */
    const ENTITY_ID     = 'storelocator_id';
    const STORE_NAME    = 'name';
    const STORE_ADDRESS = 'address';
    const STORE_CITY    = 'city';
    const STORE_ZIPCODE = 'zipcode';
    const STORE_EMAIL   = 'email';
    const STORE_PHONE   = 'phone';
    const STORE_STATUS  = 'status';
    /**#@-*/

    /**
     * Get entityId value.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set entityId value.
     *
     * @param int $entityId
     *
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get store name value.
     *
     * @return string
     */
    public function getStoreName();

    /**
     * Set store name value.
     *
     * @param string $storeName
     *
     * @return $this
     */
    public function setStoreName($storeName);

    /**
     * Get store address value.
     *
     * @return string
     */
    public function getStoreAddress();

    /**
     * Set store address value.
     *
     * @param string $storeAddress
     *
     * @return $this
     */
    public function setStoreAddress($storeAddress);

    /**
     * Get store city value.
     *
     * @return string
     */
    public function getStoreCity();

    /**
     * Set store city value.
     *
     * @param string $storeCity
     *
     * @return $this
     */
    public function setStoreCity($storeCity);

    /**
     * Get store zip code value.
     *
     * @return string
     */
    public function getStoreZipCode();

    /**
     * Set store zip code value.
     *
     * @param string $storeZipCode
     *
     * @return $this
     */
    public function setStoreZipCode($storeZipCode);

    /**
     * Get store email value.
     *
     * @return string
     */
    public function getStoreEmail();

    /**
     * Set store email value.
     *
     * @param string $storeEmail
     *
     * @return $this
     */
    public function setStoreEmail($storeEmail);

    /**
     * Get store phone value.
     *
     * @return string
     */
    public function getStorePhone();

    /**
     * Set store phone value.
     *
     * @param string $storePhone
     *
     * @return $this
     */
    public function setStorePhone($storePhone);

    /**
     * Get store status value.
     *
     * @return string
     */
    public function getStoreStatus();

    /**
     * Set store status value.
     *
     * @param string $storeStatus
     *
     * @return $this
     */
    public function setStoreStatus($storeStatus);

}
