<?php

namespace Nano\Http\Services\Files;

use Nano\Http\Exceptions\InvalidFileArrayException;
use Nano\Http\Interfaces\Service\UploadedFormInterface;
use Nano\Http\Serializers\GlobalFileArraySerializer;

class UploadRegistry
{
    /** @var array<UploadedForm> $uploadedFieldArray */
    protected array $uploadedFieldArray = [];
    public function __construct(
        protected array $globalArray,
    ) {
        $serializedArray = GlobalFileArraySerializer::serialize($this->globalArray);
        foreach ($serializedArray as $fieldName => $fieldArray) {

            $form = $this->addUploadedField($fieldName);
            if ($this->handleSingleFormSingleFileUploaded($fieldArray, $form)) {
                continue;
            }
            foreach ($fieldArray as $fieldSuffix => $value) {
                if ($this->handleNotNestedFileUploaded($fieldSuffix, $value, $form)) {
                    continue;
                }
                $this->handleNestedFileUploaded($value, $fieldSuffix, $form);
            }
        }
        $serializedArray = null;
    }

    /**
     * @param mixed $fieldName
     * @return array<UploadedFile>
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

    public function getUploadedFieldNames(): array 
    {
        $result = [];
        foreach ($this->uploadedFieldArray as $field) {
            foreach ($field->getFieldNames() as $fieldName) {
                $result[] = $fieldName;
            }
        }
        return $result;
    }

    protected function addUploadedField(string $fieldName): UploadedForm
    {
        $index = array_push($this->uploadedFieldArray, new UploadedForm($fieldName));
        return $this->uploadedFieldArray[--$index];
    }

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

    protected function handleNotNestedFileUploaded($fieldSuffix, $value, UploadedFormInterface $form): bool
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

    protected function isSingleFormAndSingleFileUploaded(array $fieldArray): bool
    {
        $value = $fieldArray[array_key_first($fieldArray)];
        if (!is_array($value)) {
            return true;
        }
        return false;
    }

}
