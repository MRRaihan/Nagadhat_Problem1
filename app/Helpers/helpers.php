<?php

if (!function_exists('countKeywords')) {
    /**
     * @param $filterResult
     * @param $keywords
     * @return array
     */
    function countKeywords($filterResult, $keywords)
    {
        $keywordCounts = [];
        if (count($filterResult) > 0) {
            foreach ($filterResult as $entry) {
                $keyword = $entry->search_keyword;
                if (array_key_exists($keyword, $keywordCounts)) {
                    $keywordCounts[$keyword]++;
                } else {
                    $keywordCounts[$keyword] = 1;
                }
            }
        }

        foreach ($keywords as $keyword) {
            if (!array_key_exists($keyword, $keywordCounts)) {
                $keywordCounts[$keyword] = 0;
            }
        }
        return $keywordCounts;
    }
}

