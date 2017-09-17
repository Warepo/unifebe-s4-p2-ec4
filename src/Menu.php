<?php

// namespace

/**
 * Menu class.
 */
class Menu
{
    /**
     * @property {array} options : The options array.
     */
    private $options = [];
    private $options_map = [];
    /**
     * @property {\League\CLImate\CLImate} cli : The CLI input/output manager class.
     */
    private $cli = null;

    public function __construct(array $options)
    {
        $this->options = $options;
        $this->map_options();
    }

    /**
     * Creates an numeric index array, that stores its relative option assoc index.
     */
    private function map_options()
    {
        $count = 0;
        foreach ($this->options as $key => $value) {
            ++$count;
            $this->options_map[$count] = $key;
        }
    }

    /**
     * Returns the option name based on its number (not index).
     */
    private function getOptionNameByNumber(int $option_number) : string
    {
        return $this->options_map[$option_number];
    }

    /**
     * Just a little lazy.
     * @return {\League\CLImate\CLImate} A CLI manager class.
     */
    public function getCLI() : \League\CLImate\CLImate
    {
        return $this->cli ?? new \League\CLImate\CLImate;
    }

    public function prompt() : string
    {
        $this->render_menu();

        $choosen = (int) $this->getCLI()->input("Escolha o número de uma opção: ")->prompt();

        if ($choosen > 0 && $choosen <= count($this->options)) {
            return $this->getOptionNameByNumber($choosen);
        } else {
            return $this->prompt();
        }
    }

    public function render_menu()
    {
        $this->getCLI()->backgroundWhite()->black()->bold("\n\tMenu\t\n");

        $this->render_options($this->options);

        echo "\n";
    }

    private function render_options(array $options)
    {
        $count = 0;

        foreach ($options as $key => $value) {
            ++$count;
            $this->getCLI()->out($count.'. '.$value);
        }
    }
}
