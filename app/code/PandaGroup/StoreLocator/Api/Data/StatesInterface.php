<?php

namespace PandaGroup\StoreLocator\Api\Data;

/**
 * Interface StatesInterface
 *
 * @package PandaGroup\StoreLocator\Api\Data
 */
interface StatesInterface
{
    /**#@+
     * Constants for keys of data array.
     */
    const ENTITY_ID = 'state_id';
    const STATE_NAME = 'state_name';
    const SHORT_STATE_NAME = 'state_short_name';
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
     * Get state name value.
     *
     * @return string
     */
    public function getStateName();

    /**
     * Set state name value.
     *
     * @param string $stateName
     *
     * @return $this
     */
    public function setStateName($stateName);

    /**
     * Get short state name value.
     *
     * @return string
     */
    public function getShortStateName();

    /**
     * Set short state name value.
     *
     * @param string $shortStateName
     *
     * @return $this
     */
    public function setShortStateName($shortStateName);

}
