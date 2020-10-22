<?php

namespace Modules\Admin\Traits;

use Modules\Admin\Entities\Answer;

trait AdjudicationTrait
{
    public static function runAdjudicationCheckForThisStep($step, $getGradingFormStatusArray)
    {
        $sections = $step->sections;
        foreach ($sections as $section) {
            $questions = $section->questions;
            foreach ($questions as $question) {
                $fieldType = $question->form_field_type->field_type;
                if (
                    $fieldType === 'Upload' ||
                    $fieldType === 'Date & Time'
                ) {
                    continue;
                }
                /********************************** */
                $answersArray = [];
                $getAnswerArray = [
                    'study_id' => $getGradingFormStatusArray['study_id'],
                    'subject_id' => $getGradingFormStatusArray['subject_id'],
                    'study_structures_id' => $getGradingFormStatusArray['study_structures_id'],
                    'phase_steps_id' => $getGradingFormStatusArray['phase_steps_id'],
                    'section_id' => $section->id,
                    'question_id' => $question->id,
                    'field_id' => $question->formfields->id
                ];
                $answersArray = Answer::getAnswersArray($getAnswerArray);
                $numberOfAnswers = count($answersArray);
                $returnData = [];
                $questionAdjudicationStatusObj = $question->AdjStatus;

                if ($fieldType === 'Radio') {
                    $returnData = self::checkAdjudicationForRadio($questionAdjudicationStatusObj, $answersArray);
                } elseif ($fieldType === 'Checkbox') {
                    $returnData = self::checkAdjudicationForCheckBox($questionAdjudicationStatusObj, $answersArray);
                } elseif ($fieldType === 'Dropdown') {
                    $returnData = self::checkAdjudicationForRadio($questionAdjudicationStatusObj, $answersArray);
                } elseif ($fieldType === 'Number') {
                    $returnData = self::checkAdjudicationForNumber($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray);
                } elseif ($fieldType === 'Text') {
                    $returnData = self::checkAdjudicationForText($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray);
                } elseif ($fieldType === 'Textarea') {
                    $returnData = self::checkAdjudicationForText($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray);
                }


                $isAdjudicationRequired = $returnData['isAdjudicationRequired'];
                $finalAnswer = $returnData['finalAnswer'];
            }
        }
    }

    public static function checkAdjudicationForNumber($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        $sumOfAnswers = array_sum($answersArray);
        $averageOfSumOfAnswers = $sumOfAnswers / count($answersArray);

        if ($questionAdjudicationStatusObj->adj_status == 'yes') {

            $decisionBasedOn = $questionAdjudicationStatusObj->decision_based_on;
            $operator = $questionAdjudicationStatusObj->opertaor;
            $customValue = $questionAdjudicationStatusObj->custom_value;

            if ($decisionBasedOn == 'custom') {
                $isAdjudicationRequired = self::customAdjudication($numberOfAnswers, $answersArray, $operator, $customValue);
            } elseif ($decisionBasedOn == 'percentage') {
                $isAdjudicationRequired = self::percentageAdjudication($numberOfAnswers, $answersArray, $operator, $customValue);
            } else {
                $isAdjudicationRequired = self::anyChangeAdjudication($numberOfAnswers, $answersArray);
            }
        }
        if ($isAdjudicationRequired === false) {
            $finalAnswer = $averageOfSumOfAnswers;
        }
        return ['isAdjudicationRequired' => $isAdjudicationRequired, 'finalAnswer' => $finalAnswer];
    }

    public static function checkAdjudicationForText($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        if ($questionAdjudicationStatusObj->adj_status == 'yes') {
            $isAdjudicationRequired = self::anyChangeAdjudication($numberOfAnswers, $answersArray);
        }
        if ($isAdjudicationRequired === false) {
            $finalAnswer = $answersArray[0];
        }
        return ['isAdjudicationRequired' => $isAdjudicationRequired, 'finalAnswer' => $finalAnswer];
    }

    public static function checkAdjudicationForRadio($questionAdjudicationStatusObj, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        $countedArray = array_count_values($answersArray);

        if ($questionAdjudicationStatusObj->adj_status == 'yes') {
            if (
                (count($answersArray) > 1) &&
                (count($countedArray) == count($answersArray))
            ) {
                $isAdjudicationRequired = true;
            }
        }
        if ($isAdjudicationRequired === false) {
            arsort($countedArray);
            $finalAnswer = array_keys($countedArray)[0];
        }
        return ['isAdjudicationRequired' => $isAdjudicationRequired, 'finalAnswer' => $finalAnswer];
    }

    public static function checkAdjudicationForCheckBox($questionAdjudicationStatusObj, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        $countedArray = array_count_values($answersArray);

        if ($questionAdjudicationStatusObj->adj_status == 'yes') {
            if (
                (count($answersArray) > 1) &&
                (count($countedArray) == count($answersArray))
            ) {
                $isAdjudicationRequired = true;
            }
        }
        if ($isAdjudicationRequired === false) {
            arsort($countedArray);
            $finalAnswer = array_keys($countedArray)[0];
        }

        return ['isAdjudicationRequired' => $isAdjudicationRequired, 'finalAnswer' => $finalAnswer];
    }

    public static function anyChangeAdjudication($numberOfAnswers, $answersArray)
    {
        $isAdjudicationRequired = false;
        if ($numberOfAnswers >= 2) {
            if ($answersArray[0] != $answersArray[1]) {
                $isAdjudicationRequired = true;
            }
        }
        if ($numberOfAnswers >= 3) {
            if (
                ($answersArray[0] != $answersArray[2]) ||
                ($answersArray[1] != $answersArray[2])
            ) {
                $isAdjudicationRequired = true;
            }
        }
        if ($numberOfAnswers >= 4) {
            if (
                ($answersArray[0] != $answersArray[3]) ||
                ($answersArray[1] != $answersArray[3]) ||
                ($answersArray[2] != $answersArray[3])
            ) {
                $isAdjudicationRequired = true;
            }
        }
        return $isAdjudicationRequired;
    }

    public static function customAdjudication($numberOfAnswers, $answersArray, $operator, $customValue)
    {
        $isAdjudicationRequired = false;
        $isAdjudicationRequired_1 = false;
        $isAdjudicationRequired_2 = false;
        $isAdjudicationRequired_3 = false;
        $isAdjudicationRequired_4 = false;
        $isAdjudicationRequired_5 = false;
        $isAdjudicationRequired_6 = false;

        if ($numberOfAnswers >= 2) {
            $difference = $answersArray[0] - $answersArray[1];
            $isAdjudicationRequired_1 = self::checkDifference($operator, $difference, $customValue);
        }
        if ($numberOfAnswers >= 3) {

            $difference = $answersArray[0] - $answersArray[2];
            $isAdjudicationRequired_2 = self::checkDifference($operator, $difference, $customValue);

            $difference = $answersArray[1] - $answersArray[2];
            $isAdjudicationRequired_3 = self::checkDifference($operator, $difference, $customValue);
        }
        if ($numberOfAnswers >= 4) {

            $difference = $answersArray[0] - $answersArray[3];
            $isAdjudicationRequired_4 = self::checkDifference($operator, $difference, $customValue);

            $difference = $answersArray[1] - $answersArray[3];
            $isAdjudicationRequired_5 = self::checkDifference($operator, $difference, $customValue);

            $difference = $answersArray[2] - $answersArray[3];
            $isAdjudicationRequired_6 = self::checkDifference($operator, $difference, $customValue);
        }

        if (
            $isAdjudicationRequired_1 == true ||
            $isAdjudicationRequired_2 == true ||
            $isAdjudicationRequired_3 == true ||
            $isAdjudicationRequired_4 == true ||
            $isAdjudicationRequired_5 == true ||
            $isAdjudicationRequired_6 == true
        ) {
            $isAdjudicationRequired = true;
        }

        return $isAdjudicationRequired;
    }

    public static function percentageAdjudication($numberOfAnswers, $answersArray, $operator, $customValue)
    {
        sort($answersArray);
        $isAdjudicationRequired = false;
        $isAdjudicationRequired_1 = false;
        $isAdjudicationRequired_2 = false;
        $isAdjudicationRequired_3 = false;
        $isAdjudicationRequired_4 = false;
        $isAdjudicationRequired_5 = false;
        $isAdjudicationRequired_6 = false;

        if ($numberOfAnswers >= 2) {
            $difference = $answersArray[0] - $answersArray[1];
            $percentage = ($difference / $answersArray[0]) * 100;
            $isAdjudicationRequired_1 = self::checkDifference($operator, $percentage, $customValue);
        }
        if ($numberOfAnswers >= 3) {

            $difference = $answersArray[0] - $answersArray[2];
            $percentage = ($difference / $answersArray[0]) * 100;
            $isAdjudicationRequired_2 = self::checkDifference($operator, $percentage, $customValue);

            $difference = $answersArray[1] - $answersArray[2];
            $percentage = ($difference / $answersArray[1]) * 100;
            $isAdjudicationRequired_3 = self::checkDifference($operator, $percentage, $customValue);
        }
        if ($numberOfAnswers >= 4) {

            $difference = $answersArray[0] - $answersArray[3];
            $percentage = ($difference / $answersArray[0]) * 100;
            $isAdjudicationRequired_4 = self::checkDifference($operator, $percentage, $customValue);

            $difference = $answersArray[1] - $answersArray[3];
            $percentage = ($difference / $answersArray[1]) * 100;
            $isAdjudicationRequired_5 = self::checkDifference($operator, $percentage, $customValue);

            $difference = $answersArray[2] - $answersArray[3];
            $percentage = ($difference / $answersArray[2]) * 100;
            $isAdjudicationRequired_6 = self::checkDifference($operator, $percentage, $customValue);
        }

        if (
            $isAdjudicationRequired_1 == true ||
            $isAdjudicationRequired_2 == true ||
            $isAdjudicationRequired_3 == true ||
            $isAdjudicationRequired_4 == true ||
            $isAdjudicationRequired_5 == true ||
            $isAdjudicationRequired_6 == true
        ) {
            $isAdjudicationRequired = true;
        }

        return $isAdjudicationRequired;
    }

    public static function checkDifference($operator, $difference, $customValue)
    {
        $difference = abs($difference);
        $isAdjudicationRequired = false;
        if ($operator == '>=') {
            if ($difference >= $customValue) {
                $isAdjudicationRequired = true;
            }
        } elseif ($operator == '>') {
            if ($difference > $customValue) {
                $isAdjudicationRequired = true;
            }
        } elseif ($operator == '<=') {
            if ($difference <= $customValue) {
                $isAdjudicationRequired = true;
            }
        } elseif ($operator == '<') {
            if ($difference < $customValue) {
                $isAdjudicationRequired = true;
            }
        }
        return $isAdjudicationRequired;
    }
}
