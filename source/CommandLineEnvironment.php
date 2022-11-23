<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2015-10-10
 */

namespace Net\Bazzline\Component\Cli\Environment;

use Exception;
use InvalidArgumentException;
use Net\Bazzline\Component\Cli\Arguments\Arguments;
use Net\Bazzline\Component\Toolbox\Scalar\Text;

class CommandLineEnvironment
{
    private Arguments $arguments;
    private bool $beVerbose;
    /** @var Callable */
    private $executeExceptionHandler;
    private Text $text;

    public function __construct(array $argv)
    {
        $this->arguments    = new Arguments($argv);
        $this->text         = new Text();
        $this->determineIfWeAreVerbose($this->arguments);
        $this->setDefaultExecuteExceptionHandler();
    }

    public function beVerbose(): bool
    {
        return $this->beVerbose;
    }

    /**
     * $instruction is called with argument CommandLineEnvironment
     * \Exception thrown by $instruction are handled
     *
     * @param Callable $instruction
     * @param string $usage
     * @throws InvalidArgumentException
     */
    public function execute(callable $instruction, string $usage): void
    {
        try {
            $this->throwInvalidArgumentExceptionIfNoCallableProvided($instruction);
            $instruction($this);
        } catch (Exception $exception) {
            $handler = $this->executeExceptionHandler;
            $handler($exception, $usage);
        }
    }

    public function getArguments(): Arguments
    {
        return $this->arguments;
    }

    public function getText(): Text
    {
        return $this->text;
    }

    /**
     * Handler is called with arguments:
     *  \Exception $exception
     *  string $usage
     *
     * @param callable $handler
     * @throws InvalidArgumentException
     */
    public function overwriteDefaultExecuteExceptionHandler(callable $handler): void
    {
        $this->throwInvalidArgumentExceptionIfNoCallableProvided($handler);
        $this->executeExceptionHandler = $handler;
    }

    public function output(float|array|int|string $scalarOrArrayOfScalars): void
    {
        if (is_scalar($scalarOrArrayOfScalars)) {
            $scalarOrArrayOfScalars = [
                $scalarOrArrayOfScalars
            ];
        }

        if (!is_array($scalarOrArrayOfScalars)) {
            throw new InvalidArgumentException(
                'provided argument must be scalar or array'
            );
        }

        echo implode(PHP_EOL, $scalarOrArrayOfScalars) . PHP_EOL;
    }

    public function outputIfVerbosityIsEnabled(float|array|int|string $scalarOrArrayOfScalars): void
    {
        if ($this->beVerbose) {
            $this->output($scalarOrArrayOfScalars);
        }
    }

    private function determineIfWeAreVerbose(Arguments $arguments): void
    {
        $this->beVerbose = ($arguments->hasFlag('verbose') || $arguments->hasFlag('v'));
    }

    private function setDefaultExecuteExceptionHandler(): void
    {
        $environment                    = $this;
        $this->executeExceptionHandler  = function (Exception $exception, $usage) use ($environment) {
            $environment->output(
                [
                    'An error occurred',
                    $exception->getMessage(),
                    '',
                    'Usage: ' . $usage
                ]
            );
            exit(1);
        };
    }

    /**
     * @param Callable $callable
     * @throws InvalidArgumentException
     */
    private function throwInvalidArgumentExceptionIfNoCallableProvided(callable $callable): void
    {
        if (!is_callable($callable)) {
            throw new InvalidArgumentException(
                'no callable provided'
            );
        }
    }
}
