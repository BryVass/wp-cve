<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 08-March-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\Matrix\Operators;

use GFExcel\Vendor\Matrix\Matrix;
use \GFExcel\Vendor\Matrix\Builder;
use GFExcel\Vendor\Matrix\Exception;

class Multiplication extends Operator
{
    /**
     * Execute the multiplication
     *
     * @param mixed $value The matrix or numeric value to multiply the current base value by
     * @throws Exception If the provided argument is not appropriate for the operation
     * @return $this The operation object, allowing multiple multiplications to be chained
     **/
    public function execute($value)
    {
        if (is_array($value)) {
            $value = new Matrix($value);
        }

        if (is_object($value) && ($value instanceof Matrix)) {
            return $this->multiplyMatrix($value);
        } elseif (is_numeric($value)) {
            return $this->multiplyScalar($value);
        }

        throw new Exception('Invalid argument for multiplication');
    }

    /**
     * Execute the multiplication for a scalar
     *
     * @param mixed $value The numeric value to multiply with the current base value
     * @return $this The operation object, allowing multiple mutiplications to be chained
     **/
    protected function multiplyScalar($value)
    {
        for ($row = 0; $row < $this->rows; ++$row) {
            for ($column = 0; $column < $this->columns; ++$column) {
                $this->matrix[$row][$column] *= $value;
            }
        }

        return $this;
    }

    /**
     * Execute the multiplication for a matrix
     *
     * @param Matrix $value The numeric value to multiply with the current base value
     * @return $this The operation object, allowing multiple mutiplications to be chained
     * @throws Exception If the provided argument is not appropriate for the operation
     **/
    protected function multiplyMatrix(Matrix $value)
    {
        $this->validateReflectingDimensions($value);

        $newRows = $this->rows;
        $newColumns = $value->columns;
        $matrix = Builder::createFilledMatrix(0, $newRows, $newColumns)
            ->toArray();
        for ($row = 0; $row < $newRows; ++$row) {
            for ($column = 0; $column < $newColumns; ++$column) {
                $columnData = $value->getColumns($column + 1)->toArray();
                foreach ($this->matrix[$row] as $key => $valueData) {
                    $matrix[$row][$column] += $valueData * $columnData[$key][0];
                }
            }
        }
        $this->matrix = $matrix;

        return $this;
    }
}
