<?php

class ProcessingWithTableNameAndField
{
    public function generateVacancyInfoTableName($className)
    {
        preg_match("/_\w+/", $className, $tableNameWith_);
        $tableName = trim($tableNameWith_[0], '_') . '_vacancy_info';
        return $tableName;
    }

    public function generateCitiesTableName($className)
    {
        preg_match("/_\w+/", $className, $tableNameWith_);
        $tableName = trim($tableNameWith_[0], '_') . '_cities';
        return $tableName;
    }

    public function generateTagsTableName($className)
    {
        preg_match("/_\w+/", $className, $tableNameWith_);
        $tableName = trim($tableNameWith_[0], '_') . '_tags';
        return $tableName;
    }

    public function generateTableFieldsIdVacancyAndText($tableName)
    {
        $tableFieldIdVacancy = $tableName . '.id_vacancies';
        $tableFieldTextVacancy = $tableName . '.text_vacancies';
        return ['fieldIdVacancy' => $tableFieldIdVacancy, 'fieldTextVacancy' => $tableFieldTextVacancy];
    }

    public function generateTableFieldIDateAddVacancyInfo($tableName)
    {
        $tableFieldDateAdd = $tableName . '.date_add';
        return $tableFieldDateAdd;
    }


    public function generateTableFieldIdVacancy($tableName)
    {
        $tableFieldIdVacancy = $tableName . '.id_vacancy';
        return $tableFieldIdVacancy;
    }

    public function generateTableFieldTagTags($tableName)
    {
        $tableFieldTag = $tableName . '.tag';
        return $tableFieldTag;
    }

    public function generateTableFieldCityCities($tableName)
    {
        $tableFieldCity = $tableName . '.city';
        return $tableFieldCity;
    }
}