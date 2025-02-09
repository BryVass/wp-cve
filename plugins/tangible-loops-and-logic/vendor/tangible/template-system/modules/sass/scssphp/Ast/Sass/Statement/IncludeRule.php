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

namespace Tangible\ScssPhp\Ast\Sass\Statement;

use Tangible\ScssPhp\Ast\Sass\ArgumentInvocation;
use Tangible\ScssPhp\Ast\Sass\CallableInvocation;
use Tangible\ScssPhp\Ast\Sass\SassReference;
use Tangible\ScssPhp\Ast\Sass\Statement;
use Tangible\ScssPhp\SourceSpan\FileSpan;
use Tangible\ScssPhp\Util\SpanUtil;
use Tangible\ScssPhp\Visitor\StatementVisitor;

/**
 * A mixin invocation.
 *
 * @internal
 */
final class IncludeRule implements Statement, CallableInvocation, SassReference
{
    private readonly ?string $namespace;

    private readonly string $name;

    private readonly ArgumentInvocation $arguments;

    private readonly ?ContentBlock $content;

    private readonly FileSpan $span;

    public function __construct(string $name, ArgumentInvocation $arguments, FileSpan $span, ?string $namespace = null, ?ContentBlock $content = null)
    {
        $this->name = $name;
        $this->arguments = $arguments;
        $this->span = $span;
        $this->namespace = $namespace;
        $this->content = $content;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArguments(): ArgumentInvocation
    {
        return $this->arguments;
    }

    public function getContent(): ?ContentBlock
    {
        return $this->content;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function getSpanWithoutContent(): FileSpan
    {
        if ($this->content === null) {
            return $this->span;
        }

        return SpanUtil::trim($this->span->getFile()->span($this->span->getStart()->getOffset(), $this->arguments->getSpan()->getEnd()->getOffset()));
    }

    public function getNameSpan(): FileSpan
    {
        $startSpan = $this->span->getText()[0] === '+' ? SpanUtil::trimLeft($this->span->subspan(1)) : SpanUtil::withoutInitialAtRule($this->span);

        if ($this->namespace !== null) {
            $startSpan = SpanUtil::withoutNamespace($startSpan);
        }

        return SpanUtil::initialIdentifier($startSpan);
    }

    public function getNamespaceSpan(): ?FileSpan
    {
        if ($this->namespace === null) {
            return null;
        }

        $startSpan = $this->span->getText()[0] === '+'
            ? SpanUtil::trimLeft($this->span->subspan(1))
            : SpanUtil::withoutInitialAtRule($this->span);

        return SpanUtil::initialIdentifier($startSpan);
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitIncludeRule($this);
    }

    public function __toString(): string
    {
        $buffer = '@include ';

        if ($this->namespace !== null) {
            $buffer .= $this->namespace . '.';
        }
        $buffer .= $this->name;

        if (!$this->arguments->isEmpty()) {
            $buffer .= "($this->arguments)";
        }

        $buffer .= $this->content === null ? ';' : ' ' . $this->content;

        return $buffer;
    }
}
