<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 08-March-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Document\Properties as DocumentProperties;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Security\XmlScanner;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Settings;

class Properties
{
    private $securityScanner;

    private $docProps;

    public function __construct(XmlScanner $securityScanner, DocumentProperties $docProps)
    {
        $this->securityScanner = $securityScanner;
        $this->docProps = $docProps;
    }

    private function extractPropertyData($propertyData)
    {
        return simplexml_load_string(
            $this->securityScanner->scan($propertyData),
            'SimpleXMLElement',
            Settings::getLibXmlLoaderOptions()
        );
    }

    public function readCoreProperties($propertyData)
    {
        $xmlCore = $this->extractPropertyData($propertyData);

        if (is_object($xmlCore)) {
            $xmlCore->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
            $xmlCore->registerXPathNamespace('dcterms', 'http://purl.org/dc/terms/');
            $xmlCore->registerXPathNamespace('cp', 'http://schemas.openxmlformats.org/package/2006/metadata/core-properties');

            $this->docProps->setCreator((string) self::getArrayItem($xmlCore->xpath('dc:creator')));
            $this->docProps->setLastModifiedBy((string) self::getArrayItem($xmlCore->xpath('cp:lastModifiedBy')));
            $this->docProps->setCreated(strtotime(self::getArrayItem($xmlCore->xpath('dcterms:created')))); //! respect xsi:type
            $this->docProps->setModified(strtotime(self::getArrayItem($xmlCore->xpath('dcterms:modified')))); //! respect xsi:type
            $this->docProps->setTitle((string) self::getArrayItem($xmlCore->xpath('dc:title')));
            $this->docProps->setDescription((string) self::getArrayItem($xmlCore->xpath('dc:description')));
            $this->docProps->setSubject((string) self::getArrayItem($xmlCore->xpath('dc:subject')));
            $this->docProps->setKeywords((string) self::getArrayItem($xmlCore->xpath('cp:keywords')));
            $this->docProps->setCategory((string) self::getArrayItem($xmlCore->xpath('cp:category')));
        }
    }

    public function readExtendedProperties($propertyData)
    {
        $xmlCore = $this->extractPropertyData($propertyData);

        if (is_object($xmlCore)) {
            if (isset($xmlCore->Company)) {
                $this->docProps->setCompany((string) $xmlCore->Company);
            }
            if (isset($xmlCore->Manager)) {
                $this->docProps->setManager((string) $xmlCore->Manager);
            }
        }
    }

    public function readCustomProperties($propertyData)
    {
        $xmlCore = $this->extractPropertyData($propertyData);

        if (is_object($xmlCore)) {
            foreach ($xmlCore as $xmlProperty) {
                /** @var \SimpleXMLElement $xmlProperty */
                $cellDataOfficeAttributes = $xmlProperty->attributes();
                if (isset($cellDataOfficeAttributes['name'])) {
                    $propertyName = (string) $cellDataOfficeAttributes['name'];
                    $cellDataOfficeChildren = $xmlProperty->children('http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes');

                    $attributeType = $cellDataOfficeChildren->getName();
                    $attributeValue = (string) $cellDataOfficeChildren->{$attributeType};
                    $attributeValue = DocumentProperties::convertProperty($attributeValue, $attributeType);
                    $attributeType = DocumentProperties::convertPropertyType($attributeType);
                    $this->docProps->setCustomProperty($propertyName, $attributeValue, $attributeType);
                }
            }
        }
    }

    private static function getArrayItem(array $array, $key = 0)
    {
        return $array[$key] ?? null;
    }
}
