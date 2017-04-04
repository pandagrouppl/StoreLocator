<?php


namespace MagicToolbox\Magic360\Classes;



/**
 * MagicToolboxParamsClass
 *
 */
class MagicToolboxParamsClass
{

    /**
     * Options
     *
     * @var   array
     *
     */
    public $params = [];

    /**
     * General profile
     *
     * @var   string
     *
     */
    public $generalProfile = 'default';

    /**
     * Current profile
     *
     * @var   string
     *
     */
    public $currentProfile = '';

    /**
     * Scope
     *
     * @var   string
     *
     */
    public $scope = 'default';

    /**
     * Mapping array
     *
     * @var   array
     *
     */
    public $mapping = [];

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->params = [$this->generalProfile => []];
        $this->currentProfile = $this->generalProfile;
    }

    /**
     * Method to set scope
     *
     * @param string $scope Scope
     *
     * @return void
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * Method to get current profile name
     *
     * @return string
     */
    public function getProfile()
    {
        return $this->currentProfile;
    }

    /**
     * Method to get all profile names
     *
     * @return array
     */
    public function getProfiles()
    {
        return array_keys($this->params);
    }

    /**
     * Method to set current profile name
     *
     * @param string $profile Profile name
     *
     * @return boolean
     */
    public function setProfile($profile)
    {
        /*if (!$profile) return false;
        if (!isset($this->params[$profile])) {
            $this->params[$profile] = [];
        }*/
        $this->currentProfile = $profile;
        return true;
    }

    /**
     * Method to rename general profile
     *
     * @param string $profile Profile name
     *
     * @return boolean
     */
    public function renameGeneralProfile($profile)
    {
        if (!$profile) {
            return false;
        }
        if ($this->generalProfile != $profile) {
            $this->params[$profile] = $this->params[$this->generalProfile];
            if ($this->currentProfile == $this->generalProfile) {
                $this->currentProfile = $profile;
            }
            unset($this->params[$this->generalProfile]);
            $this->generalProfile = $profile;
        }
        return true;
    }

    /**
     * Method to reset to general profile
     *
     * @return void
     */
    public function resetProfile()
    {
        $this->currentProfile = $this->generalProfile;
    }

    /**
     * Method to delete profile
     *
     * @param string $profile Profile name
     *
     * @return boolean
     */
    public function deleteProfile($profile)
    {
        if (isset($this->params[$profile]) && $profile != $this->generalProfile) {
            if ($profile == $this->currentProfile) {
                $this->currentProfile = $this->generalProfile;
            }
            unset($this->params[$profile]);
            return true;
        }
        return false;
    }

    /**
     * Method to check if profile exists
     *
     * @param string $profile Profile name
     *
     * @return boolean
     */
    public function profileExists($profile)
    {
        return isset($this->params[$profile]);
    }

    /**
     * Method to get profile's params
     *
     * @param string $profile Profile name
     *
     * @return array|null
     */
    public function getParams($profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        return isset($this->params[$profile]) ? $this->params[$profile] : null;
    }

    /**
     * Method to set profile's params
     *
     * @param array  $params Params to set
     * @param string $profile Profile name
     *
     * @return void
     */
    public function setParams($params, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        //if (!isset($this->params[$profile])) {
        //    $this->params[$profile] = [];
        //}
        //NOTICE: this method rewrite all params
        $this->params[$profile] = $params;
    }

    /**
     * Method to get param names
     *
     * @param string $profile Profile name
     *
     * @return array|null
     */
    public function getNames($profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        return isset($this->params[$profile]) ? array_keys($this->params[$profile]) : null;
    }

    /**
     * Method to append params
     *
     * @param array  $params Params to append
     * @param string $profile Profile name
     *
     * @return array
     */
    public function appendParams($params, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (!isset($this->params[$profile])) {
            $this->params[$profile] = [];
        }
        //NOTE: we can't use array_merge because it overwrites the subarrays, and not merge them
        //$this->params[$profile] = array_merge($this->params[$profile], $params);
        foreach ($params as $key => $value) {
            if (array_key_exists($key, $this->params[$profile]) && is_array($this->params[$profile][$key])) {
                $this->params[$profile][$key] = array_merge($this->params[$profile][$key], $value);
            } else {
                $this->params[$profile][$key] = $value;
            }
        }
        return $this->params[$profile];
    }

    /**
     * Method to check if param exists
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return boolean
     */
    public function paramExists($id, $profile = '', $strict = true)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        return isset($this->params[$profile][$id]) || !$strict && isset($this->params[$this->generalProfile][$id]);
    }

    /**
     * Method to remove param
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     *
     * @return void
     */
    public function removeParam($id, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (isset($this->params[$profile][$id])) {
            unset($this->params[$profile][$id]);
        }
    }

    /**
     * Method to get param's data
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return mixed|null
     */
    public function getParam($id, $profile = '', $strict = true)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        return isset($this->params[$profile][$id]) ? $this->params[$profile][$id] : ((!$strict && isset($this->params[$this->generalProfile][$id])) ? $this->params[$this->generalProfile][$id] : null);
    }

    /**
     * Method to set param's value
     *
     * @param string $id      Param ID
     * @param mixed  $value   Param value
     * @param string $profile Profile name
     *
     * @return void
     */
    public function setValue($id, $value, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (isset($this->params[$profile][$id])) {
            $this->params[$profile][$id]['value'] = $value;
        } elseif (isset($this->params[$this->generalProfile][$id])) {
            $this->params[$profile][$id] = $this->params[$this->generalProfile][$id];
            $this->params[$profile][$id]['value'] = $value;
        } else {
            $this->params[$profile][$id] = [
                'id' => $id,
                'group' => '',
                'order' => '',
                'default' => $value,
                'label' => '',
                'description' => '',
                'type' => 'text',
                'value' => $value,
                'scope' => ''
            ];
        }
    }

    /**
     * Method to set param's value (for mobile)
     *
     * @param string $id      Param ID
     * @param mixed  $value   Param value
     * @param string $profile Profile name
     *
     * @return void
     */
    public function setMobileValue($id, $value, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (isset($this->params[$profile][$id])) {
            $this->params[$profile][$id]['mobile-value'] = $value;
        } elseif (isset($this->params[$this->generalProfile][$id])) {
            $this->params[$profile][$id] = $this->params[$this->generalProfile][$id];
            $this->params[$profile][$id]['mobile-value'] = $value;
        } else {
            $this->params[$profile][$id] = [
                'id' => $id,
                'group' => '',
                'order' => '',
                'default' => $value,
                'label' => '',
                'description' => '',
                'type' => 'text',
                'mobile-value' => $value,
                'scope' => ''
            ];
        }
    }

    /**
     * Method to get param's value
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return mixed|null
     */
    public function getValue($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        if ($param) {
            return isset($param['value']) ? $param['value'] : $param['default'];
        }
        return null;
    }

    /**
     * Method to get param's value (for mobile)
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return mixed|null
     */
    public function getMobileValue($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        if ($param) {
            return isset($param['mobile-value']) ? $param['mobile-value'] : null;
        }
        return null;
    }

    /**
     * Method to get param's default value
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return mixed|null
     */
    public function getDefaultValue($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        return $param ? $param['default'] : null;
    }

    /**
     * Method to get param's values
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return array|null
     */
    public function getValues($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        if ($param) {
            return isset($param['values']) ? $param['values'] : [$param['default']];
        } else {
            return null;
        }
    }

    /**
     * Method to check if values exists
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return boolean
     */
    public function valuesExists($id, $profile = '', $strict = true)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        return $param ? isset($param['values']) : false;
    }

    /**
     * Method to set values
     *
     * @param string $id      Param ID
     * @param array  $values  Param values
     * @param string $profile Profile name
     *
     * @return void
     */
    public function setValues($id, $values, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (isset($this->params[$profile][$id])) {
            $this->params[$profile][$id]['values'] = $values;
        } elseif (isset($this->params[$this->generalProfile][$id])) {
            $this->params[$profile][$id] = $this->params[$this->generalProfile][$id];
            $this->params[$profile][$id]['values'] = $values;
        } //else param not exists
    }

    /**
     * Method to check param's value
     *
     * @param string  $id      Param ID
     * @param mixed   $value   Param values
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return boolean
     */
    public function checkValue($id, $value, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (!is_array($value)) {
            $value = [$value];
        }
        return in_array(strtolower($this->getValue($id, $profile, $strict)), array_map('strtolower', $value));
    }

    /**
     * Method to check param's value (for mobile)
     *
     * @param string  $id      Param ID
     * @param mixed   $value   Param values
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return boolean
     */
    public function checkMobileValue($id, $value, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (!is_array($value)) {
            $value = [$value];
        }
        return in_array(strtolower($this->getMobileValue($id, $profile, $strict)), array_map('strtolower', $value));
    }

    /**
     * Method to check group
     *
     * @param string $id    Param ID
     * @param string $group Group name
     *
     * @return boolean
     */
    public function checkGroup($id, $group/*, $profile = ''*/)
    {
        /*
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        */
        if (!isset($this->params[$this->generalProfile][$id]['group']) || empty($this->params[$this->generalProfile][$id]['group'])) {
            return false;
        }
        if (!is_array($group)) {
            $group = [$group];
        }
        return in_array(strtolower($this->params[$this->generalProfile][$id]['group']), array_map('strtolower', $group));
    }

    /**
     * Method to get param's group
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return string|null
     */
    public function getGroup($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        return $param ? $param['group'] : null;
    }

    /**
     * Method to get param's label
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return string|null
     */
    public function getLabel($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        return $param ? $param['label'] : null;
    }

    /**
     * Method to get param's description
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return string|null
     */
    public function getDescription($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        if ($param) {
            return isset($param['description']) ? $param['description'] : '';
        } else {
            return null;
        }
    }

    /**
     * Method to get param's type
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return string|null
     */
    public function getType($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        return $param ? $param['type'] : null;
    }

    /**
     * Method to get param's subtype
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return string|null
     */
    public function getSubType($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        return $param ? (isset($param['subType']) ? $param['subType'] : null) : null;
    }

    /**
     * Method to check if param is advanced
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return boolean
     */
    public function isAdvanced($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        return isset($param, $param['advanced']) ? true : false;
    }

    /**
     * Method to check if param (or some values) is not used for mobile
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return boolean|array
     */
    public function isForDesktopOnly($id, $profile = '', $strict = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        $result = false;
        if ($param) {
            if (isset($param['desktop-only'])) {
                $result = empty($param['desktop-only']) ? true : explode('|', $param['desktop-only']);
            }
        }
        return $result;
    }

    /**
     * Method to set param's subtype
     *
     * @param string $id      Param ID
     * @param string $subType Param subtype
     * @param string $profile Profile name
     *
     * @return void
     */
    public function setSubType($id, $subType, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (isset($this->params[$profile][$id])) {
            $this->params[$profile][$id]['subType'] = $subType;
        } elseif (isset($this->params[$this->generalProfile][$id])) {
            $this->params[$profile][$id] = $this->params[$this->generalProfile][$id];
            $this->params[$profile][$id]['subType'] = $subType;
        } //else param not exists
    }

    /**
     * Method to get scope or param's scope
     *
     * @param string  $id      Param ID
     * @param string  $profile Profile name
     * @param boolean $strict  Flag; whether to check the general profile or no
     *
     * @return string|null
     */
    public function getScope($id = null, $profile = '', $strict = false)
    {
        if ($id == null) {
            return $this->scope;
        }
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $param = $this->getParam($id, $profile, $strict);
        return $param ? $param['scope'] : null;
    }

    /**
     * Method to load params from INI file
     *
     * @param string $file    Path to INI file
     * @param string $profile Profile name
     *
     * @return boolean
     */
    public function loadINI($file, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (!file_exists($file)) {
            return false;
        }
        $ini = file($file);
        foreach ($ini as $num => $line) {
            $line = trim($line);
            if (empty($line) || in_array(substr($line, 0, 1), [';','#'])) {
                continue;
            }
            $cur = explode('=', $line, 2);
            if (count($cur) != 2) {
                continue;
            }
            $this->setValue(trim($cur[0]), trim($cur[1]), $profile);
        }
        return true;
    }

    /**
     * Method to update INI file
     *
     * @param string $file    Path to INI file
     * @param array  $params  Params
     * @param string $profile Profile name
     *
     * @return boolean
     */
    public function updateINI($file, $params = null, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        if (!file_exists($file)) {
            return false;
        }
        $iniLines = file($file);
        $iniParams = [];
        foreach ($iniLines as $num => $line) {
            $line = trim($line);
            if (empty($line) || in_array(substr($line, 0, 1), [';','#'])) {
                continue;
            }
            list($id, $value) = explode('=', $line, 2);
            $id = trim($id);
            $iniParams[$id] = $num;
        }
        if ($params === null) {
            $params = array_keys($this->params[$profile]);
        }

        foreach ($params as $id) {
            if (isset($iniParams[$id])) {
                $iniLines[$iniParams[$id]] = $id.' = '.$this->getValue($id, $profile)."\n";
            } else {
                $line = "\n";
                if (isset($this->params[$profile][$id]['label'])) {
                    $line .= '# '.$this->params[$profile][$id]['label']."\n";
                }
                if (isset($this->params[$profile][$id]['description'])) {
                    $line .= '# '.$this->params[$profile][$id]['description']."\n";
                }
                if (isset($this->params[$profile][$id]['values'])) {
                    $line .= '# allowed values: ';
                    for ($i = 0, $l = count($this->params[$profile][$id]['values']); $i < $l; $i++) {
                        $line .= $this->params[$profile][$id]['values'][$i];
                        if ($i < $l - 1) {
                            $line .= ', ';
                        }
                    }
                    $line .= "\n";
                }
                $iniLines[] = $line.$id.' = '.$this->getValue($id, $profile)."\n";
            }
        }
        file_put_contents($file, implode('', $iniLines));

        return true;
    }

    /**
     * Method to set mapping
     *
     * @param array  $mapping  Mapping
     *
     * @return void
     */
    public function setMapping($mapping = [])
    {
        $this->mapping = $mapping;
    }

    /**
     * Method to get mapping
     *
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * Method to add mapping
     *
     * @param string $key      Param ID
     * @param array  $mapping  Mapping
     *
     * @return void
     */
    public function addMapping($key, $mapping = [])
    {
        $this->mapping[$key] = $mapping;
    }

    /**
     * Method to remove mapping
     *
     * @param string $key      Param ID
     *
     * @return void
     */
    public function removeMapping($key)
    {
        if (isset($this->mapping[$key])) {
            unset($this->mapping[$key]);
        }
    }

    /**
     * Method to serialize params
     *
     * @param boolean $script    Flag; serialize for script or for attribute
     * @param string  $delimiter Delimiter
     * @param string  $profile   Profile name
     *
     * @return string
     */
    public function serialize($script = false, $delimiter = '', $profile = '', $mobile = false)
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        $serializeAll = $profile == $this->generalProfile;
        $str = [];
        foreach ($this->getParams($this->generalProfile) as $param) {
            if (!isset($param['scope']) || ($param['scope'] != $this->scope)) {
                continue;
            }
            if ($serializeAll) {
                if ($mobile && $this->checkMobileValue($param['id'], $this->getValue($param['id'], $this->generalProfile), $profile, true)) {
                    continue;
                }
            } else {
                if (!$this->paramExists($param['id'], $profile)) {
                    continue;
                }
                if ($mobile) {
                    if ($this->checkMobileValue($param['id'], $this->getMobileValue($param['id'], $this->generalProfile, true), $profile, true)) {
                        //NOTE: if data-mobile-options value is equal to mzMobileOptions value
                        //      we need to display it if it is different from data-options value
                        if ($this->checkMobileValue($param['id'], $this->getValue($param['id'], $profile, true), $profile, true)) {
                            continue;
                        }
                    }
                } elseif ($this->checkValue($param['id'], $this->getValue($param['id'], $this->generalProfile), $profile)) {
                    continue;
                }
            }
            if ($mobile) {
                $value = $this->getMobileValue($param['id'], $profile, true);
                if ($value == null) {
                    continue;
                }
            } else {
                $value = $this->getValue($param['id'], $profile);
            }
            if (isset($this->mapping[$param['id']])) {
                if (is_array($this->mapping[$param['id']])) {
                    if (array_key_exists($value, $this->mapping[$param['id']])) {
                        $value = $this->mapping[$param['id']][$value];
                    }
                } else {
                    // lambda-style function
                    $value = $this->mapping[$param['id']]($this);
                }
                //add possibility to skip some parameters with a specific value
                if ($value === null) {
                    continue;
                }
            }
            if ($script) {
                switch ($param['type']) {
                    case 'num':
                        //TODO: spike for the parameters that can be numeric or string
                        //if (strpos($value, '%') !== false) {
                        //    $value = '\''.$value.'\'';
                        //    break;
                        //}
                        // no break
                    case 'float':
                        //NOTE: numeric and float values without quotes
                        if ($value != 'auto') {
                            break;
                        }
                        // no break
                    case 'text':
                        // NOTE: for magicscroll 'items' param: value can be an array
                        if ($param['id'] == 'items' && strpos($value, '[') !== false) {
                            break;
                        }
                        //NOTE: escape single quotes
                        $value = '\''.str_replace('\'', '\\\'', $value).'\'';//preg_replace('/(?<!\\\\)\'/s', '\\\'', $value)
                        break;
                    case 'array':
                        if (/*$param['id'] != 'right-click' && */in_array($value, ['false', 'true'])) {
                            break;
                        }
                        // no break
                    default:
                        $value = '\''.$value.'\'';
                }
                $str[] = '\''.$param['id'].'\':'.$value;
            } else {
                // rel
                $str[] = $param['id'].':'.$value;
            }
        }
        if (empty($str)) {
            $str = '';
        } else {
            if (!$delimiter) {
                $delimiter = $script ? ',' : ';';
            }
            $str = implode($delimiter, $str);
            if (!$script) {
                $str .= $delimiter;
            }
        }

        return $str;
    }

    /**
     * Method to unserialize params
     *
     * @param string  $str     Params string
     * @param string  $profile Profile name
     *
     * @return boolean
     */
    public function unserialize($str, $profile = '')
    {
        if (!$profile) {
            $profile = $this->currentProfile;
        }
        //script version
        //preg_match_all("/'([a-z_\-]+)':'?([^;']*)'?/ui", $str, $matches);
        //rel version
        preg_match_all("/([a-z_\-]+):([^;']*)/ui", $str, $matches);
        if (count($matches[1]) > 0) {
            $options = array_combine($matches[1], $matches[2]);
            foreach ($options as $name => $value) {
                $this->setValue($name, $value, $profile);
            }
            return true;
        }
        return false;
    }
}
