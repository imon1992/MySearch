<?php

class Common
{
    public function getAllIdOfVacancies($data)
    {
        foreach ($data as $val) {
            $arrayOfId[] = $val['id_vacancies'];
        }
        return $arrayOfId;
    }

    public function curlInit($url)
    {
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "count=0&csrfmiddlewaretoken=sTZxFTpI7xU7TtxB4lVfNUTQcT55BFPm");
            curl_setopt($curl, CURLOPT_COOKIE, "__gads=ID=16e61c63986cc981:T=1412706042:S=ALNI_MaOyUGB7e9rZQHHjEP_YdImdbAfyA; __utmt=1; csrftoken=sTZxFTpI7xU7TtxB4lVfNUTQcT55BFPm; __utma=15214883.1329840483.1412706043.1425585907.1425592692.26; __utmb=15214883.18.10.1425592692; __utmc=15214883; __utmz=15214883.1425376018.14.2.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided);");
            $curlResult = curl_exec($curl);
            curl_close($curl);
        }
        return $curlResult;
    }

    public function findKeyWords($fullMapArray, $searchObject)
    {
        foreach ($fullMapArray as $idAndCompanyAndText) {

            foreach ($searchObject as $searchStringObject) {
                if ($searchStringObject->search !== null) {
                    $isAllKeysPresented = $this->isKeyPresent($searchStringObject->search, $idAndCompanyAndText['text']);
                }
                if ($searchStringObject->notPresented !== null) {
                    $isPresentedKeyPresent = $this->isKeyPresent(
                        $searchStringObject->notPresented,
                        $idAndCompanyAndText['text']);
                }
                if ($isAllKeysPresented && !$isPresentedKeyPresent) {
                    $searchResultMap = $this->insertKeyWord($searchResultMap, $searchStringObject->name);
                }
            }
        }
        return $this->putZeroIfKeyNotPresent($searchResultMap, $searchObject);
    }

    protected function isKeyPresent($keyArrays, $idAndCompanyAndText)
    {
        foreach ($keyArrays[0] as $key => $data) {
            $lowSearchString = $keyArrays[0]->$key;
            if (preg_match("/\b($lowSearchString)\b/i", $idAndCompanyAndText)) {
                return true;
            }
        }
        return false;
    }

   protected function insertKeyWord($searchResultMap, $searchString)
    {
        if (null != $searchResultMap[$searchString]) {
            $searchResultMap[$searchString]++;
        } else {
            $searchResultMap[$searchString] = 1;
        }
        return $searchResultMap;
    }

   protected function putZeroIfKeyNotPresent($searchResultMap, $searchObject)
    {
        foreach ($searchObject as $key => $searchStringObject) {
            if (null == $searchResultMap[$searchStringObject->name]) {
                $searchResultMap[$searchStringObject->name] = 0;
            }
        }
        return $searchResultMap;

    }
}