<?php

declare(strict_types=1);

namespace YamlStandards\Command;

use Symfony\Component\Console\Input\InputInterface;

class InputSettingData
{
    /**
     * @var string[]
     */
    private $pathToDirsOrFiles;

    /**
     * @var string[]
     */
    private $excludedFileMasks;

    /**
     * @var string|null
     */
    private $alphabeticalSortDepth;

    /**
     * @var string|null
     */
    private $countOfIndents;

    /**
     * @var bool
     */
    private $inlineStandard;

    /**
     * @var string|null
     */
    private $levelForCheckSpacesBetweenGroups;

    /**
     * @var string[]
     */
    private $excludedPaths;

    /**
     * @var bool
     */
    private $fixEnabled;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->pathToDirsOrFiles = $input->getArgument(YamlCommand::ARGUMENT_DIRS_OR_FILES);
        $this->excludedFileMasks = $input->getOption(YamlCommand::OPTION_EXCLUDE_BY_NAME);
        $this->alphabeticalSortDepth = $input->getOption(YamlCommand::OPTION_CHECK_ALPHABETICAL_SORT_DEPTH);
        $this->countOfIndents = $input->getOption(YamlCommand::OPTION_CHECK_YAML_COUNT_OF_INDENTS);
        $this->inlineStandard = $input->getOption(YamlCommand::OPTION_CHECK_INLINE);
        $this->levelForCheckSpacesBetweenGroups = $input->getOption(YamlCommand::OPTION_CHECK_LEVEL_FOR_SPACES_BETWEEN_GROUPS);

        $this->fixEnabled = $input->getOption(YamlCommand::OPTION_FIX);

        $excludedPathToDirs = $input->getOption(YamlCommand::OPTION_EXCLUDE_DIR);
        $excludedPathToFiles = $input->getOption(YamlCommand::OPTION_EXCLUDE_FILE);
        $this->excludedPaths = array_merge($excludedPathToDirs, $excludedPathToFiles);
    }

    /**
     * @return string[]
     */
    public function getPathToDirsOrFiles(): array
    {
        return $this->pathToDirsOrFiles;
    }

    /**
     * @return string[]
     */
    public function getExcludedFileMasks(): array
    {
        return $this->excludedFileMasks;
    }

    /**
     * @return string[]
     */
    public function getExcludedPaths(): array
    {
        return $this->excludedPaths;
    }

    /**
     * @return int|null
     */
    public function getAlphabeticalSortDepth(): ?int
    {
        return $this->alphabeticalSortDepth === null ? null : (int)$this->alphabeticalSortDepth;
    }

    /**
     * @return int|null
     */
    public function getCountOfIndents(): ?int
    {
        return $this->countOfIndents === null ? null : (int)$this->countOfIndents;
    }

    /**
     * @return bool
     */
    public function checkInlineStandard(): bool
    {
        return $this->inlineStandard;
    }

    /**
     * @return int|null
     */
    public function getLevelForCheckSpacesBetweenGroups(): ?int
    {
        return $this->levelForCheckSpacesBetweenGroups === null ? null : (int)$this->levelForCheckSpacesBetweenGroups;
    }

    /**
     * @return bool
     */
    public function isFixEnabled(): bool
    {
        return $this->fixEnabled;
    }
}
