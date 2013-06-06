<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-06-05 
 */

namespace Net\Bazzline\Symfony\Console\Application;

use Net\Bazzline\Symfony\Console\IO\ConsoleIOFactory;
use Net\Bazzline\Symfony\Console\IO\ConsoleIO;
use Net\Bazzline\Symfony\Console\IO\IOAwareInterface;
use Net\Bazzline\Symfony\Console\IO\IOInterface;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application
 *
 * @package Net\Bazzline\Symfony\Console\Application
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-06-05
 */
class Application extends SymfonyApplication implements IOAwareInterface
{
    /**
     * @var ConsoleIO
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-06-05
     */
    protected $io;

    /*
     * {@inheritDoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {

        $ioFactory = ConsoleIOFactory::buildDefault($input, $output, $this->getHelperSet());
        $this->io = $ioFactory->get();

        return parent::doRun($input, $output);
    }

    /**
     * {@inheritDoc}
     */
    public function get($name)
    {
        $command = parent::get($name);

        if (!is_null($this->io)
            && $command instanceof IOAwareInterface) {
            $command->setIO($this->io);
        }

        return $command;
    }

    /**
     * {@inheritDoc}
     */
    public function all($namespace = null)
    {
        $commands = parent::all($namespace);

        foreach ($commands as $command) {
            if (!is_null($this->io)
                && $command instanceof IOAwareInterface) {
                $command->setIO($this->io);
            }
        }

        return $commands;
    }

    /**
     * {@inheritDoc}
     */
    public function add(Command $command)
    {
        if (!is_null($this->io)
            && $command instanceof IOAwareInterface) {
            $command->setIO($this->io);
        }

        return parent::add($command);
    }

    /**
     * Copied from: https://github.com/jenswiese/phpteda/blob/master/src/Phpteda/CLI/Application.php
     *
     * @return null|int
     * @author Jens Wiese <jens@howtrueisfalse.de>
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-06-05
     */
    public function getTerminalWidth()
    {
        $dimensions = $this->getTerminalDimensions();

        return $dimensions[0];
    }

    /**
     * @return \Net\Bazzline\Symfony\Console\IO\IOInterface
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-05-31
     */
    public function getIO()
    {
        return $this->io;
    }

    /**
     * @param \Net\Bazzline\Symfony\Console\IO\IOInterface $io
     * @return Application
     *
     * @author stev leibelt <artodeto@arcor.de>
     * @since 2013-05-31
     */
    public function setIO(IOInterface $io)
    {
        $this->io = $io;

        return $this;
    }
}
