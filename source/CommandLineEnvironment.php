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
    /** @var Arguments */
    private $arguments;

    /** @var bool */
    private $beVerbose;

    /** @var Callable */
    private $executeExceptionHandler;

    /** @var Text */
    private $text;

    /**
     * @param array $argv
     */
    public function __construct($argv)
    {
        $this->arguments    = new Arguments($argv);
        $this->text         = new Text();
        $this->determineIfWeAreVerbose($this->arguments);
        $this->setDefaultExecuteExceptionHandler();
    }

    /**
     * @return bool
     */
    public function beVerbose()
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
    public function execute($instruction, $usage)
    {
        try {
            $this->throwInvalidArgumentExceptionIfNoCallableProvided($instruction);
            $instruction($this);
        } catch (Exception $exception) {
            $handler = $this->executeExceptionHandler;
            $handler($exception, $usage);
        }
    }

    /**
     * @return Arguments
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return Text
     */
    public function getText()
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
    public function overwriteDefaultExecuteExceptionHandler($handler)
    {
        $this->throwInvalidArgumentExceptionIfNoCallableProvided($handler);
        $this->executeExceptionHandler = $handler;
    }

    /**
     * @param int|float|string|array $scalarOrArrayOfScalars
     * @throws InvalidArgumentException
     */
    public function output($scalarOrArrayOfScalars)
    {
        if (is_scalar($scalarOrArrayOfScalars)) {
            $scalarOrArrayOfScalars = array($scalarOrArrayOfScalars);
        }

        if (!is_array($scalarOrArrayOfScalars)) {
            throw new InvalidArgumentException(
                'provided argument must be scalar or array'
            );
        }

        echo implode(PHP_EOL, $scalarOrArrayOfScalars) . PHP_EOL;
    }

    /**
     * @param int|float|string|array $scalarOrArrayOfScalars
     */
    public function outputIfVerbosityIsEnabled($scalarOrArrayOfScalars)
    {
        if ($this->beVerbose) {
            $this->output($scalarOrArrayOfScalars);
        }
    }

    /**
     * @param Arguments $arguments
     */
    private function determineIfWeAreVerbose(Arguments $arguments)
    {
        $this->beVerbose = ($arguments->hasFlag('verbose') || $arguments->hasFlag('v'));
    }

    private function setDefaultExecuteExceptionHandler()
    {
        $environment                    = $this;
        $this->executeExceptionHandler  = function (Exception $exception, $usage) use ($environment) {
            $environment->output(
                array(
                    'An error occurred',
                    $exception->getMessage(),
                    '',
                    'Usage: ' . $usage
		)
            );
            exit(1);
        };
    }

    /**
     * @param Callable $callable
     * @throws InvalidArgumentException
     */
    private function throwInvalidArgumentExceptionIfNoCallableProvided($callable)
    {
        if (!is_callable($callable)) {
            throw new InvalidArgumentException(
                'no callable provided'
            );
        }
    }
}
