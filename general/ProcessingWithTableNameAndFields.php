<?php

class ProcessingWithTableNameAndField{
    public function generateVacancyInfoTableName($className){
        preg_match("/_\w+/", $className, $tableNameWith_);
        $tableName = trim($tableNameWith_[0],'_') . '_vacancy_info';
        return $tableName;
    }
    public function generateCitiesTableName($className){
        preg_match("/_\w+/", $className, $tableNameWith_);
        $tableName = trim($tableNameWith_[0],'_') . '_cities';
        return $tableName;
    }

    public function generateTableFieldsIdVacancyAndText($tableName){
        $tableFieldIdVacancy = $tableName . '.id_vacancies';
        $tableFieldTextVacancy = $tableName . '.text_vacancies';
        return ['fieldIdVacancy'=>$tableFieldIdVacancy,'fieldTextVacancy'=>$tableFieldTextVacancy];
    }
}