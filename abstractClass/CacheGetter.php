<?php
abstract class cacheGetter{
    abstract protected function formationMapWithText($arrayWithVacancyInfo);
    public function getMapWithText($arrayWithVacancyInfo){
        return $this->formationMapWithText($arrayWithVacancyInfo);
    }
    protected function getAllIdOfVacancies($data)
    {
        foreach ($data as $val) {
            $arrayOfId[] = $val['id_vacancies'];
        }
        return $arrayOfId;
    }
}