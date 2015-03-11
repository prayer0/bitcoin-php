<?php

namespace Afk11\Bitcoin\Serializer\Block;

use Afk11\Bitcoin\Block\Block;
use Afk11\Bitcoin\Exceptions\ParserOutOfRange;
use Afk11\Bitcoin\Parser;
use Afk11\Bitcoin\Block\BlockInterface;
use Afk11\Bitcoin\Serializer\Transaction\TransactionCollectionSerializer;

class HexBlockSerializer
{
    /**
     * @var HexBlockHeaderSerializer
     */
    protected $headerSerializer;

    /**
     * @param HexBlockHeaderSerializer $headerSerializer
     * @param TransactionCollectionSerializer $txColSerializer
     */
    public function __construct(HexBlockHeaderSerializer $headerSerializer, TransactionCollectionSerializer $txColSerializer)
    {
        $this->headerSerializer = $headerSerializer;
        $this->txColSerializer = $txColSerializer;
    }

    /**
     * @param Parser $parser
     * @return Block
     * @throws ParserOutOfRange
     */
    public function fromParser(Parser &$parser)
    {
        try {
            $block = new Block();
            $block->setHeader($this->headerSerializer->fromParser($parser));
            $block->setTransactions($this->txColSerializer->fromParser($parser));
        } catch (ParserOutOfRange $e) {
            throw new ParserOutOfRange('Failed to extract full block header from parser');
        }

        return $block;
    }

    /**
     * @param $string
     * @return Block
     * @throws ParserOutOfRange
     */
    public function parse($string)
    {
        $parser = new Parser($string);
        $block = $this->fromParser($parser);
        return $block;
    }

    /**
     * @param BlockInterface $block
     * @return string
     */
    public function serialize(BlockInterface $block)
    {
        $header = $block->getHeader()->getBuffer();
        $parser = new Parser($header);
        $serializedTxs = $this->txColSerializer->serialize($block->getTransactions());
        $parser->writeArray($serializedTxs);
        return $parser->getBuffer();
    }
}
