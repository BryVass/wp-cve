<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace Tangible\ScssPhp\Ast\Sass\Expression;

use Tangible\ScssPhp\Ast\Sass\Expression;
use Tangible\ScssPhp\Ast\Sass\SassReference;
use Tangible\ScssPhp\SourceSpan\FileSpan;
use Tangible\ScssPhp\Util\SpanUtil;
use Tangible\ScssPhp\Visitor\ExpressionVisitor;

/**
 * A Sass variable.
 *
 * @internal
 */
final class VariableExpression implements Expression, SassReference
{
    /**
     * The name of this variable, with underscores converted to hyphens.
     */
    private readonly string $name;

    /**
     * The namespace of the variable being referenced, or `null` if it's
     * referenced without a namespace.
     */
    private ?string $namespace;

    private readonly FileSpan $span;

    public function __construct(string $name, FileSpan $span, ?string $namespace = null)
    {
        $this->span = $span;
        $this->name = $name;
        $this->namespace = $namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function getNameSpan(): FileSpan
    {
        if ($this->namespace === null) {
            return $this->span;
        }

        return SpanUtil::withoutNamespace($this->span);
    }

    public function getNamespaceSpan(): ?FileSpan
    {
        if ($this->namespace === null) {
            return null;
        }

        return SpanUtil::initialIdentifier($this->span);
    }

    public function accept(ExpressionVisitor $visitor)
    {
        return $visitor->visitVariableExpression($this);
    }

    public function __toString(): string
    {
        if ($this->namespace === null) {
            return '$' . $this->name;
        }

        return $this->namespace . '$' . $this->name;
    }
}
