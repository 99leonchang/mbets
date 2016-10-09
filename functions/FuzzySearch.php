<?php
namespace Fuzzy;

/**
 * Class FuzzySearch
 * @package Fuzzy
 */
class FuzzySearch
{


    /**
     * Search function
     *
     * @param array $dataset
     * @param $term
     * @param int $threshold
     * @return array
     */
    public function search(array $dataset, $term, $threshold = 4){
        $best_distance = -1;
        $best_result = null;
        $best_def = null;

        foreach($dataset as $data){
            $check = $this->search_checks(strtolower($term), strtolower($data->title), $best_distance);
            if($check[0] != false){
                if($check[0] == true && $check[1] == 0)
                    return array(0,$data->title,$data->definition);
                $best_distance = $check[1];
                $best_result = $data->title;
                $best_def = $data->definition;
                //echo $best_result."\n";
            }
        }
        return array($best_distance,$best_result, $best_def);
    }

    /**
     * Runs algorithmic checks. Uses Levenshtein Distance Algorithm
     *
     * @param $query
     * @param $term
     * @param $best_distance
     * @return array
     */
    private function search_checks($query, $term, $best_distance){
        $queryLength = strlen($query); //search term
        $termLength = strlen($term); //iterated term
        $current_distance = levenshtein($query, $term);
        if($queryLength > $termLength) {
            return array(false);
        }
        if($queryLength === $termLength) {
            if($query === $term)
                return array(true,0);
        }
        for($char_index = 0, $next_char_index = 0; $char_index < $queryLength; $char_index++){
            $query_character = ord($query[$char_index]);
            //echo $query_character." ";
            while($next_char_index < $termLength){
                if(ord($term[$next_char_index++]) == $query_character)
                    continue 2;
            }
            return array(false);
        }
        if($best_distance == -1 || $current_distance < $best_distance){
            return array(true,$current_distance);
        }
        return array(false);
    }

    /**
     * Parses string to include soft-linking
     *
     * @param $string
     * @return string
     */
    public function parse_link($string){
        $fullString = "";
        $tempString = "";
        $state = false;
        for($i = 0, $len = strlen($string); $i<$len; $i++){
            if($string[$i] == "_"){
                if(!$state){
                    $state = true;
                }else{
                    $state = false;
                    $fullString .= "<a href=\"singledef.php?term=" . $tempString . "\" target=\"_blank\">" . $tempString . "</a>";
                    $tempString = "";
                }
            }else if($state){
                $tempString .= $string[$i];
            }else if(!$state){
                $fullString .= $string[$i];
            }
        }
        return $fullString;
    }


}