<?php

declare (strict_types=1);
namespace Dotdigital_WordPress_Vendor\Dotdigital\V3\Models;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\InsightData\Record;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\InsightData\RecordsCollection;
class InsightData extends AbstractSingletonModel
{
    /**
     * @var string
     */
    protected string $collectionName;
    /**
     * @var string
     */
    protected string $collectionScope;
    /**
     * @var string
     */
    protected string $collectionType;
    /**
     * @var RecordsCollection
     */
    protected RecordsCollection $records;
    /**
     * @param $data
     * @return void
     * @throws \Exception
     */
    public function setRecords($data)
    {
        $recordsCollection = new RecordsCollection();
        foreach ($data as $array) {
            $record = new Record($array);
            $recordsCollection->add($record);
        }
        $this->records = $recordsCollection;
    }
}
