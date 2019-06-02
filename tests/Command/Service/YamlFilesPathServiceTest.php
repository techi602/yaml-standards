<?php

namespace YamlStandards\Command\Service;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\Output\NullOutput;
use YamlStandards\Command\InputSettingData;

class YamlFilesPathServiceTest extends TestCase
{
    public function testFindAllYamlFilesInDir()
    {
        $pathToDir = ['./tests/yamlFiles/unSorted/'];
        $excludedPaths = [];
        $inputSettingData = $this->getInputSettingDataMock($pathToDir, $excludedPaths);

        $yamlFiles = YamlFilesPathService::getPathToYamlFiles($inputSettingData, new NullOutput());

        $this->assertCount(6, $yamlFiles);
    }

    public function testFindAllYamlFilesInDirExceptOfExcludedDirs()
    {
        $pathToDir = ['./tests/yamlFiles/'];
        $excludedPaths = [
            './tests/yamlFiles/sorted/config',
            './tests/yamlFiles/unSorted/route',
        ];
        $inputSettingData = $this->getInputSettingDataMock($pathToDir, $excludedPaths);

        $yamlFiles = YamlFilesPathService::getPathToYamlFiles($inputSettingData, new NullOutput());

        $this->assertCount(9, $yamlFiles);
    }

    public function testFindAllYamlFilesInDirAndExcludedFilesToo()
    {
        $pathToDir = ['./tests/yamlFiles/unSorted/'];
        $excludedPaths = ['./tests/yamlFiles/unSorted/route'];
        $inputSettingData = $this->getInputSettingDataMock($pathToDir, $excludedPaths);

        $yamlFiles = YamlFilesPathService::getPathToYamlFiles($inputSettingData, new NullOutput(), true);

        $this->assertCount(6, $yamlFiles);
    }

    public function testFindAllYamlFilesInDirExceptOfExcludedFiles()
    {
        $pathToDir = ['./tests/yamlFiles/'];
        $excludedPaths = [
            './tests/yamlFiles/sorted/config/symfony-config.yml',
            './tests/yamlFiles/unSorted/service/shopsys-service.yml',
        ];
        $inputSettingData = $this->getInputSettingDataMock($pathToDir, $excludedPaths);

        $yamlFiles = YamlFilesPathService::getPathToYamlFiles($inputSettingData, new NullOutput());

        $this->assertCount(10, $yamlFiles);
    }

    public function testFindAllYamlFilesInDirExceptOfExcludedDirsAndFiles()
    {
        $pathToDir = ['./tests/yamlFiles/'];
        $excludedPaths = [
            './tests/yamlFiles/sorted/config',
            './tests/yamlFiles/unSorted/route',
            './tests/yamlFiles/unSorted/service/shopsys-service.yml',
            './tests/yamlFiles/unSorted/yaml-getting-started.yml',
        ];
        $inputSettingData = $this->getInputSettingDataMock($pathToDir, $excludedPaths);

        $yamlFiles = YamlFilesPathService::getPathToYamlFiles($inputSettingData, new NullOutput());

        $this->assertCount(7, $yamlFiles);
    }

    public function testFindFile()
    {
        $pathToFile = ['./tests/yamlFiles/sorted/yaml-getting-started.yml'];
        $excludedPaths = [];
        $inputSettingData = $this->getInputSettingDataMock($pathToFile, $excludedPaths);

        $yamlFiles = YamlFilesPathService::getPathToYamlFiles($inputSettingData, new NullOutput());

        $this->assertCount(1, $yamlFiles);
    }

    public function testReturnFullPathToFile()
    {
        $pathToFile = './tests/yamlFiles/unSorted/yaml-getting-started.yml';
        $excludedPaths = [];
        $inputSettingData = $this->getInputSettingDataMock([$pathToFile], $excludedPaths);

        $yamlFiles = YamlFilesPathService::getPathToYamlFiles($inputSettingData, new NullOutput());
        $foundFile = reset($yamlFiles);

        $this->assertEquals($pathToFile, $foundFile);
    }

    public function testReturnFullPathToFilesFromDir()
    {
        $pathToFile = ['./tests/yamlFiles/unSorted/config/', './tests/yamlFiles/unSorted/route/'];
        $excludedPaths = [];
        $inputSettingData = $this->getInputSettingDataMock($pathToFile, $excludedPaths);

        $yamlFiles = YamlFilesPathService::getPathToYamlFiles($inputSettingData, new NullOutput());

        $expectedYamlFiles = [
            './tests/yamlFiles/unSorted/config/symfony-config.yml',
            './tests/yamlFiles/unSorted/config/symfony-security.yml',
            './tests/yamlFiles/unSorted/route/symfony-route.yml',
        ];

        $this->assertCount(count(array_intersect($yamlFiles, $expectedYamlFiles)), $yamlFiles); // assert two arrays are equal, but order of elements not important
    }

    /**
     * @param string[] $pathToDirsOrFiles
     * @param string[] $excludedPaths
     * @return \YamlStandards\Command\InputSettingData|PHPUnit_Framework_MockObject_MockObject
     */
    private function getInputSettingDataMock(array $pathToDirsOrFiles, array $excludedPaths)
    {
        $inputSettingDataMock = $this->createMock(InputSettingData::class);
        $inputSettingDataMock->method('getPathToDirsOrFiles')->willReturn($pathToDirsOrFiles);
        $inputSettingDataMock->method('getExcludedPaths')->willReturn($excludedPaths);

        return $inputSettingDataMock;
    }
}
