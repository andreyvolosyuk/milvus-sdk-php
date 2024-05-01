<?php


namespace Volosyuk\MilvusPhp\Client;


use Milvus\Proto\Schema\FieldData as FieldDataGRPC;
use Milvus\Proto\Schema\SearchResultData;
use TypeError;
use Volosyuk\MilvusPhp\ORM\Schema\FieldData;
use function Volosyuk\MilvusPhp\ORM\Schema\idsToRepeatedField;
use function Volosyuk\MilvusPhp\ORM\Schema\repeatedFieldSlice;
use function Volosyuk\MilvusPhp\ORM\Schema\repeatedFieldToArray;

class Hits extends ArrayLike
{
    public function __construct(SearchResultData $raw, bool $autoId, int $roundDecimal = -1)
    {
        $this->pack($raw);
    }

    private function pack(SearchResultData $raw)
    {
        $scores = $raw->getScores();
        $ids = repeatedFieldToArray(idsToRepeatedField($raw->getIds()));
        $cnt = $scores->count();
        $fieldsData = [];

        /**
         * @var FieldDataGRPC $fieldData
         */
        foreach ($raw->getFieldsData()->getIterator() as $fieldData) {
            $rawData = FieldData::rawToData($fieldData);
            try {
                $fieldsData[$fieldData->getFieldName()] = repeatedFieldToArray($rawData);
            } catch (TypeError $e) {
                $fieldsData[$fieldData->getFieldName()] = $rawData;
            }
        }

        for ($i = 0; $i < $cnt; $i++) {
            $hitsData = [];

            foreach ($fieldsData as $fieldKey => $fieldsDatum) {
                $hitsData[$fieldKey] = $fieldsDatum[$i];
            }

            $this->append(new Hit($ids[$i], $hitsData, $scores[$i]));
        }
    }
}
