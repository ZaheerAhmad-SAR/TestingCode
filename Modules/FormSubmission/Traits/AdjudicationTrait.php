<?php

namespace Modules\FormSubmission\Traits;

use Illuminate\Support\Str;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\FinalAnswer;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
use Modules\FormSubmission\Entities\QuestionAdjudicationRequired;

trait AdjudicationTrait
{
    public static function runAdjudicationCheckForThisStep($step, $getGradingFormStatusArray)
    {
        $trailLogArray = [];
        $trailLogArray[] = 'system started adjudication';

        $sections = $step->sections;
        foreach ($sections as $section) {
            $questions = $section->questions;
            foreach ($questions as $question) {
                $form_field_name = buildFormFieldName($question->formFields->variable_name);
                $fieldType = $question->form_field_type->field_type;
                if (
                    $fieldType == 'Upload' ||
                    $fieldType == 'Date & Time' ||
                    $fieldType == 'Description'

                ) {
                    continue;
                }
                /********************************** */
                $isQuestionAdjudicationRequired = false;
                $finalAnswer = '';
                $valDifference = 0;
                $isPercentage = 'no';
                $answersArray = [];

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
                $questionAdjudicationStatusObj = $question->questionAdjudicationStatus;

                if ($step->graders_number == $numberOfAnswers) {
                    if ($fieldType == 'Radio') {
                        $returnData =  self::selectMajorityAnswer($questionAdjudicationStatusObj, $answersArray);
                    } elseif ($fieldType == 'Checkbox') {
                        $returnData =  self::selectMajorityAnswer($questionAdjudicationStatusObj, $answersArray);
                    } elseif ($fieldType == 'Dropdown') {
                        $returnData =  self::selectMajorityAnswer($questionAdjudicationStatusObj, $answersArray);
                    } elseif ($fieldType == 'Number' || $fieldType == 'Calculated') {
                        $returnData = self::checkAdjudicationForNumber($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray, $question->form_field_type->decimal_point);
                    } elseif ($fieldType == 'Text') {
                        $returnData =  self::selectMajorityAnswer($questionAdjudicationStatusObj, $answersArray);
                    } elseif ($fieldType == 'Textarea') {
                        $returnData = self::checkAdjudicationForText($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray);
                    }
                } else {
                    $returnData = [
                        'isQuestionAdjudicationRequired' => true,
                        'finalAnswer' => $finalAnswer,
                        'valDifference' => $valDifference,
                        'isPercentage' => $isPercentage,
                    ];
                }
                $isQuestionAdjudicationRequired = (bool)$returnData['isQuestionAdjudicationRequired'];
                $finalAnswer = (string)$returnData['finalAnswer'];
                $valDifference = (string)$returnData['valDifference'];
                $isPercentage = (string)$returnData['isPercentage'];

                /************************************* */
                /************************************* */
                /************************************* */
                $questionAdjudicationRequiredArray = [
                    'study_id' => $getGradingFormStatusArray['study_id'],
                    'subject_id' => $getGradingFormStatusArray['subject_id'],
                    'study_structures_id' => $getGradingFormStatusArray['study_structures_id'],
                    'phase_steps_id' => $getGradingFormStatusArray['phase_steps_id'],
                    'section_id' => $section->id,
                    'question_id' => $question->id,
                ];
                $questionAdjudicationRequiredArray_1 = [
                    'id' => Str::uuid(),
                    'val_difference' => $valDifference,
                    'is_percentage' => $isPercentage,

                ];
                $finalAnswerArray = [
                    'study_id' => $getGradingFormStatusArray['study_id'],
                    'subject_id' => $getGradingFormStatusArray['subject_id'],
                    'study_structures_id' => $getGradingFormStatusArray['study_structures_id'],
                    'phase_steps_id' => $getGradingFormStatusArray['phase_steps_id'],
                    'section_id' => $section->id,
                    'question_id' => $question->id,
                    'variable_name' => $form_field_name,
                    'field_id' => $question->formfields->id,
                ];
                $finalAnswerArray_1 = [
                    'id' => Str::uuid(),
                    'answer' => $finalAnswer,
                ];


                FinalAnswer::updateOrCreate($finalAnswerArray, $finalAnswerArray_1);
                /************************************* */
                /************************************* */
                /************************************* */
                if ($isQuestionAdjudicationRequired) {
                    QuestionAdjudicationRequired::deleteAdjudicationRequiredQuestion($questionAdjudicationRequiredArray);
                    QuestionAdjudicationRequired::create($questionAdjudicationRequiredArray + $questionAdjudicationRequiredArray_1);
                } else {
                    /***********************
                     *  Trail Log
                     */
                    $finalAnswer = FinalAnswer::find($finalAnswerArray_1['id']);
                    $trailLogArray = $finalAnswer->attributesToArray();
                    $trailLogArray['form_type_id'] = $step->form_type_id;
                    $trailLogArray['form_type'] = 'Adjudication Form';
                    $trailLogArray['modility_id'] = $step->modility_id;
                    $trailLogArray['answer_id'] = $finalAnswer->id;
                }
            }
        }
        if (count($trailLogArray) > 0) {
            /***********************
             *  Trail Log
             */
            $formAddOrEdit = 'Add';
            $formType = 'System Adjudication Form';

            eventDetails($trailLogArray, $formType, $formAddOrEdit, request()->ip, []);
            /********************* */
        }
    }

    public static function make_abs($array)
    {
        $abs_array = array();
        foreach ($array as $key => $value)
            $abs_array[$key] = abs($value);
        return $abs_array;
    }

    public static function checkAdjudicationForNumber($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray, $decimalPoint)
    {
        $isQuestionAdjudicationRequired = false;
        $finalAnswer = '';
        $valDifference = 0;
        $isPercentage = 'no';
        $retArray = [];

        sort($answersArray);

        $sumOfAnswers = array_sum($answersArray);
        $averageOfSumOfAnswers = $sumOfAnswers / count($answersArray);

        if ($questionAdjudicationStatusObj->adj_status == 'yes') {

            $decisionBasedOn = $questionAdjudicationStatusObj->decision_based_on;
            $operator = $questionAdjudicationStatusObj->opertaor;
            $customValue = $questionAdjudicationStatusObj->custom_value;

            if ($decisionBasedOn == 'custom') {
                $retArray = self::customAdjudication($numberOfAnswers, $answersArray, $operator, $customValue);
                $isQuestionAdjudicationRequired = $retArray['isQuestionAdjudicationRequired'];
                $valDifference = $retArray['valDifference'];
                $finalAnswer = $retArray['finalAnswer'];
            } elseif ($decisionBasedOn == 'percentage') {
                $retArray = self::percentageAdjudication($numberOfAnswers, $answersArray, $operator, $customValue);
                $isQuestionAdjudicationRequired = $retArray['isQuestionAdjudicationRequired'];
                $valDifference = $retArray['valDifference'];
                $finalAnswer = $retArray['finalAnswer'];
                $isPercentage = 'yes';
            } else {
                $retArray = self::anyChangeAdjudication($numberOfAnswers, $answersArray);
                $isQuestionAdjudicationRequired = $retArray['isQuestionAdjudicationRequired'];
                $valDifference = $retArray['valDifference'];
            }
        }
        if ($isQuestionAdjudicationRequired == false && $finalAnswer == '') {
            $finalAnswer = number_format((float)$averageOfSumOfAnswers, $decimalPoint);
        }

        return [
            'isQuestionAdjudicationRequired' => $isQuestionAdjudicationRequired,
            'finalAnswer' => $finalAnswer,
            'valDifference' => $valDifference,
            'isPercentage' => $isPercentage,
        ];
    }

    public static function checkAdjudicationForText($questionAdjudicationStatusObj, $numberOfAnswers, $answersArray)
    {
        $retArray = [
            'isQuestionAdjudicationRequired' => false,
            'finalAnswer' => '',
            'valDifference' => 0,
            'isPercentage' => 'no',
        ];

        if ($questionAdjudicationStatusObj->adj_status == 'yes') {
            $retArray =  self::selectMajorityAnswer($questionAdjudicationStatusObj, $answersArray);
        }
        return $retArray;
    }


    public static function selectMajorityAnswer($questionAdjudicationStatusObj, $answersArray)
    {

        $isQuestionAdjudicationRequired = false;
        $finalAnswer = '';
        $countedArray = array_count_values($answersArray);

        if ($questionAdjudicationStatusObj->adj_status == 'yes') {
            if (
                (count($answersArray) > 1) &&
                (count($countedArray) == count($answersArray))
            ) {
                $isQuestionAdjudicationRequired = true;
            }
        }
        if ($isQuestionAdjudicationRequired == false) {
            arsort($countedArray);
            $finalAnswer = array_keys($countedArray)[0];
        }
        return [
            'isQuestionAdjudicationRequired' => $isQuestionAdjudicationRequired,
            'finalAnswer' => $finalAnswer,
            'valDifference' => 0,
            'isPercentage' => 'no',
        ];
    }

    public static function anyChangeAdjudication($numberOfAnswers, $answersArray)
    {

        $isQuestionAdjudicationRequired = false;
        if ($numberOfAnswers >= 2) {
            if ((string)trim($answersArray[0]) != (string)trim($answersArray[1])) {
                $isQuestionAdjudicationRequired = true;
            }
        }
        if ($numberOfAnswers >= 3 && ($isQuestionAdjudicationRequired == false)) {
            if (
                ((string)trim($answersArray[0]) != (string)trim($answersArray[2])) ||
                ((string)trim($answersArray[1]) != (string)trim($answersArray[2]))
            ) {
                $isQuestionAdjudicationRequired = true;
            }
        }
        if ($numberOfAnswers >= 4 && ($isQuestionAdjudicationRequired == false)) {
            if (
                ((string)trim($answersArray[0]) != (string)trim($answersArray[3])) ||
                ((string)trim($answersArray[1]) != (string)trim($answersArray[3])) ||
                ((string)trim($answersArray[2]) != (string)trim($answersArray[3]))
            ) {
                $isQuestionAdjudicationRequired = true;
            }
        }

        return [
            'isQuestionAdjudicationRequired' => $isQuestionAdjudicationRequired,
            'valDifference' => 0,
        ];
    }

    public static function customAdjudication($numberOfAnswers, $answersArray, $operator, $customValue)
    {

        $isQuestionAdjudicationRequired = false;
        $valDifference = 0;
        $finalAnswer = '';
        $valuesInRange = [];

        if ($numberOfAnswers == 1) {
            $valuesInRange[] = (float)trim($answersArray[0]);
        } elseif ($numberOfAnswers == 2) {

            $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[1]);
            $isQuestionAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
            if ($isQuestionAdjudicationRequired == false) {
                $valuesInRange[] = (float)trim($answersArray[0]);
                $valuesInRange[] = (float)trim($answersArray[1]);
            }
        } elseif ($numberOfAnswers == 3) {

            $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[1]);
            $isQuestionAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
            if ($isQuestionAdjudicationRequired == false) {
                $valuesInRange[] = (float)trim($answersArray[0]);
                $valuesInRange[] = (float)trim($answersArray[1]);
            }
            if ($isQuestionAdjudicationRequired == false) {

                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[2]);
                $isQuestionAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
                if ($isQuestionAdjudicationRequired == false) {
                    $valuesInRange[] = (float)trim($answersArray[2]);
                }
            }
        } elseif ($numberOfAnswers == 4) {

            $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[1]);
            $isQuestionAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
            if ($isQuestionAdjudicationRequired == false) {
                $valuesInRange[] = (float)trim($answersArray[0]);
                $valuesInRange[] = (float)trim($answersArray[1]);
            }

            if ($isQuestionAdjudicationRequired == false) {

                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[2]);
                $isQuestionAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
                if ($isQuestionAdjudicationRequired == false) {
                    $valuesInRange[] = (float)trim($answersArray[2]);
                }
            }

            if ($isQuestionAdjudicationRequired == false) {

                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[3]);
                $isQuestionAdjudicationRequired = self::checkDifference($operator, $valDifference, $customValue);
                if ($isQuestionAdjudicationRequired == false) {
                    $valuesInRange[] = (float)trim($answersArray[3]);
                }
            }
        }

        $countValuesInRange = count($valuesInRange);
        if ($isQuestionAdjudicationRequired == false && $countValuesInRange == 1) {
            $finalAnswer = $valuesInRange[0];
        } elseif ($isQuestionAdjudicationRequired == true && $countValuesInRange > 1) {
            $isQuestionAdjudicationRequired = false;
            $finalAnswer = (array_sum($valuesInRange) / $countValuesInRange);
        }

        return [
            'isQuestionAdjudicationRequired' => $isQuestionAdjudicationRequired,
            'finalAnswer' => $finalAnswer,
            'valDifference' => $valDifference,
            'isPercentage' => 'no',
        ];
    }

    public static function percentageAdjudication($numberOfAnswers, $answersArray, $operator, $customValue)
    {
        $isQuestionAdjudicationRequired = false;
        $valDifference = 0;
        $finalAnswer = '';
        $valuesInRange = [];

        if ($numberOfAnswers == 1) {
            $valuesInRange[] = (float)trim($answersArray[0]);
        } elseif ($numberOfAnswers == 2) {
            $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[1]);
            $percentage = ($valDifference / (float)trim($answersArray[0])) * 100;
            $isQuestionAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
            if ($isQuestionAdjudicationRequired == false) {
                $valuesInRange[] = (float)trim($answersArray[0]);
                $valuesInRange[] = (float)trim($answersArray[1]);
            }
        }
        if ($numberOfAnswers == 3) {

            $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[1]);
            $percentage = ($valDifference / (float)trim($answersArray[0])) * 100;
            $isQuestionAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
            if ($isQuestionAdjudicationRequired == false) {
                $valuesInRange[] = (float)trim($answersArray[0]);
                $valuesInRange[] = (float)trim($answersArray[1]);
            }

            if ($isQuestionAdjudicationRequired == false) {
                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[2]);
                $percentage = ($valDifference / (float)trim($answersArray[0])) * 100;
                $isQuestionAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
                if ($isQuestionAdjudicationRequired == false) {
                    $valuesInRange[] = (float)trim($answersArray[2]);
                }
            }
        }
        if ($numberOfAnswers == 4) {
            $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[1]);
            $percentage = ($valDifference / (float)trim($answersArray[0])) * 100;
            $isQuestionAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
            if ($isQuestionAdjudicationRequired == false) {
                $valuesInRange[] = (float)trim($answersArray[0]);
                $valuesInRange[] = (float)trim($answersArray[1]);
            }
            if ($isQuestionAdjudicationRequired == false) {
                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[2]);
                $percentage = ($valDifference / (float)trim($answersArray[0])) * 100;
                $isQuestionAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
                if ($isQuestionAdjudicationRequired == false) {
                    $valuesInRange[] = (float)trim($answersArray[2]);
                }
            }
            if ($isQuestionAdjudicationRequired == false) {
                $valDifference = (float)trim($answersArray[0]) - (float)trim($answersArray[3]);
                $percentage = ($valDifference / (float)trim($answersArray[0])) * 100;
                $isQuestionAdjudicationRequired = self::checkDifference($operator, $percentage, $customValue);
                if ($isQuestionAdjudicationRequired == false) {
                    $valuesInRange[] = (float)trim($answersArray[3]);
                }
            }
        }
        $countValuesInRange = count($valuesInRange);
        if ($isQuestionAdjudicationRequired == false && $countValuesInRange == 1) {
            $finalAnswer = $valuesInRange[0];
        } elseif ($isQuestionAdjudicationRequired == true && $countValuesInRange > 1) {
            $isQuestionAdjudicationRequired = false;
            $finalAnswer = (array_sum($valuesInRange) / $countValuesInRange);
        }

        return [
            'isQuestionAdjudicationRequired' => $isQuestionAdjudicationRequired,
            'finalAnswer' => $finalAnswer,
            'valDifference' => $valDifference,
            'isPercentage' => 'no',
        ];
    }

    public static function checkDifference($operator, $valDifference, $customValue)
    {
        $valDifference = abs($valDifference);
        $isQuestionAdjudicationRequired = false;
        if ($operator == '>=') {
            if ($valDifference >= $customValue) {
                $isQuestionAdjudicationRequired = true;
            }
        } elseif ($operator == '>') {
            if ($valDifference > $customValue) {
                $isQuestionAdjudicationRequired = true;
            }
        } elseif ($operator == '<=') {
            if ($valDifference <= $customValue) {
                $isQuestionAdjudicationRequired = true;
            }
        } elseif ($operator == '<') {
            if ($valDifference < $customValue) {
                $isQuestionAdjudicationRequired = true;
            }
        }
        return $isQuestionAdjudicationRequired;
    }
}
