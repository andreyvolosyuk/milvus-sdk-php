<?php


namespace Volosyuk\MilvusPhp\Client;

use Milvus\Proto\Milvus\SearchResults;
use Milvus\Proto\Schema\FieldData as FieldDataGRPC;
use Milvus\Proto\Schema\IDs;
use Milvus\Proto\Schema\LongArray;
use Milvus\Proto\Schema\SearchResultData;
use Milvus\Proto\Schema\StringArray;
use Volosyuk\MilvusPhp\Exceptions\ParamException;
use Volosyuk\MilvusPhp\ORM\Schema\FieldData;
use function Volosyuk\MilvusPhp\ORM\Schema\idsToRepeatedField;
use function Volosyuk\MilvusPhp\ORM\Schema\repeatedFieldSlice;

class ChunkedQueryResult extends ArrayLike
{
    /**
     * @var array|SearchResults[]
     */
    private $rawList;

    /**
     * @var bool
     */
    private $autoId;

    /**
     * @var int
     */
    private $roundDecimal;

    /**
     * @var int
     */
    private $nq;

    /**
     * @param array|SearchResults[] $rawList
     * @param bool $autoId
     * @param int $roundDecimal
     *
     * @throws ParamException
     */
    public function __construct(array $rawList, bool $autoId = true, int $roundDecimal = -1)
    {
        $this->rawList = $rawList;
        $this->autoId = $autoId;
        $this->roundDecimal = $roundDecimal;
        $this->nq = 0;

        $this->pack();
    }

    /**
     * @throws ParamException
     */
    private function pack()
    {
        /**
         * @var SearchResults $rawItem
         */
        foreach ($this->rawList as $rawItem) {
            $results = $rawItem->getResults();
            $nq = $results->getNumQueries();
            $this->nq += $nq;
            $topK = $results->getTopK();
            $idsData = idsToRepeatedField($results->getIds());
            $offset = 0;

            for ($i = 0; $i < $nq; $i++) {
                $startPos = $offset;
                $length =  $results->getTopks()[$i];

                $ids = new IDs();
                $idsSlice = repeatedFieldSlice($idsData, $startPos, $length);

                if ($results->getIds()->getIntId()) {
                    $longArray = (new LongArray())->setData($idsSlice);
                    $ids->setIntId($longArray);
                } elseif ($results->getIds()->getStrId()) {
                    $stringArray = (new StringArray())->setData($idsSlice);
                    $ids->setStrId($stringArray);
                }

                $fieldsData = [];
                /**
                 * @var $fieldsDatum FieldDataGRPC
                 */
                foreach ($results->getFieldsData()->getIterator() as $fieldsDatum) {
                    $data = FieldData::rawToData($fieldsDatum);
                    $dim = FieldData::rawToDim($fieldsDatum);
                    $appliedDim = $dim ?? 1;

                    $fieldsData[] = (new FieldData(
                        $fieldsDatum->getFieldName(),
                        $fieldsDatum->getType(),
                        repeatedFieldSlice($data, $startPos * $appliedDim, $length * $appliedDim),
                        $dim
                    ))->toRaw();
                }

                $this->append(
                    (new SearchResultData())
                        ->setScores(repeatedFieldSlice(
                            $results->getScores(),
                            $startPos,
                            $length
                        ))
                        ->setIds($ids)
                        ->setFieldsData($fieldsData)
                );
                $offset += $topK;
            }
        }
    }

    /**
     * @param SearchResultData $item
     *
     * @return Hits
     */
    protected function processItemBeforeOutput($item): Hits
    {
        return new Hits(
            $item,
            $this->autoId,
            $this->roundDecimal
        );
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->nq;
    }
}