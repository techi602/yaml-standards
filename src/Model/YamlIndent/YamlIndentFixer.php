<?php

declare(strict_types=1);

namespace YamlStandards\Model\YamlIndent;

use SebastianBergmann\Diff\Differ;
use YamlStandards\Model\AbstractFixer;
use YamlStandards\Model\Config\StandardParametersData;
use YamlStandards\Result\Result;

/**
 * Fix yaml file with right count of indent
 */
class YamlIndentFixer extends AbstractFixer
{
    /**
     * @inheritDoc
     */
    public function fix(string $pathToFile, string $pathToDumpFixedFile, StandardParametersData $standardParametersData): Result
    {
        $fileContent = file_get_contents($pathToFile);
        $fileContent = str_replace("\r", '', $fileContent); // remove carriage returns
        $fileLines = explode("\n", $fileContent);
        $yamlIndentDataFactory = new YamlIndentDataFactory();
        $rightFileLines = [];

        foreach ($fileLines as $key => $fileLine) {
            $rightFileLines[] = $yamlIndentDataFactory->getRightFileLines($fileLines, $key, $standardParametersData, $fileLine);
        }

        $rightFileContent = implode("\n", $rightFileLines);

        if ($fileContent === $rightFileContent) {
            return new Result($pathToFile, Result::RESULT_CODE_OK);
        }

        file_put_contents($pathToDumpFixedFile, $rightFileContent);

        $differ = new Differ();
        $diffBetweenStrings = $differ->diff($fileContent, $rightFileContent);

        return new Result($pathToFile, Result::RESULT_CODE_FIXED_INVALID_FILE_SYNTAX, $diffBetweenStrings);
    }
}
