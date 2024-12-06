<?php

class Search extends Model
{
    protected function conditionBuilder($columnName, $criteriaCondition, $criteriaValue, $criteriaType) {

        $conditions = [
            '=' => '=',
            '!=' => '!=',
            'starts' => 'like',
            '!starts' => 'not like',
            'contains' => 'like',
            '!contains' => 'not like',
            'ends' => 'like',
            '!ends' => 'not like',
            '<' => '<',
            '<=' => '<=',
            '>' => '>',
            '>=' => '>=',
            'null' => 'null',
            '!null' => '!null',
            'between' => 'between',
            '!between' => 'not between'
        ];

        if($criteriaType == 'date') {

            if($criteriaCondition == '=') {
                $criteriaCondition = 'contains';
            }

            if($criteriaCondition == '!=') {
                $criteriaCondition = '!contains';
            }

        }

        $condition = $conditions[$criteriaCondition];
        $value = ($criteriaValue != '') ? $criteriaValue[0] : '';

        if( $criteriaCondition == 'starts' || $criteriaCondition == '!starts' ) {
            return " AND $columnName $condition '$value%'";
        }

        if( $criteriaCondition == 'ends' || $criteriaCondition == '!ends' ) {
            return " AND $columnName $condition '%$value'";
        }

        if($criteriaCondition == 'contains' || $criteriaCondition == '!contains') {
            return " AND $columnName $condition '%$value%'";
        }

        if($criteriaCondition == 'null') {
            return " AND ($columnName = '' OR $columnName IS NULL)";
        }

        if($criteriaCondition == '!null') {
            return " AND ($columnName != '' AND $columnName IS NOT NULL)";
        }

        if((
                $criteriaCondition == '<' ||
                $criteriaCondition == '<=' ||
                $criteriaCondition == '>' ||
                $criteriaCondition == '>='
            )
            && is_numeric($value)
        ) {
            return " AND $columnName $condition $value";
        }

        if(
            ($criteriaCondition == 'between' || $criteriaCondition == '!between') &&
            $value != '' &&
            isset($criteriaValue[1]) &&
            $criteriaValue[1] != ''
        ) {
            $value2 = $criteriaValue[1];

            if($criteriaType == 'date') {
                $value = "'$value'";
                $value2 = "'$value2'";
            }

            return " AND ($columnName $condition $value AND $value2)";
        }

        if(
            $criteriaCondition == '=' ||
            $criteriaCondition == '!=' ||
            $criteriaCondition == '<' ||
            $criteriaCondition == '<=' ||
            $criteriaCondition == '>' ||
            $criteriaCondition == '>='
        ) {
            return " AND $columnName $condition '$value'";
        }

    }

    protected function queryBuilder($options, $sqlQuery) {

        if(isset($options['searchBuilder'])) {

            foreach ($options['searchBuilder']['criteria'] as $key => $criteria) {

                if(!isset($criteria['condition'])) continue;

                $columnName = $criteria['origData'];

                if($columnName == 'name') {
                    $columnName = "CONCAT(firstname, ' ', lastname)";
                }

                $value = isset($criteria['value']) ? $criteria['value'] : '';

                $conditionBuilder = $this->conditionBuilder($columnName, $criteria['condition'], $value, $criteria['type']);
                $sqlQuery .= "$conditionBuilder";

            }

        }

        $filteredQuery = $this->database->query($sqlQuery, [1]);

        if(isset($options['order'])) {

            $order = $options['order'][0];
            $orderCol = $order['column'];
            $orderDir = strtoupper($order['dir']);

            $columnName = $options['columns'][$orderCol]['data'];

            $sqlQuery .= " ORDER BY $columnName $orderDir";

        }

        $offset = $options['start'];
        $length = $options['length'];
        if($length == '-1') {
            $length = 100000000;
        }

        $sqlQuery .= " LIMIT $length OFFSET $offset";

        $dataQuery = $this->database->query($sqlQuery, [1]);

        return [
            "filteredQuery" => $filteredQuery,
            "dataQuery" => $dataQuery
        ];

    }

}