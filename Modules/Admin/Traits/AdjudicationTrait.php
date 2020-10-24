<?php

namespace Modules\Admin\Traits;

use Illuminate\Support\Str;
use Modules\Admin\Entities\Answer;
use Modules\Admin\Entities\FinalAnswer;
use Modules\Admin\Entities\AdjudicationFormStatus;
use Modules\Admin\Entities\QuestionAdjudicationRequired;

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
                $isAdjudicationRequired = false;
                $finalAnswer = '';
                $valDifference = 0;
                $isPercentage = 'no';
                $answersArray = [];
                $returnData = [];
                /********************************** */
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


                $isAdjudicationRequired = (bool)$returnData['isAdjudicationRequired'];
                $finalAnswer = (string)$returnData['finalAnswer'];
                $valDifference = (string)$returnData['valDifference'];
                $isPercentage = (string)$returnData['isPercentage'];

                if ($isAdjudicationRequired) {
                    $questionAdjudicationRequiredArray_1 = [
                        'id' => Str::uuid(),
                        'val_difference' => $valDifference,
                        'is_percentage' => $isPercentage,
                        'question_id' => $question->id,
                    ];
                    QuestionAdjudicationRequired::create($questionAdjudicationRequiredArray + $questionAdjudicationRequiredArray_1);
                } else {
                    $finalAnswerArray_1 = [
                        'id' => Str::uuid(),
                        'answer' => $finalAnswer,
                        'question_id' => $question->id,
                        'field_id' => $question->formfields->id,
                    ];
                    FinalAnswer::create($finalAnswerArray + $finalAnswerArray_1);
                }
            }
        }
    }

    public static function checkAdjudicationForNumber($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        $valDifference = 0;
        $isPercentage = 'no';
        $retArray = [];

        $sumOfAnswers = array_sum($answersArray);
        $averageOfSumOfAnswers = $sumOfAnswers / count($answersArray);

        if ($questionAdjudicationStatusObj->adj_status == 'yes') {

            $decisionBasedOn = $questionAdjudicationStatusObj->decision_based_on;
            $operator = $questionAdjudicationStatusObj->opertaor;
            $customValue = $questionAdjudicationStatusObj->custom_value;

            if ($decisionBasedOn == 'custom') {
                $retArray = self::customAdjudication($numberOfAnswers, $answersArray, $operator, $customValue);
                $isAdjudicationRequired = $retArray['isAdjudicationRequired'];
                $valDifference = $retArray['valDifference'];
            } elseif ($decisionBasedOn == 'percentage') {
                $retArray = self::percentageAdjudication($numberOfAnswers, $answersArray, $operator, $customValue);
                $isAdjudicationRequired = $retArray['isAdjudicationRequired'];
                $valDifference = $retArray['valDifference'];
                $isPercentage = 'yes';
            } else {
                $retArray = self::anyChangeAdjudication($numberOfAnswers, $answersArray);
                $isAdjudicationRequired = $retArray['isAdjudicationRequired'];
                $valDifference = $retArray['valDifference'];
            }
        }
        if ($isAdjudicationRequired === false) {
            $finalAnswer = $averageOfSumOfAnswers;
        }

        return [
            'isAdjudicationRequired' => $isAdjudicationRequired,
            'finalAnswer' => $finalAnswer,
            'valDifference' => $valDifference,
            'isPercentage' => $isPercentage,
        ];
    }

    public static function checkAdjudicationForText($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        $valDifference = 0;
        if ($questionAdjudicationStatusObj->adj_status == 'yes') {
            $retArray = self::anyChangeAdjudication($numberOfAnswers, $answersArray);
            $isAdjudicationRequired = $retArray['isAdjudicationRequired'];
            $valDifference = $retArray['valDifference'];
        }
        if ($isAdjudicationRequired === false) {
            $finalAnswer = (string)trim($answersArray[0]);
        }
        return [
            'isAdjudicationRequired' => $isAdjudicationRequired,
            'finalAnswer' => $finalAnswer,
            'valDifference' => $valDifference,
            'isPercentage' => 'no',
        ];
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
        return [
            'isAdjudicationRequired' => $isAdjudicationRequired,
            'finalAnswer' => $finalAnswer,
            'valDifference' => 0,
            'isPercentage' => 'no',
        ];
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
        return [
            'isAdjudicationRequired' => $isAdjudicationRequired,
            'finalAnswer' => $finalAnswer,
            'valDifference' => 0,
            'isPercentage' => 'no',
        ];
    }

    public static function anyChangeAdjudication($numberOfAnswers, $answersArray)
    {

        $isAdjudicationRequired = false;
        if ($numberOfAnswers >= 2) {
            if ((string)trim($answersArray[0]) != (string)trim($answersArray[1])) {
                $isAdjudicationRequired = true;
            }
        }
        if ($numberOfAnswers >= 3 && ($isAdjudicationRequired === false)) {
            if (
                ((string)trim($answersArray[0]) != (string)trim($answersArray[2])) ||
                ((string)trim($answersArray[1]) != (string)trim($answersArray[2]))
            ) {
                $isAdjudicationRequired = true;
            }
        }
        if ($numberOfAnswers >= 4 && ($isAdjudicationRequired === false)) {
            if (
                ((string)trim($answersArray[0]) != (string)trim($answersArray[3])) ||
                ((string)trim($answersArray[1]) != (string)trim($answersArray[3])) ||
                ((string)trim($answersArray[2]) != (string)trim($answersArray[3]))
            ) {
                $isAdjudicationRequired = true;
            }
        }

        return [
            'isAdjudicationRequired' => $isAdjudicationRequired,
            'valDifference' => 0,
        ];
    }

    public static function customAdjudication($numberOfAnswers, $answersArray, $operator, $customValue)
    {
        $isAdjudicationRequired = false;
        $valDifference = 0;

        if ($numberOfAnswers >= 2) {
            $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[1]);
            $isAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
        }
        if ($numberOfAnswers >= 3 && ($isAdjudicationRequired === false)) {
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[2]);
                $isAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
            }
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[1]) - (float)trim($answersArray[2]);
                $isAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
            }
        }
        if ($numberOfAnswers >= 4 && ($isAdjudicationRequired === false)) {
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[3]);
                $isAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
            }
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[1]) - (float)trim($answersArray[3]);
                $isAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
            }
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[2]) - (float)trim($answersArray[3]);
                $isAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
            }
        }

        return [
            'isAdjudicationRequired' => $isAdjudicationRequired,
            'valDifference' => $valDifference,
        ];
    }

    public static function percentageAdjudication($numberOfAnswers, $answersArray, $operator, $customValue)
    {
        sort($answersArray);
        $isAdjudicationRequired = false;
        $valDifference = 0;

        if ($numberOfAnswers >= 2) {
            $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[1]);
            $percentage = ($valDifference / (float)trim($answersArray[0])) * 100;
            $isAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
        }
        if ($numberOfAnswers >= 3 && ($isAdjudicationRequired === false)) {
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[2]);
                $percentage = ($valDifference / (float)trim($answersArray[0])) * 100;
                $isAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
            }
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[1]) - (float)trim($answersArray[2]);
                $percentage = ($valDifference / (float)trim($answersArray[1])) * 100;
                $isAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
            }
        }
        if ($numberOfAnswers >= 4 && ($isAdjudicationRequired === false)) {
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[3]);
                $percentage = ($valDifference / (float)trim($answersArray[0])) * 100;
                $isAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
            }
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[1]) - (float)trim($answersArray[3]);
                $percentage = ($valDifference / (float)trim($answersArray[1])) * 100;
                $isAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
            }
            if ($isAdjudicationRequired === false) {
                $valDifference = (float)trim($answersArray[2]) - (float)trim($answersArray[3]);
                $percentage = ($valDifference / (float)trim($answersArray[2])) * 100;
                $isAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
            }
        }

        return [
            'isAdjudicationRequired' => $isAdjudicationRequired,
            'valDifference' => $valDifference,
        ];
    }

    public static function checkDifference($operator, $valDifference, $customValue)
    {
        $valDifference = abs($valDifference);
        $isAdjudicationRequired = false;
        if ($operator == '>=') {
            if ($valDifference >= $customValue) {
                $isAdjudicationRequired = true;
            }
        } elseif ($operator == '>') {
            if ($valDifference > $customValue) {
                $isAdjudicationRequired = true;
            }
        } elseif ($operator == '<=') {
            if ($valDifference <= $customValue) {
                $isAdjudicationRequired = true;
            }
        } elseif ($operator == '<') {
            if ($valDifference < $customValue) {
                $isAdjudicationRequired = true;
            }
        }
        return $isAdjudicationRequired;
    }
}
