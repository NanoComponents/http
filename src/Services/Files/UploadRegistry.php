<?php

namespace NanoLibs\Http\Services\Files;

use NanoLibs\Http\Exceptions\InvalidFileArrayException;
use NanoLibs\Http\Interfaces\Service\UploadedFileInterface;
use NanoLibs\Http\Interfaces\Service\UploadedFormInterface;
use NanoLibs\Http\Serializers\GlobalFileArraySerializer;

class UploadRegistry
{
    /** @var array<UploadedForm> $uploadedFieldArray */
    protected array $uploadedFieldArray = [];

    /**
     * @param array<string, array<string, mixed>> $globalArray
     * @throws InvalidFileArrayException
     */
    public function __construct(
        protected array $globalArray,
    ) {
        $serializedArray = GlobalFileArraySerializer::serialize($this->globalArray);
        foreach ($serializedArray as $fieldName => $fieldArray) {

            $form = $this->addUploadedField($fieldName);
            /**
             * @var array<string, string> $fieldArray
             */
            if ($this->handleSingleFormSingleFileUploaded($fieldArray, $form)) {
                continue;
            }
            foreach ($fieldArray as $fieldSuffix => $value) {
                /**
                 * @var array<string, string> $value
                 */
                if ($this->handleNotNestedFileUploaded($fieldSuffix, $value, $form)) {
                    continue;
                }
                /**
                 * @var array<int, array<string, string>> $value
                 */
                $this->handleNestedFileUploaded($value, $fieldSuffix, $form);
            }
        }
    }

    /**
     * @param ?string $fieldName
     * @return array<int, UploadedFileInterface>
     */
    public function getUploadedFile(?string $fieldName = null): array
    {
        $result = [];
        foreach ($this->uploadedFieldArray as $field) {
            $files = $field->getFiles();

            foreach ($files as $file) {
                if ($fieldName === $file->getFieldNameSuffix()) {
                    $result[] = $file;
                }
                $result[] = $file;
            }
        }
        return $result;
    }

    protected function addUploadedField(string $fieldName): UploadedForm
    {
        $index = array_push($this->uploadedFieldArray, new UploadedForm($fieldName));
        return $this->uploadedFieldArray[--$index];
    }

    /**
     * @param array<string, string> $fieldArray
     * @throws InvalidFileArrayException
     */
    protected function handleSingleFormSingleFileUploaded(array $fieldArray, UploadedFormInterface $form): ?bool
    {
        if ($this->isSingleFormAndSingleFileUploaded($fieldArray)) {
            $fileData = $this->extractFileData($fieldArray);
                $form->addUploadedFile(new UploadedFile(
                    $fileData['name'],
                    $fileData['full_path'],
                    $fileData['type'],
                    $fileData['tmp_name'],
                    $fileData['error'],
                    $fileData['size'],
                    $form->getFormName()
                ));
            return true;
        }
        return false;
    }

    /**
     * @param string|int $fieldSuffix
     * @param array<string, string> $value
     * @param UploadedFormInterface $form
     * @throws InvalidFileArrayException
     */
    protected function handleNotNestedFileUploaded(string|int $fieldSuffix, array $value, UploadedFormInterface $form): bool
    {
        if ($this->isNotNested($fieldSuffix)) {
            $fileData = $this->extractFileData($value);
            $form->addUploadedFile(new UploadedFile(
                $fileData['name'],
                $fileData['full_path'],
                $fileData['type'],
                $fileData['tmp_name'],
                $fileData['error'],
                $fileData['size'],
                $form->getFormName()
            ));
            return true;
        }
        return false;
    }

    /**
     * @param array<int, array<string, string>> $value
     * @param string $fieldSuffix
     * @param UploadedFormInterface $form
     * @throws InvalidFileArrayException
     */
    protected function handleNestedFileUploaded(array $value, string $fieldSuffix, UploadedFormInterface $form): void
    {
        foreach ($value as $fileData) {
            $fileData = $this->extractFileData($fileData);
            $form->addUploadedFile(new UploadedFile(
                $fileData['name'],
                $fileData['full_path'],
                $fileData['type'],
                $fileData['tmp_name'],
                $fileData['error'],
                $fileData['size'],
                $form->getFormName() . '.' . $fieldSuffix
            ));
        }
    }

    /**
     * @param array<string, string> $fileData
     * @return array<string, string>
     * @throws InvalidFileArrayException
     */
    protected function extractFileData(array $fileData): array
    {
        if (6 !== count($fileData)) {
            throw new InvalidFileArrayException('global file array is not valid');
        }
        return [
            'name'      => $name,
            'tmp_name'  => $tmp_name,
            'type'      => $type,
            'full_path' => $fullPath,
            'error'     => $error,
            'size'      => $size
        ] = $fileData;
    }

    protected function isNotNested(string|int $fieldSuffix): bool
    {
        if (!is_string($fieldSuffix)) {
            return true;
        }
        return false;
    }

    /**
     * @param array<string, array<string, mixed>|string> $fieldArray
     * @return bool
     */
    protected function isSingleFormAndSingleFileUploaded(array $fieldArray): bool
    {
        $value = $fieldArray[array_key_first($fieldArray)];
        if (!is_array($value)) {
            return true;
        }
        return false;
    }

}
