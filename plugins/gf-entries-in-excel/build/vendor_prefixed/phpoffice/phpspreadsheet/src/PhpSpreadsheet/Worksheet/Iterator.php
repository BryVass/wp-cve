<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 08-March-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Spreadsheet;

class Iterator implements \Iterator
{
    /**
     * Spreadsheet to iterate.
     *
     * @var Spreadsheet
     */
    private $subject;

    /**
     * Current iterator position.
     *
     * @var int
     */
    private $position = 0;

    /**
     * Create a new worksheet iterator.
     *
     * @param Spreadsheet $subject
     */
    public function __construct(Spreadsheet $subject)
    {
        // Set subject
        $this->subject = $subject;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->subject);
    }

    /**
     * Rewind iterator.
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Current Worksheet.
     *
     * @return Worksheet
     */
    public function current()
    {
        return $this->subject->getSheet($this->position);
    }

    /**
     * Current key.
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Next value.
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Are there more Worksheet instances available?
     *
     * @return bool
     */
    public function valid()
    {
        return $this->position < $this->subject->getSheetCount() && $this->position >= 0;
    }
}
